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
class Activity extends Object {
  var $available_event_types = array();
  var $default_event_types = array();
  var $providers = array();

/**
 * Returns a singleton instance of the Activity class.
 *
 * @return Activity instance
 * @access public
 */
  function &getInstance($boot = true) {
    static $instance = array();
    if (!$instance) {
      $instance[0] =& new Activity();
      $instance[0]->__loadDefault($boot);
    }
    return $instance[0];
  }

  /**
   * Registers an activity provider
   * 
   * Usage. Activity::register('issues', array('class_name' => array('Issue', 'Journal')));
   *
   * @param event_type : like a table name. ex.issues
   * @param options : Relation of table name between class name has not followed to designation rule when and, 
   *                  when we would like to register the plural tables, class_name is appointed. 
   */
  function register($event_type, $options=array()) {
    $_this =& Activity::getInstance();
    $_this->_register($event_type, $options);
  }

  function _register($event_type, $options=array()) {
    $options = array_intersect_key($options, array('class_name'=>false, 'default'=>false));
    $options = array_merge(array('default'=>false), $options);

    $providers = empty($options['class_name']) ? Inflector::classify($event_type) : $options['class_name'];
    if (!is_array($providers)) {
      $providers = array($providers);
    }

    if (!in_array($event_type, $this->available_event_types)) {
      $this->available_event_types[] = $event_type;
    }
    if ($options['default'] != false) {
      $this->default_event_types[] = $event_type;
    }
    $this->providers[$event_type] = array_merge($this->providers[$event_type], $providers);
  }
  
  function __loadDefault($boot) {
    $this->_register('issues', array('class_name' => array('Issue', 'Journal')));
    $this->_register('changesets');
    $this->_register('news');
    $this->_register('documents', array('class_name' => array('Document', 'Attachment')));
    $this->_register('files', array('class_name' => 'Attachment'));
    $this->_register('wiki_edits', array('class_name' => 'WikiContentVersion', 'default' => false));
    $this->_register('messages', array('default' => false));
  }
}
