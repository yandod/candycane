<?php
/**
* Migrations -- A Simple PHP Class to convert YAML into SQL queries
* @version 0.2 -- 2008-04-08
* @author Georgi Momchilov <gmomchilov@gmail.com>
* @copyright Copyright 2008 Georgi Momchilov
* @license http://www.opensource.org/licenses/mit-license.php MIT License
* @package Migrations
*/

App::Import('vendor', 'georgious-cakephp-yaml-migrations-and-fixtures/spyc/spyc');
App::import('Core', 'ConnectionManager');

class Migrations{
    const SPYC_CLASS_NOT_FOUND  = 0;
    const YAML_FILE_NOT_FOUND   = -1;
    const YAML_FILE_IS_INVALID  = -2;
    const YAML_FILE_NOT_LOADED  = -3;
    
    var $aTasks = array();
    
    var $aTypes = array(
        'string',
        'text',
        'integer',
        'int',
        'blob',
        'boolean',
        'bool',
        'float',
        'date',
        'datetime',
        'time',
        'timestamp'
    );
    
    var $aUuid_format = array(
        'type'    => 'text',
        'length'  => 36
    );
    
    var $aId_format = array(
        'type'    => 'integer',
        'length'  => 10,
        'key' => 'primary'
    );
    
    var $bUse_uuid = false;
    
    var $bLoaded = false;
    
    var $bSpycReady = false;
    
    var $oDb;
        
    /**
    * Constructor - checks dependencies and loads the connection
    *
    * @param string $sConnecion The connection from database.php to use. Deafaults to "default"
    * @return void
    */
    function Migrations($sConnection = 'default'){
        if(class_exists('Spyc'))
            $this->bSpycReady = true;
        $this->oDb = & ConnectionManager::getDataSource($sConnection);
    }
    
    /**
    * Executes all queries from the UP section
    *
    * @return mixed True on success and an array of errors on failure
    */
    function strtoupper(){
        return $this->_run('UP');
    }
    
    /**
    * Executes all queries from the DOWN section
    *
    * @return mixed True on success and an array of errors on failure
    */
    function down(){
        return $this->_run('DOWN');
    }
    
    /**
    * Generates an YAML file from the current DB schema
    */
    function generate(){
        $aResult = array();
        $aResult['UP'] = $aResult['DOWN'] = array();
        $aResult['UP']['create_table'] = $aResult['DOWN']['drop_table'] = array();
        
        $aTables = $this->oDb->listSources();
        foreach( $aTables as $sTable ){
            $sTableName = str_replace( $this->getPrefix(), '', $sTable );
            $aTableSchema = $this->_buildSchema( $sTableName );
            $aResult['UP']['create_table'][$sTableName] = $aTableSchema;
            $aResult['DOWN']['drop_table'][] = $sTableName;
        }
        
        return Spyc::YAMLDump($aResult);
    }
    
    /**
    * Loads the YAML file with the YAML schema and parses it into self::aTasks
    *
    * @param string $sFile Path to the YAML file
    * @return mixed True on success and an Error code ( see class constants ) on failure
    */
    function load($sFile){
        if( !$this->bSpycReady )
            return self::SPYC_CLASS_NOT_FOUND;
        
        if( !file_exists( $sFile ) )
            return self::YAML_FILE_NOT_FOUND;

        $this->aSchema = SPYC::YAMLload( file_get_contents( $sFile ) );

        $this->aTasks = array();
        $this->aTasks['UP'] = $this->aTasks['DOWN'] = array();
        
        if( !is_array( $this->aSchema ) || !isset( $this->aSchema['UP'] ) || !isset( $this->aSchema['DOWN'] ) )
            return self::YAML_FILE_IS_INVALID;
        
        foreach( $this->aSchema as $sDirection => $sAction ){
            foreach( $this->aSchema[$sDirection] as $sAction => $aElement ){
                foreach( $aElement as $sName => $aProperties ){
                    if( $sAction == 'create_table' || $sAction == 'create_tables' ){
                        $this->aTasks[$sDirection][] = $this->create_table( $sName, $aProperties );
                    }
                    elseif( $sAction == 'drop_table' || $sAction == 'drop_tables'){
                            $this->aTasks[$sDirection][] = $this->drop_table( $aProperties );
                    }
                    elseif( $sAction == 'rename_table' || $sAction == 'rename_tables'){
                        $this->aTasks[$sDirection][] = $this->rename_table( $sName, $aProperties['name'] );
                    }
                    elseif( $sAction == 'merge_table' || $sAction == 'merge_tables' ){
                        $this->aTasks[$sDirection] = am( $this->aTasks[$sDirection], $this->merge_table( $sName, $aProperties ) );
                    }
                    elseif( $sAction == 'truncate_table' || $sAction == 'truncate_tables'){
                        $this->aTasks[$sDirection][] = $this->truncate_table( $sName );
                    }
                    elseif( $sAction == 'add_fields' || $sAction == 'add_field' || $sAction == 'add_columns' || $sAction == 'add_column'){
                        foreach( $aProperties as $sN => $aV )
                            $this->aTasks[$sDirection][] = $this->add_field( $sName, array( $sN => $aV ) );
                    }
                    elseif( $sAction == 'alter_field' || $sAction == 'alter_fields' || $sAction == 'alter_column' || $sAction == 'alter_columns'){
                        foreach( $aProperties as $sN => $aV )
                            $this->aTasks[$sDirection][] = $this->alter_field( $sName, array( $sN => $aV ) );
                    }
                    elseif( $sAction == 'drop_field' || $sAction == 'drop_fields' || $sAction == 'drop_column' || $sAction == 'drop_columns'){
                        foreach( $aProperties as $sField )
                            $this->aTasks[$sDirection][] = $this->drop_field( $sName, $sField );
                    }
                    elseif( $sAction == 'query' || $sAction == 'queries'){
                        $this->aTasks[$sDirection][] = $aElement;
                    }
                }
            }
        }
        $this->bLoaded = true;
        return true;
    }
    
    function getPrefix(){
        return $this->oDb->config['prefix'];
    }
        
    /**
    * Generate SQL for create table. If no_id and/or no_dates is specified, it will autogenerate an id field and dates fields ( created, modified )
    *
    * @param string $sTable Table name
    * @param array $aFields Array of fields in format array('field'=>array('type'=>'int','length'=>10,'null'=>true,'primary'=>true,'auto_increment'=>true))
    */
    function create_table($sTable, $aFields = array()){
        //initialization
        $aIndexes = array();
        
        $aKeys = array();
        $aPrimary = array(); 
        $aPrimary['column'] = array();

        $sSql = 'CREATE TABLE IF NOT EXISTS `'.$this->getPrefix().$sTable.'`('."\n\t";
        
        //Flag no_id - autogenerate an id field unless it is explicitly stated this is not needed
        if( !in_array( 'no_id', $aFields ) ){
            $aFormat = ( $this->bUse_uuid ) ? $this->aUuid_format : $this->aId_format;
            $sSql .= $this->oDb->buildColumn( am( array( 'name' => 'id' ), $aFormat ) ).", \n\t";
            $aPrimary['column'][] = 'id';
        }
        
        //Go through all fields and generate the respective sql depending on the DB driver. Add respective keys' information, if needed
        foreach( $aFields as $sName => $aValue ){
            $sSql .= $this->_buildColumn( $sName, $aValue );
            if( is_array( $aValue ) && !empty( $aValue['primary'] ) )
                $aPrimary['column'][] = $sName;
            elseif( is_array( $aValue ) && !empty( $aValue['unique'] ) )
                $aKeys[$sName] = array( 'unique' => true, 'column' => $sName );
            elseif( is_array( $aValue ) && !empty( $aValue['index'] ) )
                $aKeys[$sName] = array( 'index' => true, 'column' => $sName );
        }

        //Flag no_dates - autogenerate dates fields ( created, modified ) unless it is explicitly stated this is not needed
        if( !in_array( 'no_dates', $aFields ) ){
            $sSql .= $this->oDb->buildColumn( array( 'name' => 'created', 'type' => 'datetime' ) ).", \n\t";
            $sSql .= $this->oDb->buildColumn( array( 'name' => 'modified', 'type' => 'datetime' ) ).", \n\t";
        }

        //Append table keys - primary, index and unique to the sql
        if( count( $aPrimary['column'] ) )
            $aIndexes['PRIMARY'] = $aPrimary;
        if( count( $aKeys ) )
            $aIndexes = am( $aIndexes, $aKeys );
        
        if( count( $aIndexes ) ){
            $sIndexes = $this->oDb->buildIndex( $aIndexes );
            //cakephp is backward incompatible!!!!
            if( is_array( $sIndexes ) )
                $sIndexes = join(",\n\t", $sIndexes);
        
            $sSql .= $sIndexes."\n\t";
        }
        
        //Clear the data and return
        $sSql = trim( $sSql, ", \n\t" );
        $sSql .= ');';
        return $sSql;
    }
    
    /**
    * Generate a SQL array for merging a table. If no_id and/or no_dates is specified, it will autogenerate an id field and dates fields ( created, modified )
    *
    * @param string $sTable Table name
    * @param array $aFields Array of fields in format array('field'=>array('type'=>'int','length'=>10,'null'=>true,'primary'=>true,'auto_increment'=>true))
    */
    function merge_table($sTable, $aFields = array()){
        //check whether that table exists at all
        if( !in_array( $this->getPrefix().$sTable, $this->oDb->listSources() ) ){
            return array( $this->create_table( $sTable, $aFields ) );
        }
        
        //table does not exist - merge it
        $aQueries = array();
        $aIndexes = array();
        $aCurrentFields = $this->oDb->describe( new Model( false, $sTable ) );
        
        //no_id and no_dates first

        //there should be an id according to the new schema
        if( !in_array( 'no_id', $aFields ) ){
            $aIdFormat = ( $this->bUse_uuid ) ? $this->aUuid_format : $this->aId_format;
            //but there is no such one in the current schema
            if( !array_key_exists( 'id', $aCurrentFields ) ){
                $aQueries[] = $this->add_field( $sTable, array( 'id' => $aIdFormat ) );
                $aQueries[] = 'ALTER TABLE `'.$this->getPrefix().$sTable.'` ADD '.$this->oDb->buildIndex( array('PRIMARY' => array( 'column' => array('id') ) ) );
            }
        }
        //there should be no id - drop it
        else if( array_key_exists( 'id', $aCurrentFields ) ){
            $aQueries[] = $this->drop_field( $sTable, 'id' );
            unset( $aFields[ array_search( 'no_id', $aFields, true ) ] );
        }

        //work with the current fields - drop or alter
        foreach( $aCurrentFields as $sCurrentFieldName => $aCurrentFieldProperties )
                if( $sCurrentFieldName != 'id' &&
                    $sCurrentFieldName != 'created' &&
                    $sCurrentFieldName != 'modified'
                  ){
            //field doesn't exist anymore
            if( !array_key_exists( $sCurrentFieldName, $aFields ) ){
                $aQueries[] = $this->drop_field( $sTable, $sCurrentFieldName );
            }
            else{
                //cope with null
                if( in_array( 'not_null', $aFields[ $sCurrentFieldName ] ) ){
                    $aFields[ $sCurrentFieldName ]['null'] = false;
                    unset( $aFields[ $sCurrentFieldName ][ array_search( 'not_null', $aFields[ $sCurrentFieldName ], true ) ] );
                }
                elseif( in_array( 'is_null', $aFields[ $sCurrentFieldName ] ) ){
                    $aFields[ $sCurrentFieldName ]['null'] = true;
                    unset( $aFields[ $sCurrentFieldName ][ array_search( 'is_null', $aFields[ $sCurrentFieldName ], true ) ] );
                }
                else{
                    $aFields[ $sCurrentFieldName ]['null'] = true;
                }
                
                //properties are different
                if( $aCurrentFieldProperties != $aFields[ $sCurrentFieldName ] ){
                    $aQueries[] = $this->alter_field( $sTable, array( $sCurrentFieldName => $aFields[ $sCurrentFieldName ] ) );
                }
                
                //keys ( except for primary! ) - add keys only if they don't already exist
                $aKeys = array();
                if( !empty( $aFields[ $sCurrentFieldName ]['unique'] ) && 
						( !isset( $aCurrentFieldProperties['key'] ) || $aCurrentFieldProperties['key'] != "unique" )
						){
                    $aQueries[] = $this->add_key( $sTable, array( 'unique' => $sCurrentFieldName ) );
                }
                if( !empty( $aFields[ $sCurrentFieldName ]['index'] ) && 
						( !isset( $aCurrentFieldProperties['key'] ) || $aCurrentFieldProperties['key'] != "index" )
						){
                    $aQueries[] = $this->add_key( $sTable, array( 'index' => $sCurrentFieldName ) );
                }
                        
                unset( $aFields[ $sCurrentFieldName ] );
            }
        }
        //then - add any new fields if necessary
        foreach( $aFields as $sFieldName => $aFieldProperties )if( is_string( $sFieldName ) ){
            $aQueries[] = $this->add_field( $sTable, array( $sFieldName => $aFieldProperties ) );
            //keys ( except for primary! )
            $aKeys = array();
            if( !empty( $aFields[ $sFieldName ]['unique'] ) ){
                $aQueries[] = $this->add_key( $sTable, array( 'unique' => $sFieldName ) );
            }
            if( !empty( $aFields[ $sFieldName ]['index'] ) ){
                $aQueries[] = $this->add_key( $sTable, array( 'index' => $sFieldName ) );
            }
        }
        
        //there should be dates fields according to the new schema
        if( !in_array( 'no_dates', $aFields ) ){
            //but there is no such one in the current schema
            if( !array_key_exists( 'created', $aCurrentFields )  ){
                $aQueries[] = $this->add_field( $sTable, array( 'created' => array( 'type' => 'datetime' ) ) );
                $aQueries[] = $this->add_field( $sTable, array( 'modified' => array( 'type' => 'datetime' ) ) );
            }
            unset( $aFields[ array_search( 'no_dates', $aFields, true ) ] );
        }
        //there should be no dates fields - drop 'em
        else if( array_key_exists( 'created', $aCurrentFields ) ){
            $aQueries[] = $this->drop_field( $sTable, 'created' );
            $aQueries[] = $this->drop_field( $sTable, 'modified' );
        }
                
        return $aQueries;
    }
        
    /**
    * Generate SQL for rename table
    */
    function rename_table( $sTable, $sName ){
        $sSql = 'ALTER TABLE `'.$this->getPrefix().$sTable.'` RENAME TO `'.$sName.'`;';
        return $sSql;
    }
        
    /**
    * Generate SQL for drop table
    */
    function drop_table($sTable){
        $sSql = 'DROP TABLE IF EXISTS `'.$this->getPrefix().$sTable.'`;';
        return $sSql;
    }
    
    /**
    * Generate SQL for truncate table
    */
    function truncate_table($sTable){
        $sSql = 'TRUNCATE `'.$this->getPrefix().$sTable.'`;';
        return $sSql;
    }
        
    /**
    * Generate SQL for add field
    */
    function add_field( $sTable, $aField ){
        $sSql = 'ALTER TABLE `'.$this->getPrefix().$sTable.'` ADD '.$this->_buildColumn( key( $aField ), $aField[key($aField)] );
        $sSql = trim( $sSql, ", \n\t" ).';';
        return $sSql;
    }
        
    /**
    * Generate SQL for drop field
    */
    function drop_field( $sTable, $column ){
        $sSql = 'ALTER TABLE `'.$this->getPrefix().$sTable.'` DROP `'.$column.'`;';
        return $sSql;
    }
        
    /**
    * Generate SQL for alter field
    */
    function alter_field( $sTable, $aField ){
        $sSql = 'ALTER TABLE `'.$this->getPrefix().$sTable.'` CHANGE `'.key( $aField ).'` '.$this->_buildColumn( 
                ( !empty( $aField['name'] ) ? $aField['name'] : key( $aField ) ),
                  $aField[key($aField)] );
        $sSql = trim( $sSql, ", \n\t" );
        return $sSql;
    }
    
    /**
    * Generate SQL for keys ( index and unique )
    */
    function add_key( $sTable, $aKey ){
        $sType = key( $aKey );
        $sColumn = $aKey[ $sType ];
        $sSql = 'ALTER TABLE `'.$this->getPrefix().$sTable.'` ADD '.$this->oDb->buildIndex( array( $sColumn => array( $sType => true, 'column' => $sColumn ) ) );
        return $sSql;
    }
        
    /**
    * If there is a query - just return it
    */
    function query( $query ){
        return $query;
    }
    
    /**
    * Internal wrapper for DBoSource::buildColumn()
    * 
    * @access protected
    * @param string $sName Field name
    * @param string $aValue Values - can contain 'type', 'length', 'default', 'null', 'auto_increment'
    * @return SQL
    */
    function _buildColumn( $sName, $aValue ){
        $sSql = '';
        $aValue = $this->_formatProperties( $aValue );
        if( in_array( $aValue['type'], $this->aTypes ) ){
            $sSql = $this->oDb->buildColumn( am( array( 'name' => $sName ), $aValue ) ).", \n\t";
        };
        return $sSql;
    }
    
    /**
    * Format field properties
    */
    function _formatProperties( $aProps ){
        if( !is_array( $aProps ) )
            return;
        
        //turn array to hash
        if( isset( $aProps[0] ) && !isset( $aProps['type'] ) ){
            $aProps['type'] = $aProps[0];
            $aProps['length'] = $aProps[1];
            $aProps['null'] = $aProps[2];
            unset( $aProps[0], $aProps[1], $aProps[2] );
        }
        
        if( empty( $aProps['type'] ) ){
            $aProps['type'] = 'string';
            $aProps['length'] = '255';
        }
        
        if ($aProps['type'] == 'int'){
            $aProps['type'] = 'integer';
        }
        
        if ($aProps['type'] == 'bool'){
            $aProps['type'] = 'boolean';
            $aProps['length'] = 1;
        }

        $aResult = array( 'type' => $aProps['type'] );
        
        if( !empty( $aProps['length'] ) ) 
            $aResult['length'] = $aProps['length'];

        if( in_array( 'not_null', $aProps ) )
            $aResult['null'] = false;
        
        if( in_array( 'is_null', $aProps ) )
            $aResult['null'] = true;

        if( !empty( $aProps['default'] ) ) 
            $aResult['default'] = $aProps['default'];

        if( !empty( $aProps['primary'] ) ) 
            $aResult['key'] = 'primary';

        return $aResult;
    }
    
    /**
    * Executes tasks in the specified section ( UP or DOWN )
    *
    * @access protected
    * @param string $sDirection
    * @return mixed True on success and an array of errors on failure
    */
    function _run($sDirection){
        if( !$this->bLoaded )
            return self::YAML_FILE_NOT_LOADED;

        $aErrors = array();
        foreach( $this->aTasks[$sDirection] as $sTask ){
            if( !$this->oDb->execute( $sTask ) )
                $aErrors[] = array( 'sql' => $sTask, 'error' => $this->oDb->error );
        }

        if( count( $aErrors ) )
            return $aErrors;

        return true;
    }
    
    /**
    * Build an array holding a db schema for a table
    *
    * @access protected
    * @param string $sDirection
    * @return mixed True on success and an array of errors on failure
    */
    function _buildSchema( $sTableName ){
        $oTempModel = new Model( false, $sTableName );
        $aModelFields = $this->oDb->describe($oTempModel);
        $aTableSchema = array();
        foreach($aModelFields as $sKey=>$aItem){
            if($sKey!='id' && $sKey!='created' && $sKey!='modified'){
                $default = !empty($aItem['default']) ? $aItem['default'] : 'false';
                $setNull = $aItem['null']==true ? 'is_null' : 'not_null';
                $aTableSchema[$sKey] = array('type'=>$aItem['type'],
                                               'default'=>$default,
                                               'length'=>$aItem['length'] );
                if( !empty( $aItem['key'] ) ){
                    $aTableSchema[$sKey][$aItem['key']] = true;
                }
                $aTableSchema[$sKey][] = $setNull;
            }
        }
        if(!array_key_exists('id', $aModelFields)){
            $aTableSchema[] = 'no_id';
        }
        if(!array_key_exists('created', $aModelFields)){
            $aTableSchema[] = 'no_dates';
        }
        
        return $aTableSchema;
    }
}