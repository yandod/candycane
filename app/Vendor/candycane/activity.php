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

/**
 * Activity
 *
 * @package candycane
 */
class Activity extends Object {

/**
 * Available event types
 *
 * @var array
 */
	public $available_event_types = array();

/**
 * Default event types
 *
 * @var array
 */
	public $default_event_types = array();

/**
 * Providers
 *
 * @var array
 */
	public $providers = array();

/**
 * Returns a singleton instance of the Activity class.
 *
 * @param boolean $boot 
 * @return Activity Instance
 */
	public function &getInstance($boot = true) {
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
	public function register($event_type, $options=array()) {
		$_this =& Activity::getInstance();
		$_this->_register($event_type, $options);
	}

/**
 * Register
 *
 * @param string $event_type 
 * @param array $options Options
 * @return void
 * @access protected
 */
	function _register($event_type, $options = array()) {
		$options = array_intersect_key($options, array('class_name' => false, 'default' => false));
		$options = array_merge(array('default' => true), $options);

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
		if (!array_key_exists($event_type, $this->providers)) {
			$this->providers[$event_type] = array();
		}
		$this->providers[$event_type] = array_merge($this->providers[$event_type], $providers);
	}

/**
 * Load defaults
 *
 * @param boolean $boot 
 * @return void
 * @access private
 * @todo Wiki
 * @todo Next version
 */
	function __loadDefault($boot) {
		$this->_register('issues', array('class_name' => array('Issue', 'Journal')));
		$this->_register('news');
		$this->_register('documents', array('class_name' => array('Document', 'Attachment')));
		$this->_register('files', array('class_name' => 'Attachment'));

		// TODO : Wiki... 
		// $this->_register('wiki_edits', array('class_name' => 'WikiContentVersion', 'default' => false));
		// TODO : next version.
		// $this->_register('changesets');
		// $this->_register('messages', array('default' => false));
	}
}
