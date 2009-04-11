<?php
class MenuManagerComponent extends Object
{
  var $project_menu = array();
  
  function initialize(&$controller) {
    // saving the controller reference for later use
    $this->controller =& $controller;
    $this->_prepareMainmenu();
  }
	
  function _prepareMainmenu()
  {
  	//pr($this->controller->params);
  	$meta_data = array();
  	
  	if ( isset($this->controller->params['project_id'])) {
  	  $meta_data = $this->_getProjectMenu();
  	}
  	$menu_data = array();
  	
  	foreach ($meta_data as $val) {
  		if ( $val['controller'] == $this->controller->params['controller'] && $val['action'] == $this->controller->params['action']) {
  			$val['class'] .= " selected";
  		}
  		$menu_data[] = $val;
  	}
  	$this->controller->set('main_menu',$menu_data);
  }
  
  function _getProjectMenu()
  {
  	return array(
  		aa('controller','projects','action','show','class','','caption',__('Overview',true)),
  		aa('controller','projects','action','activity','class','','caption',__('Activity',true)),
  		aa('controller','projects','action','issues','class','','caption',__('Issues',true)),
  		aa('controller','projects','action','issues/new','class','','caption',__('New issue',true)),
  		aa('controller','wiki','action','show','class','','caption',__('Wiki',true)),
  		aa('controller','projects','action','settings','class','','caption',__('Preferences',true)),  		
  	);
  }
}