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
class CcInstallController extends CcInstallAppController {
/**
 * Controller name
 *
 * @var string
 * @access public
 */
	var $name = 'CcInstall';
/**
 * No models required
 *
 * @var array
 * @access public
 */
    public $uses = array();
/**
 * No components required
 *
 * @var array
 * @access public
 */
    public $components = array('Session');
/**
 * beforeFilter
 *
 * @return void
 */
    function beforeFilter() {
		Configure::write('debug', 0);
        parent::beforeFilter();
        App::uses('L10n', 'I18n');
        App::uses('SessionComponent', 'Controller/Component');
      	$this->L10n = new L10n();
      	$lang = $this->L10n->get();
      	Configure::write('Config.language', $lang);
        $this->layout = 'install';
    }
/**
 * Step 0: welcome
 *
 * A simple welcome message for the installer.
 *
 * @return void
 */
    function index() {
        $this->pageTitle = __('Installation: Welcome');
		$url = Router::url(array(
			'plugin' => 'cc_install',
			'controller' => 'cc_install',
			'action' => 'route'
			),
			true
		);
		$file = json_decode(file_get_contents($url),true);
		$this->set('file',$file);
    }

	function route() {
		$this->layout = null;
		Configure::write('debug', 0);
	}
/**
 * Step 1: database
 *
 * @return void
 */
    function database() {
        $this->pageTitle = __('Step 1: Database');
        if (!empty($this->data)) {
            // test database connection
            if (mysql_connect($this->data['Install']['host'], $this->data['Install']['login'], $this->data['Install']['password']) &&
                mysql_select_db($this->data['Install']['database'])) {
                // rename database.php.install
                copy(APP.'Config'.DS.'database.php.install', APP.'Config'.DS.'database.php');

                // open database.php file
                App::uses('File', 'Utility');
                $file = new File(APP.'Config'.DS.'database.php', true);
                $content = $file->read();
                

                // split host information
                $hostinfo =  explode(':', $this->data['Install']['host'], 2);
                $host = $hostinfo[0];
                $port = '';
                if (count($hostinfo) >= 2) {
                    $port = $hostinfo[1];
                }

                // write database.php file
                $content = str_replace('{default_host}', $host, $content);
                $content = str_replace('{default_port}', $port, $content);
                $content = str_replace('{default_login}', $this->data['Install']['login'], $content);
                $content = str_replace('{default_password}', $this->data['Install']['password'], $content);
                $content = str_replace('{default_database}', $this->data['Install']['database'], $content);
                // The database import script does not support prefixes at this point
                $content = str_replace('{default_prefix}', ''/*$this->data['Install']['prefix']*/, $content);
                
                if($file->write($content) ) {
                    $this->redirect(array('action' => 'data'));
                } else {
                    $this->Session->setFlash(__('Could not write database.php file.'));
                }
            } else {
                $this->Session->setFlash(__('Could not connect to database.'));
            }
        }
    }
/**
 * Step 2: insert required data
 *
 * @return void
 */
    function data() {
        $this->pageTitle = __('Step 2: Run SQL');
        //App::import('Core', 'Model');
        //$Model = new Model;

        if (isset($this->request['named']['run'])) {
            App::import('Core', 'File');
            App::import('Model', 'ConnectionManager');
            $db = ConnectionManager::getDataSource('default');

            if(!$db->isConnected()) {
                $this->Session->setFlash(__('Could not connect to database.'));
            } else {
                $this->__executeSQLScript($db, APP . 'Config' . DS.'sql'.DS.'dump.sql');
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
        $this->pageTitle = __('Installation completed successfully');
        if (isset($this->params['named']['delete'])) {
            App::uses('Folder', 'Utility');
            $this->folder = new Folder;
            if ($this->folder->delete(APP.'Plugin'.DS.'CcInstall')) {
                Cache::clear(false, '_cake_core_');
                $this->Session->setFlash(__('Installataion files deleted successfully.'));
                $this->redirect('/');
                exit();
            } else {
                $this->Session->setFlash(__('Could not delete installation files.'));
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
                1 => __('User documentation'),
                2 => __('Technical documentation'),
                3 => __('Low'),
                4 => __('Normal'),
                5 => __('High'),
                6 => __('Urgent'),
                7 => __('Immediate'),
                8 => __('Design'),
                9 => __('Development')                
            ),
            'IssueStatus' => array(
                1 => __('New'),
                2 => __('Assigned'),
                3 => __('Resolved'),
                4 => __('Feedback'),
                5 => __('Closed'),
                6 => __('Rejected')                
            ),
            'Role' => array(
                3 => __('Manager'),
                4 => __('Developer'),
                5 => __('Reporter')                
            ),
            'Tracker' => array(
                1 => __('Bug'),
                2 => __('Feature'),
                3 => __('Support')                
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
