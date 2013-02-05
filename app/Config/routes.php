<?php
/**
 * Regular expressions used for Route elements
 */
    App::import('Controller', 'Projects');
    $methods = get_class_methods('ProjectsController');
    $projects_actions = implode('|', $methods);
    $project_id = '(?!('.$projects_actions.'))[-a-z0-9_]+';

/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.ctp)...
 */
    // for quick install
	if (!file_exists(APP.'Config'.DS.'database.php')) {
		Router::connect('/', array('controller' => 'cc_install', 'action' => 'index', 'plugin' => 'cc_install'));
	} else {
		Router::connect('/', array('controller' => 'welcome', 'action' => 'index'));
    }
	Router::connect('/login', array('controller' => 'account', 'action' => 'login'));
	Router::connect('/logout', array('controller' => 'account', 'action' => 'logout'));

    Router::connect('/projects', array('controller' => 'projects'));
    Router::connect('/projects/:action', array('controller' => 'projects'), array('action' => $projects_actions));
    Router::connect('/projects/:project_id', array('controller' => 'projects', 'action' => 'show'));//, array('project_id' => $project_id));
    Router::connect('/projects/:project_id/boards/:action/:id/', array('controller' => 'boards'), array('project_id' => $project_id));
    Router::connect('/projects/:project_id/documents/:action/', array('controller' => 'documents'), array('project_id' => $project_id));
    Router::connect('/projects/:project_id/issues/:action/*', array('controller' => 'issues'), array('project_id' => $project_id));
    Router::connect('/projects/:project_id/news/:action', array('controller' => 'news'), array('project_id' => $project_id));
    Router::connect('/projects/:project_id/news/:action/:id', array('controller' => 'news'), array('project_id' => $project_id));
    Router::connect('/projects/:project_id/reports/:action', array('controller' => 'reports'), array('project_id' => $project_id));
    Router::connect('/projects/:project_id/timelog/:action/*', array('controller' => 'timelog'), array('project_id' => $project_id));
    Router::connect('/projects/:project_id/timelog/:action/:page/:sort/:direction/*', array('controller' => 'timelog'), array('project_id' => $project_id));
    Router::connect('/projects/:project_id/wiki', array('controller' => 'wiki', 'action' => 'index'), array('project_id' => $project_id));
    Router::connect('/projects/:project_id/wiki/:wikipage', array('controller' => 'wiki', 'action' => 'index'), array('project_id' => $project_id));
    Router::connect('/projects/:project_id/wiki/:wikipage/:action/*', array('controller' => 'wiki', 'action' => 'index', 'wikipage' => null), array('project_id' => $project_id));
    Router::connect('/projects/:project_id/:action', array('controller' => 'projects'), array('project_id' => $project_id, 'action' => $projects_actions));
    Router::connect('/projects/:project_id/:action/*', array('controller' => 'projects'), array('project_id' => $project_id, 'action' => $projects_actions));

	Router::connect('/reports/:action/:project_id', array('controller' => 'reports'));

    Router::connect('/issue_categories/:action/:id/:project_id', array('controller' => 'issue_categories'));
		
	
	Router::connect('/users/list', array('controller' => 'users', 'action' => 'list_'));
    Router::connect('/members/:action/:project_id', array('controller' => 'members'));
    Router::connect('/members/:action/:project_id/:id', array('controller' => 'members'));
		
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

Router::connect('/wikis/:action/:project_id', array('controller' => 'wikis'));

#  map.connect 'wiki/:id/:page/:action', :controller => 'wiki', :page => nil
#  map.connect 'roles/workflow/:id/:role_id/:tracker_id', :controller => 'roles', :action => 'workflow'
#  map.connect 'help/:ctrl/:page', :controller => 'help'
#  #map.connect ':controller/:action/:id/:sort_key/:sort_order'
#  
Router::connect('/issues/:issue_id/*', array('controller' => 'issues', 'action' => 'show'), array('issue_id' => '[0-9]+'));
Router::connect('/issues/:action', array('controller' => 'issues'));
Router::connect('/issues/:action/:issue_id/*', array('controller' => 'issues'));

#  map.connect 'issues/:issue_id/relations/:action/:id', :controller => 'issue_relations'
#  map.connect 'projects/:project_id/issues/:action', :controller => 'issues'
Router::connect('/projects/:project_id/issues/:action', array('controller' => 'issues'));

#  map.connect 'projects/:project_id/news/:action', :controller => 'news'
#  map.connect 'projects/:project_id/documents/:action', :controller => 'documents'
#  map.connect 'projects/:project_id/boards/:action/:id', :controller => 'boards'
#  map.connect 'projects/:project_id/timelog/:action/:id', :controller => 'timelog', :project_id => /.+/
Router::connect('/timelog/:action/:id/*', array('controller' => 'timelog'));

#  map.connect 'boards/:board_id/topics/:action/:id', :controller => 'messages'

Router::connect('/news/:action/:id/*', array('controller' => 'news'));

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
Router::connect('/attachments/:id', array('controller' => 'attachments', 'action' => 'show'), array('id' => '\\d+'));
Router::connect('/attachments/:id/:filename', array('controller' => 'attachments', 'action' => 'show'), array('id' => '\\d+', 'filename' => '.*'));
Router::connect('/attachments/download/:id/:filename', array('controller' => 'attachments', 'action' => 'download'), array('id' => '\\d+', 'filename' => '.*'));
Router::connect('/attachments/destroy/:id', array('controller' => 'attachments', 'action' => 'destroy'), array('id' => '\\d+'));

#   
#  # Allow downloading Web Service WSDL as a file with an extension
#  # instead of a file named 'wsdl'
#  map.connect ':controller/service.wsdl', :action => 'wsdl'
#
# 
#  # Install the default route as the lowest priority.
#  map.connect ':controller/:action/:id'
#end
Router::connect('/search/:action/:project_id',array('controller' => 'search'));
Router::connect('/search/:action',array('controller' => 'search'));
Router::connect('/queries/:action/:query_id',array('controller' => 'queries'));
/**
 * Then we connect url '/test' to our test controller. This is helpful in
 * developement.
 */
	Router::connect('/tests', array('controller' => 'tests', 'action' => 'index'));

  Router::parseExtensions('xml', 'json');

/**
 * Load all plugin routes.  See the CakePlugin documentation on 
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
	
