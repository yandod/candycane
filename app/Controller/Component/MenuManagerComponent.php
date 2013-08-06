<?php
class MenuManagerComponent extends Component
{
  var $project_menu = array();
  var $application_menu = array();
  var $symbol_link = array();
  var $__selected = false;
  
  public function initialize(Controller $controller) {
    // saving the controller reference for later use
    $this->controller = $controller;
    $this->project_menu = $this->_getProjectMenu();
    $this->application_menu = $this->_getApplicationMenu();
  }
  
  public function startup(Controller $controller) {
  }
  
  function _detectProjectId()
  {
    $project_id = null;
  	if ( isset($this->controller->params['project_id'])) {
  	  $project_id = $this->controller->params['project_id'];
  	}
  	
  	if ( $this->controller->name == 'Versions') {
  	  $version_id = $this->controller->params['pass'][0];
          App::uses('Version', 'Model');
  	  $version = new Version();
  	  $bind = array(
  	    'belongsTo' => array(
  	      'Project' => array(
  	        'className' => 'Project'
  	      )
  	    )
  	  );
  	  $version->bindModel($bind);
  	  $version_row = $version->find('first',array(
		  'condtions' => array(
			  'id' => $version_id
			 )
		  )
	  );
  	  $project_id = $version_row['Project']['identifier'];
  	}
  	
    
    return $project_id;
  }
  

  public function beforeRender(Controller $controller) {
    $this->_prepareSelect();
    $this->_prepareMainmenu();
    $controller->set('main_menu', $this->menu_items);
  }

  function menu_item($id, $options = array()) {
    // TODO : now support only project menu
    $actions = $this->controller->params['action'];
    if (array_key_exists('only', $options)) {
      $actions = $options['only'];
    }
    if (!is_array($actions)) {
      $actions = array($actions);
    }
    foreach ($actions as $action) {
      $this->symbol_link[$this->controller->params['controller']][$action] = $id;
    }
  }

  function _prepareSelect() {
    // TODO : now support only project menu
    if (isset($this->symbol_link[$this->controller->params['controller']][$this->controller->params['action']])) {
      $symbol = $this->symbol_link[$this->controller->params['controller']][$this->controller->params['action']];
      $this->_select($this->project_menu[$symbol]);
    }
  }

  function _prepareMainmenu()
  {
  	$meta_data = array();
  	$project_id = $this->_detectProjectId();
  	if ( $project_id ) {
  	  $meta_data = $this->_getProjectMenu($project_id);
  	}
  	
  	if (isset($this->controller->request->params['project_id'])) {
  	  $meta_data = $this->_allowed_items($this->project_menu);
  	} else {
      $meta_data = $this->application_menu;
    }
    $menu_data = array();
  	foreach ($meta_data as $val) {
  		if ( $val['controller'] == $this->controller->request->params['controller'] && $val['action'] == $this->controller->request->params['action'] && !$this->__selected ) {
  			$this->_select($val);
  		}
      if (array_key_exists('params', $val)) {
        $params = $val['params'];
        if (!is_array($params)) {
          $params = array($params);
        }
        foreach ($params as $param) {
          if (array_key_exists($param, $this->controller->request->params)) {
            $val[$param] = $this->controller->request->params[$param];
          }
        }
        unset($val['params']);
      }
  		$menu_data[] = $val;
  	}
    $this->menu_items = $menu_data;
  }
	function _getProjectMenu(){
		$menuContainer = ClassRegistry::init('MenuContainer');
		return $menuContainer->getProjectMenu();
  }
  function _getApplicationMenu()
  {
    return array();
  }
  
  function _select(&$item) {
    $item['class'] .= " selected";
    $this->__selected = true;
  }
  
	protected function _allowed_items($menu_items) {
		$allows = array();
		$User = & ClassRegistry::init('User');
		foreach ($menu_items as $key => $menu_item) {

			$allow = false;
			if (!empty($this->controller->current_user) && $User->is_allowed_to($this->controller->current_user, $this->__url($this->__to_allowed_action($menu_item)), $this->controller->_project)) {
				$allow = true;
			}
			if (isset($menu_item['_allowed']) && $menu_item['_allowed']) {
				unset($menu_item['_allowed']);
				$allow = true;
			}

			if($allow) {
				$allows[$key] = $menu_item;
			}
		}
		// for wiki existing check
		if ( is_null($this->controller->_project['Wiki']['start_page']) ) {
			unset($allows['wiki']);
		}
		return $allows;
	}

  function __url($menu_item) {
    return array_intersect_key($menu_item, array('controller'=>true,'action'=>true));
  }
  function __to_allowed_action($menu_item) {
    if ($menu_item['action'] == 'add') {
      $menu_item['action'] = 'new';
    }
    return $menu_item;
  }
}