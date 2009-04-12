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
  	  $meta_data = $this->_getProjectMenu($this->controller->params['project_id']);
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
  
  function _getProjectMenu($project_id)
  {
  	return array(
  		aa('controller','projects','action','show','class','','caption',__('Overview',true),'project_id',$project_id),
  		aa('controller','projects','action','activity','class','','caption',__('Activity',true),'project_id',$project_id),
  		aa('controller','projects','action','roadmap','class','','caption',__('Roadmap',true),'project_id',$project_id),
  		aa('controller','issues','action','index','class','','caption',__('Issues',true),'project_id',$project_id),
  		aa('controller','issues','action','add','class','','caption',__('New issue',true),'project_id',$project_id),
  		aa('controller','news','action','index','class','','caption',__('News',true),'project_id',$project_id),
  		aa('controller','wiki','action','show','class','','caption',__('Wiki',true),'project_id',$project_id),
  		aa('controller','projects','action','settings','class','','caption',__('Preferences',true),'project_id',$project_id),  		
  	);
  }
}