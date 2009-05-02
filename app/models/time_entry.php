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
#  belongs_to :project
#  belongs_to :issue
#  belongs_to :user
#  belongs_to :activity, :class_name => 'Enumeration', :foreign_key => :activity_id
#  
#  attr_protected :project_id, :user_id, :tyear, :tmonth, :tweek
#
#  acts_as_customizable
#  acts_as_event :title => Proc.new {|o| "#{o.user}: #{lwr(:label_f_hour, o.hours)} (#{(o.issue || o.project).event_title})"},
#                :url => Proc.new {|o| {:controller => 'timelog', :action => 'details', :project_id => o.project}},
#                :author => :user,
#                :description => :comments
#  
#  
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
    'Customizable'=>array('is_for_all'=>0)
  );

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

  function find_visible_by($user)
  {
    // return $this->find('all', array('conditions' => $this->Project->allowed_to_condition($user, 'view_time_entries')));
    return array();
  }
  function sum($field, $conditions) {
    $results = $this->find('all', array('conditions'=>$conditions, 'fields'=>array($field)));
    if(!$results) {
      return 0;
    }
    $sum = 0;
    foreach($results as $result) {
      $sum += $result['TimeEntry'][$field];
    }
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
}

