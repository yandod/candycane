<?php
/* SVN FILE: $Id: routes.php 7945 2008-12-19 02:16:01Z gwoo $ */
/**
 * Short description for file.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.2.9
 * @version       $Revision: 7945 $
 * @modifiedby    $LastChangedBy: gwoo $
 * @lastmodified  $Date: 2008-12-18 18:16:01 -0800 (Thu, 18 Dec 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'welcome', 'action' => 'index'));

	Router::connect('/login', array('controller' => 'account', 'action' => 'login'));
	Router::connect('/logout', array('controller' => 'account', 'action' => 'logout'));
	Router::connect('/projects/:project_id/issues/:action/*', array('controller' => 'issues'));
	Router::connect('/projects/:action/:project_id', array('controller' => 'projects'));
	Router::connect('/projects/:project_id/news/:action/', array('controller' => 'news'));
	Router::connect('/projects/:project_id/documents/:action/', array('controller' => 'documents'));
	Router::connect('/projects/:project_id/boards/:action/:id/', array('controller' => 'boards'));
	Router::connect('/projects/:project_id/timelog/:action/:id/', array('controller' => 'timelog'), array('project_id' => '.+'));
	
	Router::connect('/users/list', array('controller' => 'users', 'action' => 'list_'));

#  map.connect 'projects/:project_id/issues/:action', :controller => 'issues'
#  map.connect 'projects/:project_id/news/:action', :controller => 'news'
#  map.connect 'projects/:project_id/documents/:action', :controller => 'documents'
#  map.connect 'projects/:project_id/boards/:action/:id', :controller => 'boards'
#  map.connect 'projects/:project_id/timelog/:action/:id', :controller => 'timelog', :project_id => /.+/
#
	
#ActionController::Routing::Routes.draw do |map|
#  # Add your own custom routes here.
#  # The priority is based upon order of creation: first created -> highest priority.
#  
#  # Here's a sample route:
#  # map.connect 'products/:id', :controller => 'catalog', :action => 'view'
#  # Keep in mind you can assign values other than :controller and :action
#
#  # Allow Redmine plugins to map routes and potentially override them
#  Rails.plugins.each do |plugin|
#    map.from_plugin plugin.name.to_sym
#  end
#
#  map.home '', :controller => 'welcome'
#  map.signin 'login', :controller => 'account', :action => 'login'
#  map.signout 'logout', :controller => 'account', :action => 'logout'
#  

Router::connect('/wiki/:project_id', array('controller' => 'wiki', 'action' => 'index'));
Router::connect('/wiki/:project_id/:wikipage', array('controller' => 'wiki', 'action' => 'index'));
Router::connect('/wiki/:project_id/:wikipage/:action/*', array('controller' => 'wiki', 'action' => 'index', 'wikipage' => null));

#  map.connect 'wiki/:id/:page/:action', :controller => 'wiki', :page => nil
#  map.connect 'roles/workflow/:id/:role_id/:tracker_id', :controller => 'roles', :action => 'workflow'
#  map.connect 'help/:ctrl/:page', :controller => 'help'
#  #map.connect ':controller/:action/:id/:sort_key/:sort_order'
#  
Router::connect('issues/:action/:issue_id/*', array('controller' => 'issues'));

#  map.connect 'issues/:issue_id/relations/:action/:id', :controller => 'issue_relations'
#  map.connect 'projects/:project_id/issues/:action', :controller => 'issues'
Router::connect('projects/:project_id/issues/:action', array('controller' => 'issues'));

#  map.connect 'projects/:project_id/news/:action', :controller => 'news'
#  map.connect 'projects/:project_id/documents/:action', :controller => 'documents'
#  map.connect 'projects/:project_id/boards/:action/:id', :controller => 'boards'
#  map.connect 'projects/:project_id/timelog/:action/:id', :controller => 'timelog', :project_id => /.+/
#  map.connect 'boards/:board_id/topics/:action/:id', :controller => 'messages'
#
#  map.with_options :controller => 'repositories' do |omap|
#    omap.repositories_show 'repositories/browse/:id/*path', :action => 'browse'
#    omap.repositories_changes 'repositories/changes/:id/*path', :action => 'changes'
#    omap.repositories_diff 'repositories/diff/:id/*path', :action => 'diff'
#    omap.repositories_entry 'repositories/entry/:id/*path', :action => 'entry'
#    omap.repositories_entry 'repositories/annotate/:id/*path', :action => 'annotate'
#    omap.repositories_revision 'repositories/revision/:id/:rev', :action => 'revision'
#  end
#  
#  map.connect 'attachments/:id', :controller => 'attachments', :action => 'show', :id => /\d+/
#  map.connect 'attachments/:id/:filename', :controller => 'attachments', :action => 'show', :id => /\d+/, :filename => /.*/
#  map.connect 'attachments/download/:id/:filename', :controller => 'attachments', :action => 'download', :id => /\d+/, :filename => /.*/
#   
#  # Allow downloading Web Service WSDL as a file with an extension
#  # instead of a file named 'wsdl'
#  map.connect ':controller/service.wsdl', :action => 'wsdl'
#
# 
#  # Install the default route as the lowest priority.
#  map.connect ':controller/:action/:id'
#end
/**
 * Then we connect url '/test' to our test controller. This is helpful in
 * developement.
 */
	Router::connect('/tests', array('controller' => 'tests', 'action' => 'index'));

?>
