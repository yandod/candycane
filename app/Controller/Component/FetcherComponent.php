<?php
# Redmine - project management software
# Copyright (C) 2006-2008  Jean-Philippe Lang
#
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

# Class used to retrieve activity events
class FetcherComponent extends Component
{
  var $user;
  var $project;
  var $scope;
  var $_options;
  
  # Needs to be unloaded in development mode
  var $__constantized_providers = array();

  function initialize(&$controller) {
    $this->controller = $controller;
    App::import('file', 'Activity', array('file' => APP . 'Activity.php'));
    foreach(Activity::getInstance()->providers as $k=>$t) {
      $this->__constantized_providers[$k] = array();
      foreach($t as $model) {
        $this->__constantized_providers[$k][] = & ClassRegistry::init($model);
      }
    }
  }
  
  /**
   * When starting using this component, first please be sure to call
   */
  function fetch($user, $options=array()) {
    $options = array_intersect_key($options, array('project'=>false, 'with_subprojects'=>false, 'author'=>false));
    $options = array_merge(array('project'=>array()), $options);

    $this->user = $user;
    $this->project = $options['project'];
    if(!empty($this->project)) {
      $options['project'] = $this->project['Project'];
    }
    $this->_options = $options;

    $this->scope = $this->event_types();
  }
  
  function beforeRender($controller) {
  }

  /**
   * Returns an array of available event types
   */
  function event_types() {
    if (!empty($this->_event_types)) {
      return $this->_event_types;
    }

    $this->_event_types = Activity::getInstance()->available_event_types;
    if (!empty($this->project)) {
      $User = & ClassRegistry::init('User');
      foreach($this->_event_types as $k=>$o) {
        if (!$User->is_allowed_to($this->user, "view_{$o}", $this->project)) {
          unset($this->_event_types[$k]);
        }
      }
    }
    return $this->_event_types;
  }

  /**
   * Yields to filter the activity scope
   *
   * Controller
   *   $this->Fetcher->scope_select('_callback_scope_select');
   *
   *   function _callback_scope_select($scope) {
   *     return !empty($this->_get_params["show_{$scope}"]);
   *   }
   *
   * @param $callback : The user functional name which decides the scope which it selects is appointed.
   */
  function scope_select($callback) {
    $result = array();
    if (method_exists($this->controller, $callback)) {
      foreach ($this->scope as $scope) {
        if ($this->controller->$callback($scope)) {
          $result[] = $scope;
        }
      }
    }
    $this->scope = $result;
    return $this->scope;
  }

  /**
   * Sets the scope
   * Argument can be :all, :default or an array of event types
   */
  function set_scope($s) {
    switch ($s) {
    case 'all' :
      $this->scope = $this->_event_types;
      break;
    case 'default' :
      $this->scope = $this->default_scope();
      break;
    default :
      if (!is_array($s)) {
        $s = array($s);
      }
      $this->scope = array_intersect($s, $event_types);
      break;
    }
  }

  /**
   * Resets the scope to the default scope
   */
  function default_scope() {
    $this->scope = Activity::getInstance()->default_event_types;
    return $this->scope;
  }

  /**
   * Returns an array of events for the given date range
   * Sorting order is date DESCDING
   */
  function events($from = null, $to = null, $options=array()) {
    $e = array();
    $options = array_merge(array('limit'=>0), $options);
    $this->_options['limit'] = $options['limit'];
    foreach ($this->scope as $event_type) {
      foreach ($this->_constantized_providers($event_type) as $provider) {
        $results = $provider->find_events($event_type, $this->user, $from, $to, $this->_options);
        foreach($results as $day=>$times) {
          foreach($times as $time=>$events) {
            foreach ($events as $result) {
              $e[] = $provider->create_event_data($result);
            }
          }
        }
      }
    }
    usort($e, array($this, 'cmp_event_datetime'));    
    if ($options['limit']) {
      $e = array_slice($e, 0, $options['limit']);
    }
    return $e;
  }

  function cmp_event_datetime($l, $r) {
    $a = strtotime($l['datetime']);
    $b = strtotime($r['datetime']);
    if ($a == $b) {
      return 0;
    }
    return ($a > $b) ? -1 : 1;
  }
  
  # private

  function _constantized_providers($event_type) {
    return $this->__constantized_providers[$event_type];
  }
}