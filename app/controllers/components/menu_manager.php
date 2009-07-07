<?php
class MenuManagerComponent extends Object
{
  var $project_menu = array();
  var $application_menu = array();
  var $symbol_link = array();
  var $__selected = false;
  
  function initialize(&$controller) {
    // saving the controller reference for later use
    $this->controller =& $controller;
    $this->project_menu = $this->_getProjectMenu();
    $this->application_menu = $this->_getApplicationMenu();
  }
  
  function startup() {
  }
  
  function _detectProjectId()
  {
    $project_id = null;
  	if ( isset($this->controller->params['project_id'])) {
  	  $project_id = $this->controller->params['project_id'];
  	}
  	
  	if ( $this->controller->name == 'Versions') {
  	  $version_id = $this->controller->params['pass'][0];
  	  App::import('model','Version');
  	  $version = new Version();
  	  $bind = array(
  	    'belongsTo' => array(
  	      'Project' => array(
  	        'className' => 'Project'
  	      )
  	    )
  	  );
  	  $version->bindModel($bind);
  	  $version_row = $version->find('first',aa('condtions',aa('id',$version_id)));
  	  $project_id = $version_row['Project']['identifier'];
  	}
  	
    
    return $project_id;
  }
  

  function beforeRender($controller) {
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
  	
  	if (isset($this->controller->params['project_id'])) {
  	  $meta_data = $this->_allowed_items($this->project_menu);
  	} else {
      $meta_data = $this->application_menu;
    }
    $menu_data = array();
  	foreach ($meta_data as $val) {
  		if ( $val['controller'] == $this->controller->params['controller'] && $val['action'] == $this->controller->params['action'] && !$this->__selected ) {
  			$this->_select($val);
  		}
      if (array_key_exists('params', $val)) {
        $params = $val['params'];
        if (!is_array($params)) {
          $params = array($params);
        }
        foreach ($params as $param) {
          if (array_key_exists($param, $this->controller->params)) {
            $val[$param] = $this->controller->params[$param];
          }
        }
        unset($val['params']);
      }
  		$menu_data[] = $val;
  	}
    $this->menu_items = $menu_data;
  }
  
  function _getProjectMenu()
  {
  	return array(
      'overview' => aa('controller','projects','action','show','class','','caption','Overview','params','project_id'),
      'activity' => aa('controller','projects','action','activity','class','','caption','Activity','params','project_id'),
      'roadmap'  => aa('controller','projects','action','roadmap','class','','caption','Roadmap','params','project_id'),
      'issues'   => aa('controller','issues','action','index','class','','caption','Issues','params','project_id'),
      'new_issue'=> aa('controller','issues','action','add','class','','caption','New issue','params','project_id'),
      'news'     => aa('controller','news','action','index','class','','caption','News','params','project_id'),
      'wiki'     => aa('controller','wiki','action','index','class','','caption','Wiki','params','project_id'),
      'settings' => aa('controller','projects','action','settings','class','','caption','Preferences','params','project_id'),  		
  	);
  }
  function _getApplicationMenu()
  {
    return array();
  }
  
  function _select(&$item) {
    $item['class'] .= " selected";
    $this->__selected = true;
  }
  
  function _allowed_items($menu_items) {
    $allows = array();
    $User = & ClassRegistry::init('User');
    foreach ($menu_items as $key => $menu_item) {
      if(!empty($this->controller->current_user) && $User->is_allowed_to($this->controller->current_user, $this->__url($this->__to_allowed_action($menu_item)), $this->controller->_project)) {
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