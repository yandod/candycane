<?php
/**
 * Admin Controller
 *
 * @package candycane
 * @subpackage candycane.controllers
 */
#  helper :sort
#  include SortHelper 
#
#  def index
#    @no_configuration_data = Redmine::DefaultData::Loader::no_data?
#  end
# 
  
#  def plugins
#    @plugins = Redmine::Plugin.all
#  end
#  
#  # Loads the default configuration
#  # (roles, trackers, statuses, workflow, enumerations)
#  def default_configuration
#    if request.post?
#      begin
#        Redmine::DefaultData::Loader::load(params[:lang])
#        flash[:notice] = l(:notice_default_data_loaded)
#      rescue Exception => e
#        flash[:error] = l(:error_can_t_load_default_data, e.message)
#      end
#    end
#    redirect_to :action => 'index'
#  end
#  
#  
#  def info
#    @db_adapter_name = ActiveRecord::Base.connection.adapter_name
#    @flags = {
#      :default_admin_changed => User.find(:first, :conditions => ["login=? and hashed_password=?", 'admin', User.hash_password('admin')]).nil?,
#      :file_repository_writable => File.writable?(Attachment.storage_path),
#      :plugin_assets_writable => File.writable?(Engines.public_directory),
#      :rmagick_available => Object.const_defined?(:Magick)
#    }
#  end  
#end
class AdminController extends AppController {

  var $name = 'Admin';
  var $uses = array('Project');
  var $helpers = array('Candy');
  var $components = array('Sort','Mailer');

/**
 * beforeFilter
 *
 * # before_filter :require_admin
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->require_admin();
	}

  function index()
  {
  }

  /**
   * projects
   *
   */
  function projects()
  {
    $this->Sort->sort_init('name', 'asc');
    $this->Sort->sort_update(
      array('name', 'is_public', 'created_on')
    );

    if (isset($this->request->query['status'])) {
      $status = (int)$this->request->query['status'];
    } else {
      $status = 1;
    }

    $this->set('status', $status);

    $status_options = array(
      '' => __('all'),
      1  => __('active'),
    );

    $this->set('status_options', $status_options);

    if ($status == '1') {
      $condition = array('Project.status' => $status);
    } else {
      $condition = array();
    }

    $name = null;
    if(!empty($this->request->query['name'])) {
      $name = $this->request->query['name'];
      $q_name = "%{$name}%";
      $condition['LOWER(Project.identifier) LIKE ? OR LOWER(Project.name) LIKE ?'] = array($q_name, $q_name);
    } 

    $this->set('name', $name);

#    @project_count = Project.count(:conditions => c.conditions)
#    @project_pages = Paginator.new self, @project_count,
#               per_page_option,
#               params['page']                
#    @projects = Project.find :all, :order => sort_clause,
#                        :conditions => c.conditions,
#           :limit  =>  @project_pages.items_per_page,
#           :offset =>  @project_pages.current.offset
#

    // @todo fix limit count
    $projects = $this->Project->find('all',
      array(
        'recursive' => 0,
        'conditions' => $condition,
        'limit' => 10,
      )
    );

    $this->set('projects', $projects);
#    render :action => "projects", :layout => false if request.xhr?

   }

	public function plugins() {
		$pluginContainer = ClassRegistry::getObject('PluginContainer');
		$pluginContainer->fetchEntry();
		$this->set('plugins',$pluginContainer->getEntries());
	}

	public function installPlugin($id){
		$pluginContainer = ClassRegistry::getObject('PluginContainer');
		$pluginContainer->fetchEntry();
		if ($pluginContainer->install($id)) {
			$this->Session->setFlash(sprintf(__('Installed plugin: %s'),$id), 'default', array('class'=>'flash flash_notice'));
		}
		$this->redirect('plugins');
	}

	public function uninstallPlugin($id){
		$pluginContainer = ClassRegistry::getObject('PluginContainer');
		$pluginContainer->fetchEntry();
		if ($pluginContainer->uninstall($id)) {
			$this->Session->setFlash(sprintf(__('Uninstalled plugin: %s'),$id), 'default', array('class'=>'flash flash_notice'));
		}
		$this->redirect('plugins');
	}

	public function default_configration() {
	}

	public function test_email() {
		if ($this->Mailer->deliver_test($this->current_user)) {
			$this->Session->setFlash(sprintf(__('An email was sent to %s'),$this->current_user['mail']), 'default', array('class'=>'flash flash_notice'));
		} else {
			$this->Session->setFlash(sprintf(__('An error occurred while sending mail (%s)'),$this->current_user['mail']), 'default', array('class'=>'flash flash_error'));
		}
		$this->redirect(array(
			'controller' => 'settings',
			'action' => 'edit',
			'tab' => 'notifications'
		));
	}

/**
 * info
 *
 */
	public function info() {
		$db =& ConnectionManager::getDataSource($this->Project->useDbConfig);
		$this->set('db_driver', $db->config['datasource']);
	}

}
