<?php
/**
* Fixtures -- A Simple PHP Class to convert YAML into SQL queries
* @version 0.1 -- 2008-05-01
* @author Georgi Momchilov <gmomchilov@gmail.com>
* @copyright Copyright 2008 Georgi Momchilov
* @license http://www.opensource.org/licenses/mit-license.php MIT License
* @package Migrations
*/

App::Import('vendor', 'georgious-cakephp-yaml-migrations-and-fixtures/spyc/spyc');

class Fixtures{
    const SPYC_CLASS_NOT_FOUND  = 0;
    const YAML_FILE_NOT_FOUND   = -1;
    const YAML_FILE_IS_INVALID  = -2;
    const YAML_FILE_NOT_LOADED  = -3;
    const TABLE_NOT_FOUND       = -4;

    var $sPrefix = '';
    var $oDb;

    /**
    * Constructor - checks dependencies and loads the connection
    *
    * @param string $sConnecion The connection from database.php to use. Deafaults to "default"
    * @return void
    */
    function Fixtures($sConnection = 'default'){
        if(class_exists('Spyc'))
            $this->bSpycReady = true;
        $this->oDb = & ConnectionManager::getDataSource($sConnection);
    }

    function import($sFile){
        if( !$this->bSpycReady )
            return self::SPYC_CLASS_NOT_FOUND;

        if( !file_exists( $sFile ) )
            return self::YAML_FILE_NOT_FOUND;

        $this->aTables = SPYC::YAMLload( file_get_contents( $sFile ) );
        if( !is_array( $this->aTables ) )
            return self::YAML_FILE_IS_INVALID;

        uses('model'.DS.'model');

        $oDB = $this->oDb;
        $aAllowedTables = $oDB->listSources();

        foreach( $this->aTables as $table => $records ){
            if( !in_array( $oDB->config['prefix'].$table, $aAllowedTables ) ){
                return self::TABLE_NOT_FOUND;
            }
            $temp_model = new Model( false, $table );
            foreach( $records as $record_num => $record_value ){
                if( !isset( $record_value['id'] ) ){
                    $record_value['id'] = $record_num;
                }
                if( !$temp_model->save( $record_value ) ){
                    return array( 'error' => array( 'table' => $table, 'record' => $record_value ) );
                }
            }
        }
        return true;
    }
}