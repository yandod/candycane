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
    return $this->User->name($data).': '.sprintf(__('%.2f hour'), $data['TimeEntry']['hours'])." ($event_title)";
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
  
  /**
   * Controller->report_available_criterias($this->_project)
   */
  function report_available_criterias($project) {
    $this->bindModel(array('belongsTo'=>array('Issue')));
    $available_criterias = array(
      'project'  => array('sql' => 'TimeEntry.project_id',
                           'klass' => $this->Project,
                           'label' => 'Project'),
      'version'  => array('sql' => "Issue.fixed_version_id",
                           'klass' => $this->Issue->FixedVersion,
                           'label' => 'Version'),
      'category' => array('sql' => "Issue.category_id",
                           'klass' => $this->Issue->Category,
                           'label' => 'Category'),
      'member'   => array('sql' => "TimeEntry.user_id",
                           'klass' => $this->User,
                           'label' => 'Member'),
      'tracker'  => array('sql' => "Issue.tracker_id",
                           'klass' => $this->Issue->Tracker,
                           'label' => 'Tracker'),
      'activity' => array('sql' => "TimeEntry.activity_id",
                           'klass' => $this->Activity,
                           'label' => 'Activity'),
      'issue'    => array('sql' => "TimeEntry.issue_id",
                           'klass' => $this->Issue,
                           'label' => 'Issue'),
    );
    $CustomValue = & ClassRegistry::init('CustomValue');
    $custom_value_table_name = $CustomValue->fullTableName();
    $project_table_name = $this->Project->fullTableName();
    $issue_table_name = $this->Issue->fullTableName();
    $time_entry_table_name = $this->fullTableName();
    # Add list and boolean custom fields as available criterias
    $custom_fields = empty($project['Project']['id']) ? $this->Issue->available_custom_fields() : $this->Issue->available_custom_fields($project['Project']['id']);
    foreach($custom_fields as $cf) {
      if(!empty($cf['CustomField']['field_format']) && (($cf['CustomField']['field_format'] == 'list') || ($cf['CustomField']['field_format'] == 'bool'))) {
        $available_criterias["cf_{$cf['CustomField']['id']}"] = array(
          'sql' => "(SELECT c.value FROM $custom_value_table_name c WHERE c.custom_field_id = {$cf['CustomField']['id']} AND c.customized_type = 'Issue' AND c.customized_id = Issue.id)",
          'format' => $cf['CustomField']['field_format'],
          'label' => $cf['CustomField']['name']);
      }
    }

    # Add list and boolean time entry custom fields
    $custom_fields = $this->available_custom_fields();
    foreach($custom_fields as $cf) {
      if(!empty($cf['CustomField']['field_format']) && (($cf['CustomField']['field_format'] == 'list') || ($cf['CustomField']['field_format'] == 'bool'))) {
        $available_criterias["cf_{$cf['CustomField']['id']}"] = array(
          'sql' => "(SELECT c.value FROM $custom_value_table_name c WHERE c.custom_field_id = {$cf['CustomField']['id']} AND c.customized_type = 'TimeEntry' AND c.customized_id = TimeEntry.id)",
          'format' => $cf['CustomField']['field_format'],
          'label' => $cf['CustomField']['name']);
      }
    }
    return $available_criterias;
  }

  /**
   * TimeEntry->find_report_hours($this->_project, $available_criterias, $criterias, $this->Setting, $this->current_user, $range)
   *
   * @param $project : Project found data of array
   * @param $available_criterias : maybe return from report_available_criterias().
   * @param $criterias : Array of retrieved column name
   * @param $setting : System-setting datas
   * @param $user : current user data of array without model name.
   * @param $range : Within the retrieved range, it has the beginning date(key is from) and the end date(key is to). 
   */
  function find_report_hours($project, $available_criterias, $criterias, $setting, $user, $range) {
    $this->bindModel(array('belongsTo'=>array('Issue')));
    $project_table_name = $this->Project->fullTableName();
    $issue_table_name = $this->Issue->fullTableName();
    $time_entry_table_name = $this->fullTableName();

    $sql_select = array();
    $sql_group_by = array();
    foreach($criterias as $criteria) {
      $sql_select[]   = $available_criterias[$criteria]['sql']." AS ".$criteria;
      $sql_group_by[] = $available_criterias[$criteria]['sql'];
    }
    $sql_select = join(', ', $sql_select).', ';
    $sql_group_by = join(', ', $sql_group_by).', ';

    $sql  = "SELECT {$sql_select} tyear, tmonth, tweek, spent_on, SUM(hours) AS hours";
    $sql .= " FROM $time_entry_table_name AS TimeEntry";
    $sql .= " LEFT JOIN $issue_table_name AS Issue ON TimeEntry.issue_id = Issue.id";
    $sql .= " LEFT JOIN $project_table_name AS Project ON TimeEntry.project_id = Project.id";
    $sql .= " WHERE";
    if(!empty($this->_project)) {
      $sql .= sprintf(" (%s) AND", $this->Project->project_condition($setting->display_subprojects_issues, $project['Project'], true));
    }
    $sql .= sprintf(" (%s) AND", $this->Project->allowed_to_condition_string($user, ':view_time_entries'));
    $sql .= sprintf(" (spent_on BETWEEN '%s' AND '%s')" , $this->quoted_date($range['from'], 'spent_on'), $this->quoted_date($range['to'], 'spent_on'));
    $sql .= " GROUP BY {$sql_group_by} tyear, tmonth, tweek, spent_on";

    $hours = $this->query($sql);

    return $hours;
  }
  
  /**
   * Please call me after find_report_hours()
   */
  function get_total_hours(&$hours, $columns) {
    $total_hours = 0;
    foreach($hours as $k=>$row) {
      $total_hours += $row[0]['hours'];
      $row = $row['TimeEntry'];
      switch($columns) {
      case 'year' :
        $hours[$k]['TimeEntry']['year'] = $row['tyear'];
        break;
      case 'month' :
        $hours[$k]['TimeEntry']['month'] = "{$row['tyear']}-{$row['tmonth']}";
        break;
      case 'week' :
        $hours[$k]['TimeEntry']['week'] = "{$row['tyear']}-{$row['tweek']}";
        break;
      case 'day' :
        $hours[$k]['TimeEntry']['day'] = date('Y-n-j', strtotime($row['spent_on']));
        break;
      }
    }
    return $total_hours;
  }
  
  /**
   * @usage : $periods = $this->TimeEntry->get_periods($range, $columns);
   * @param $range : Within the retrieved range, it has the beginning date(key is from) and the end date(key is to). 
   */
  function get_periods($range, $columns) {
    $periods = array();
    # Date#at_beginning_of_ not supported in Rails 1.2.x
    $date_from = strtotime($range['from']);
    # 100 columns max
    while($date_from <= strtotime($range['to']) && count($periods) < 100) {
      switch($columns) {
      case 'year' :
        $periods[] = date('Y', $date_from);
        $date_from = strtotime(date('Y-1-1', strtotime('+1 year', $date_from)));
        break;
      case 'month' :
        $periods[] = date('Y-n', $date_from);
        $date_from = strtotime(date('Y-n-1', strtotime('+1 month', $date_from)));
        break;
      case 'week' :
        $periods[] = date("Y-W", $date_from);
        $w = date('w', $date_from);
        if($w == 1) {
          $add = 7;
        } else {
          $add = 8 - $w;
        }
        $date_from = strtotime("+{$add} day", $date_from);
        break;
      case 'day' :
        $periods[] = date('Y-n-j', $date_from);
        $date_from = strtotime('+1 day', $date_from);
        break;
      }
    }
    return $periods;
  }
  
}

