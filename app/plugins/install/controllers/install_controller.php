<?php
/**
 * Install Controller
 *
 * PHP version 5
 *
 * @category Controller
 * @package  Croogo
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class InstallController extends InstallAppController {
/**
 * Controller name
 *
 * @var string
 * @access public
 */
	var $name = 'Install';
/**
 * No models required
 *
 * @var array
 * @access public
 */
    var $uses = null;
/**
 * No components required
 *
 * @var array
 * @access public
 */
    var $components = null;
/**
 * beforeFilter
 *
 * @return void
 */
    function beforeFilter() {
        parent::beforeFilter();
      	$this->L10n = new L10n();
      	$lang = $this->L10n->get();
      	Configure::write('Config.language', $lang);
        $this->layout = 'install';
        App::import('Component', 'Session');
        $this->Session = new SessionComponent;
    }
/**
 * Step 0: welcome
 *
 * A simple welcome message for the installer.
 *
 * @return void
 */
    function index() {
        $this->pageTitle = __('Installation: Welcome', true);
        chmod(TMP,'755');
        chmod(APP.'config','755');
    }
/**
 * Step 1: database
 *
 * @return void
 */
    function database() {
        $this->pageTitle = __('Step 1: Database', true);
        if (!empty($this->data)) {
            // test database connection
            if (mysql_connect($this->data['Install']['host'], $this->data['Install']['login'], $this->data['Install']['password']) &&
                mysql_select_db($this->data['Install']['database'])) {
                // rename database.php.install
                rename(APP.'config'.DS.'database.php.install', APP.'config'.DS.'database.php');

                // open database.php file
                App::import('Core', 'File');
                $file = new File(APP.'config'.DS.'database.php', true);
                $content = $file->read();

                // write database.php file
                $content = str_replace('{default_host}', $this->data['Install']['host'], $content);
                $content = str_replace('{default_login}', $this->data['Install']['login'], $content);
                $content = str_replace('{default_password}', $this->data['Install']['password'], $content);
                $content = str_replace('{default_database}', $this->data['Install']['database'], $content);
                // The database import script does not support prefixes at this point
                $content = str_replace('{default_prefix}', ''/*$this->data['Install']['prefix']*/, $content);
                
                if($file->write($content) ) {
                    $this->redirect(array('action' => 'data'));
                } else {
                    $this->Session->setFlash(__('Could not write database.php file.', true));
                }
            } else {
                $this->Session->setFlash(__('Could not connect to database.', true));
            }
        }
    }
/**
 * Step 2: insert required data
 *
 * @return void
 */
    function data() {
        $this->pageTitle = __('Step 2: Run SQL', true);
        //App::import('Core', 'Model');
        //$Model = new Model;

        if (isset($this->params['named']['run'])) {
            App::import('Core', 'File');
            App::import('Model', 'ConnectionManager');
            $db = ConnectionManager::getDataSource('default');

            if(!$db->isConnected()) {
                $this->Session->setFlash(__('Could not connect to database.', true));
            } else {
                $this->__executeSQLScript($db, CONFIGS.'sql'.DS.'dump.sql');
                $this->__updateData(); //translate names
                $this->redirect(array('action' => 'finish'));
                exit();
            }
        }
    }
/**
 * Step 3: finish
 *
 * Remind the user to delete 'install' plugin.
 *
 * @return void
 */
    function finish() {
        $this->pageTitle = __('Installation completed successfully', true);
        if (isset($this->params['named']['delete'])) {
            App::import('Core', 'Folder');
            $this->folder = new Folder;
            if ($this->folder->delete(APP.'plugins'.DS.'install')) {
                $this->Session->setFlash(__('Installataion files deleted successfully.', true));
                $this->redirect('/');
                exit();
            } else {
                $this->Session->setFlash(__('Could not delete installation files.', true));
            }
        }
    }
/**
 * Execute SQL file
 *
 * @link http://cakebaker.42dh.com/2007/04/16/writing-an-installer-for-your-cakephp-application/
 * @param object $db Database
 * @param string $fileName sql file
 * @return void
 */
    function __executeSQLScript($db, $fileName) {
        $statements = file_get_contents($fileName);
        $statements = explode(';', $statements);

        foreach ($statements as $statement) {
            if (trim($statement) != '') {
                $db->query($statement);
            }
        }
    }

    function __updateData(){
        $data = array(
            'Enumeration' => array(
                1 => __('User documentation',true),
                2 => __('Technical documentation',true),
                3 => __('Low',true),
                4 => __('Normal',true),
                5 => __('High',true),
                6 => __('Urgent',true),
                7 => __('Immediate',true),
                8 => __('Design',true),
                9 => __('Development',true)                
            ),
            'IssueStatus' => array(
                1 => __('New',true),
                2 => __('Assigned',true),
                3 => __('Resolved',true),
                4 => __('Feedback',true),
                5 => __('Closed',true),
                6 => __('Rejected',true)                
            ),
            'Role' => array(
                3 => __('Manager',true),
                4 => __('Developer',true),
                5 => __('Reporter',true)                
            ),
            'Tracker' => array(
                1 => __('Bug',true),
                2 => __('Feature',true),
                3 => __('Support',true)                
            )
        );
        foreach ($data as $model_name => $map) {
            app::import('model',$model_name);
            $obj =& ClassRegistry::init($model_name);
            foreach ($map as $id => $name) {
                $obj->id = $id;
                $obj->saveField('name',$name);
            }            
        }
        
    }
}
?>