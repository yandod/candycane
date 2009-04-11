<?php
/**
 * Fixtures is a CakePHP shell script that imports data from your YAML files
 *
 * Run 'cake fixtures help' for more info and help on using this script.
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright 2008, Georgi Momchilov
 * @link        http://ovalpixels.com
 * @author      Georgi Momchilov
 * @since       CakePHP(tm) v 1.2
 * @license     http://www.opensource.org/licenses/mit-license.php The MIT License
 * 
*/

uses('file', 'folder');
App::import('vendor','fixtures');


class FixturesShell extends Shell {

    var $sConnection = 'default';
    var $oFixtures;

    /**
    * Initializes some paths and checks for the required classes
    */
    function startup(){
        $sPath = APP_PATH .'config' .DS. 'fixtures';

        if(isset($this->params['p'])) $sPath = $this->params['p'];
        if(isset($this->params['path'])) $sPath = $this->params['path'];
        
        define('FIXTURES_PATH', $sPath );
        
        if(isset($this->params['c'])) $this->sConnection = $this->params['c'];
        if(isset($this->params['connection'])) $this->sConnection = $this->params['connection'];
        
        if( !class_exists( 'Fixtures' ) )
            $this->error( 'File not found', 'Fixtures class is needed for this shell to run. Could not be found - exiting.' );
        
        $this->oFixtures = new Fixtures( $this->sConnection );
        
        $this->_welcome();
    }

    /**
    * Main method: Imports all fixtures from the fixtures path
    */
    function main(){
        if( !class_exists('Spyc') )
            $this->error( 'File not found', 'YAML class is needed for this shell to run. Could not be found - exiting.' );
        
        $oFolder = new Folder(FIXTURES_PATH);
        $aFixtures = $oFolder->find('.+_fixture\.yml');
        $oFixtures = new Fixtures( $this->sConnection );
        $iCount = 0;
        foreach( $aFixtures as $sFixture ){
            $iCount++;
            if( $oFixtures->import( FIXTURES_PATH. DS . $sFixture ) !== true ){
                $this->error( 'Import failed.', 'Sorry, there was an error inserting the data into your database' );
            }
            else{
                $this->out( 'Importing '.$sFixture.'...' );
                $this->out( '' );
            }
        }
        $this->out($iCount. ' fixture(s) successfully imported');
    }
    
    /**
    * Help method
    */
    function help(){
        $this->hr();
        $this->out('');
        $this->out('Fixtures help you import your data into your database in a DB engine');
        $this->out('agnostic manner.');
        $this->out('');
        $this->out('Fixture files are YAML files.');
        $this->out('');
        $this->hr();
        $this->out('');
        $this->out('COMMAND LINE OPTIONS');
        $this->out('');
        $this->out('  cake fixtures');
        $this->out('    - Imports all fixture files ( .+_fixture\.yml )');
        $this->out('');
        $this->out('  cake migrate help');
        $this->out('    - Displays this Help');
        $this->out('');
        $this->out("    append '-c [connection]' to the command if you want to specify the");
        $this->out('    connection to use from database.php. By default it uses "default"');
        $this->out('');
        $this->out("    append '-p [path]' to the command if you want to specify the");
        $this->out('    path where the fixture files reside. Default is APP_PATH . \'config\' .DS. \'fixtures\' ');
        $this->out('');
        $this->out('');
        $this->out('For more information and for the latest release of this and others,');
        $this->out('go to http://ovalpixels.com');
        $this->out('');
        $this->hr();
        $this->out('');
    }
}