<?php
# redMine - project management software
# Copyright (C) 2006-2007  Jean-Philippe Lang
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

#
#  Examples :
#   $actsAs = array('Event' => array('title' => array('Proc' => '_event_title'),
#                                    'url' => array('Proc' => '_event_url')
#                                   ));
#
#  function _event_title($data) {
#     return $data['Tracker']['name'].' ##'.$data['Issue']['id'].': '.$data['Issue']['subject'];
#  }
#  function _event_url($data) {
#    return  array('controller'=>'issues', 'action'=>'show', 'id'=>$data['Issue']['id']);
#  }

class EventBehavior extends ModelBehavior {
  var $default_options = array(
    'datetime'    => 'created_on',
    'title'       => 'title',
    'description' => 'description',
    'author'      => '',
    'url'         => array('controller' => 'welcome'),
    'type'        => ''
  );
  
  function setup(&$Model, $config = array()) {
    $this->default_options['type'] = $this->_dasherize(Inflector::underscore($Model->name));
    if(isset($Model->Author)) {
      $this->default_options['author'] = $Model->Author;
    }
    $settings = array_merge($this->default_options, $config);
    $this->settings[$Model->alias] = $settings;
  }
  function _dasherize($text) {
    return str_replace('_', '-', $text);
  }

  function create_event_data(&$Model, $data=false) {
    if(empty($data)) $data = $Model->data;
    $event_data = array(
      'date'         => $this->event_date($Model, $data),
      'datetime'     => $this->event_datetime($Model, $data),
      'title'        => $this->event_title($Model, $data),
      'description'  => $this->event_description($Model, $data),
      'author'       => $this->event_author($Model, $data),
      'url'          => $this->event_url($Model, $data),
      'type'         => $this->event_type($Model, $data),
      'project'      => $data['Project'],
      'id'           => $data[$Model->alias]['id']
    );
    return $event_data;
  }

  function event_date(&$Model, $data=false) {
    if(empty($data)) $data = $Model->data;
    return date('Y-m-d', strtotime($this->event_datetime($Model, $data)));
  }
  function event_url(&$Model, $data=false, $options = array()) {
    if(empty($data)) $data = $Model->data;
    $option = $this->settings[$Model->alias]['url'];
    $result = (is_array($option) && array_key_exists('Proc', $option)) ? @$Model->$option['Proc']($data) : $option;
    return array_merge($result, $options);
  }
  function event_datetime(&$Model, $data=false) {
    if(empty($data)) $data = $Model->data;
    return $this->_event($Model, 'datetime', $data);
  }
  function event_title(&$Model, $data=false) {
    if(empty($data)) $data = $Model->data;
    return $this->_event($Model, 'title', $data);
  }
  function event_description(&$Model, $data=false) {
    if(empty($data)) $data = $Model->data;
    return $this->_event($Model, 'description', $data);
  }
  function event_author(&$Model, $data=false) {
    if(empty($data)) $data = $Model->data;
    return $this->_event($Model, 'author', $data);
  }
  function event_type(&$Model, $data=false) {
    if(empty($data)) $data = $Model->data;
    return $this->_event($Model, 'type', $data);
  }
  function _event(&$Model, $attr, $data) {
    $result = false;
    $option = $this->settings[$Model->alias][$attr];
    if(is_array($option) && array_key_exists('Proc', $option)) {
      $result = @$Model->$option['Proc']($data);
    } elseif(is_string($option) && array_key_exists($option, $data[$Model->alias])) {
      $result = $data[$Model->alias][$option];
    } elseif(is_object($option)) {
      $result = $data[$option->alias];
    } else {
      $result = $option;
    }
    return $result;
  }
  function cmp_event_datetime(&$Model, $l, $r) {
    return strtotime($Model->event_datetime($l)) - strtotime($Model->event_datetime($r));
  }
}