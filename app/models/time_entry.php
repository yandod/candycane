<?php
## redMine - project management software
## Copyright (C) 2006-2008  Jean-Philippe Lang
##
## This program is free software; you can redistribute it and/or
## modify it under the terms of the GNU General Public License
## as published by the Free Software Foundation; either version 2
## of the License, or (at your option) any later version.
## 
## This program is distributed in the hope that it will be useful,
## but WITHOUT ANY WARRANTY; without even the implied warranty of
## MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
## GNU General Public License for more details.
## 
## You should have received a copy of the GNU General Public License
## along with this program; if not, write to the Free Software
## Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
#
#class TimeEntry < ActiveRecord::Base
#  # could have used polymorphic association
#  # project association here allows easy loading of time entries at project level with one database trip
#  
#  attr_protected :project_id, :user_id, :tyear, :tmonth, :tweek
#
#  def self.visible_by(usr)
#    with_scope(:find => { :conditions => Project.allowed_to_condition(usr, :view_time_entries) }) do
#      yield
#    end
#  end
#end

// 他に作っている人がいたら消していいです
class TimeEntry extends AppModel
{
  var $name = 'TimeEntry';
  var $belongsTo = array(
    'Project',
    'Activity'=>array('className' => 'Enumeration', 'foreignKey' => 'activity_id'),
    'User',
  );
  var $actsAs = array(
    'Customizable'=>array('is_for_all'=>0),
#  acts_as_event :title => Proc.new {|o| "#{o.user}: #{lwr(:label_f_hour, o.hours)} (#{(o.issue || o.project).event_title})"},
#                :url => Proc.new {|o| {:controller => 'timelog', :action => 'details', :project_id => o.project}},
#                :author => :user,
#                :description => :comments
    'Event' => array('title' => array('Proc' => '_event_title'),
                      'url' => array('Proc' => '_event_url'),
                      'author' => array('Proc' => '_event_author'),
                      'description' => 'comments',
                ),
  );
  function _event_title($data) {
    if(!empty($data['Issue'])) {
      $this->bindModel(array('belongsTo'=>array('Issue')));
      $event_title = $this->Issue->event_title($data);
    } else {
      $event_title = $this->Project->event_title($data);
    }
    return $this->User->name($data).': '.sprintf(__('%.2f hour',true), $data['TimeEntry']['hours'])." ($event_title)";
  }
  function _event_url($data) {
    return  array('controller'=>'timelog', 'action'=>'details', 'project_id'=>$data['Project']['identifier']);
  }
  function _event_author($data) {
    return  $data['User'];
  }

  var $validate = array(
    'user_id' => array(
      'validates_presence_of'=>array('rule'=>array('notEmpty')),
    ),
    'activity_id' => array(
      'validates_presence_of'=>array('rule'=>array('notEmpty')),
    ),
    'project_id' => array(
      'validates_presence_of'=>array('rule'=>array('notEmpty')),
    ),
    'issue_id' => array(
      'validates_presence_of'=>array('rule'=>array('notEmpty')),
    ),
    'hours' => array(
      'validates_presence_of'=>array('rule'=>array('notEmpty')),
      'validates_numericality_of'=>array('rule'=>array('numeric')),
      'validates_inclusion_of'=>array('rule'=>array('range', -1, 1000)),
    ),
    'spent_on' => array(
      'validates_presence_of'=>array('rule'=>array('notEmpty')),
    ),
    'comments' => array(
      'validates_length_of'=>array('rule'=>array('maxLength', 255)),
    )
  );

  function create($data = array(), $filterKey = false) {
    parent::create($data, $filterKey);
    if(empty($this->data[$this->name]['activity_id'])) {
      $default_activity = $this->Activity->default_value('ACTI');
      if(!empty($default_activity)) {
        $this->set('activity_id', $default_activity['Activity']['id']);
      }
    }
    return $this->data;
  }
  
  function beforeValidate() {
    if(!empty($this->data['Issue']) && empty($this->data[$this->name]['project_id'])) {
      $this->set('project_id', $issue['Issue']['project_id']);
    }
  }

  function find_visible_by($user, $project)
  {
    return $this->find('all', array('conditions' => $this->Project->allowed_to_condition($user, 'view_time_entries', array('project'=>$project['Project']))));
  }
  function sum($field, $conditions) {
    $fields = array("SUM($field) as sum_$field");
    $results = $this->find('first', array('conditions'=>$conditions, 'fields'=>$fields));
    if(!$results) {
      return 0;
    }
    $sum = $results[0]["sum_$field"];
    return $sum;
  }
  function hours() {
    if(!empty($this->data[$this->name]['hours'])) {
      $this->data[$this->name]['hours'] = $this->to_hours($this->data[$this->name]['hours']);
    }
  }
  
  # tyear, tmonth, tweek assigned where setting spent_on attributes
  # these attributes make time aggregations easier
  function spent_on() {
    if(array_key_exists('spent_on', $this->data[$this->name])) {
      $this->data[$this->name]['tyear'] = date('Y', strtotime($this->data[$this->name]['spent_on']));
      $this->data[$this->name]['tmonth'] = date('m', strtotime($this->data[$this->name]['spent_on']));
      $this->data[$this->name]['tweek'] = date('W', strtotime($this->data[$this->name]['spent_on']));
    }
  }
  function validates() {
    // convert database format.
    $this->hours();
    $this->spent_on();

    return parent::validates();
  }
  # Returns true if the time entry can be edited by usr, otherwise false
  function is_editable_by($current_user, $project) {
    $User = & ClassRegistry::init('User');
    $user_id = !empty($this->data['User']['id']) ? $this->data['User']['id'] : '';
    return (($user_id == $current_user['id']) && $User->is_allowed_to($current_user, 'edit_own_time_entries', $project)) || $User->is_allowed_to($current_user, 'edit_time_entries', $project);
  }
  
  /**
   *
   * Controller->details_condition($this->current_user, $this->_project, $this->Issue->data, $this->data['TimeEntry'])
   */
  function details_condition($Setting, $current_user, $project, $issue, $data) {
    $cond = array();
    if(empty($project)) {
      $cond[] = $this->Project->allowed_to_condition($current_user, 'view_time_entries');
    } elseif(empty($issue)) {
      $this->Project->id = $project['Project']['id'];
      $cond[] = $this->Project->project_condition($Setting->display_subprojects_issues);
    } else {
      $cond[] = array("TimeEntry.issue_id" => $issue['Issue']['id']);
    }
    $range = $this->retrieve_date_range($data['period_type'], $data['period'], 
      $current_user, $project, array('from'=>$data['from'], 'to'=>$data['to']));
    $cond[] = array('spent_on BETWEEN ? AND ?'=> array($range['from'], $range['to']));
    return compact('cond', 'range');
  }
  # Retrieves the date range based on predefined ranges or specific from/to param dates
  function retrieve_date_range($period_type, $period, $current_user, $project, $options=array(), $time=false) {
    $free_period = false;
    $from = null;
    $to = null;
    if(!$time) {
      $time = mktime();
    }
    if($period_type == '1' || ($period_type == null && $period != null)) {
      switch($period) {
      case 'today' :
        $from = $to = $time;
        break;
      case 'yesterday' :
        $from = $to = strtotime('-1 day', $time);
        break;
      case 'current_week' :
        $w = date('w', $time);
        if($w == 1) {
          $from = $time;
        } else {
          $from = strtotime("last Monday", $time);
        }
        if($w == 0) {
          $to = $time;
        } else {
          $to = strtotime("next Sunday", $time);
        }
        break;
      case 'last_week' :
        $time = strtotime("-1 week", $time);
        $w = date('w', $time);
        if($w == 1) {
          $from = $time;
        } else {
          $from = strtotime("last Monday", $time);
        }
        if($w == 0) {
          $to = $time;
        } else {
          $to = strtotime("next Sunday", $time);
        }
        break;
      case '7_days' :
        $from = strtotime('-7 day', $time);
        $to = $time;
        break;
      case 'current_month' :
        $from = strtotime(date('Y-m-1', $time));
        $day = date('t', $time) -1;
        $to = strtotime("+$day day", $from);
        break;
      case 'last_month' :
        $from = strtotime('last month', strtotime(date('Y-m-1', $time)));
        $day = date('t', $from) -1;
        $to = strtotime("+$day day", $from);
        break;
      case '30_days' :
        $from = strtotime('-30 day', $time);
        $to = $time;
        break;
      case 'current_year' :
        $from = strtotime(date('Y-1-1', $time));
        $to = strtotime(date('Y-12-31', $time));
        break;
      }
    } elseif($period_type == '2' || ($period_type == null && (!empty($options['from']) || !empty($options['to'])))) {
      if(!empty($options['from'])) $from = strtotime($options['from']);
      if(!empty($options['to']))   $to   = strtotime($options['to']);
      $free_period = true;
    } else {
      # default
    }
    if($from && $to && $from > $to) {
      $from = $to;
      $to = $from;
    }
    if(empty($from)) {
      $minimum = $this->find('first', array(
        'fields'=>array('spent_on'), 
        'conditions'=>$this->Project->allowed_to_condition($current_user, 'view_time_entries', array('project'=>$project['Project'])),
        'order' => 'spent_on ASC'
      ));
      if(empty($minimum)) {
        $from = strtotime('-1 day', $time);
      } else {
        $from = strtotime('-1 day', strtotime($minimum[$this->name]['spent_on']));
      }
    }
    if(empty($to)) {
      $max = $this->find('first', array(
        'fields'=>array('spent_on'), 
        'conditions'=>$this->Project->allowed_to_condition($current_user, 'view_time_entries', array('project'=>$project['Project'])),
        'order' => 'spent_on DESC'
      ));
      if(empty($max)) {
        $to = $time;
      } else {
        $to = strtotime($max[$this->name]['spent_on']);
      }
    }
    $from = date('Y-m-d', $from);
    $to =   date('Y-m-d', $to);
    
    return compact('from', 'to');
  }
}

