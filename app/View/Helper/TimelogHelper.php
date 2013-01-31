<?php
class TimelogHelper extends AppHelper
{
  var $helpers = array(
    'Candy', 'Html', 'AppAjax', 'CustomField'
  );

  function link_to_timelog_edit_url($project, $issue) {
    if (empty($issue)) {
      $url = array('controller'=>'timelog', 'action'=>'edit', 'project_id'=>$project['Project']['identifier']);
    } else {
      $url = array('controller'=>'timelog', 'action'=>'edit', 'project_id'=>$project['Project']['identifier'], '?'=>array('issue_id'=>$issue['Issue']['id']));
    }
    return $url;
  }
  function link_to_timelog_detail_url($project=array()) {
    if(!empty($project)) {
      $url = array('controller'=>'timelog', 'action'=>'details', 'project_id'=>$project['Project']['identifier']);
    } else {
      $url =  array('controller'=>'timelog', 'action'=>'details');
    }
    return $url;
  }
  function link_to_timelog_report_url($project=array()) {
    if(!empty($project)) {
      $url = array('controller'=>'timelog', 'action'=>'report', 'project_id'=>$project['Project']['identifier']);
    } else {
      $url =  array('controller'=>'timelog', 'action'=>'report');
    }
    return $url;
  }
  function render_timelog_breadcrumb($project, $issue) {
    $links = array();
    $links[] = $this->Html->link(__('All Projects'), '/timelog/details');
    if(!empty($project)) {
      $links[] = $this->Html->link(h($project['Project']['name']), $this->link_to_timelog_detail_url($project, false));
    }
    if(!empty($issue)) {
      $links[] = $this->Candy->link_to_issue($issue);
    }
    return $this->Candy->breadcrumb($links);
  }
  function url_options($project, $issue) {
    $options = array('?'=>array());
    if(!empty($project)) {
      $options['project_id'] = $project['Project']['identifier'];
    }
    if(!empty($issue)) {
      $options['?'] = array('issue_id'=>$issue['Issue']['id']);
    }
    $get_params = $this->request->query;
    $options['?'] = array_merge($options['?'], $get_params);
    return $options;
  }

/*
  def activity_collection_for_select_options
    activities = Enumeration::get_values('ACTI')
    collection = []
    collection << [ "--- #{l(:actionview_instancetag_blank_option)} ---", '' ] unless activities.detect(&:is_default)
    activities.each { |a| collection << [a.name, a.id] }
    collection
  end

*/

  function select_hours($data, $criteria, $value) {
    $result = array();
    foreach($data as $row) {
      foreach($row as $model => $values) { // some model include a record
        if(array_key_exists($criteria, $values) && ($values[$criteria] == $value)) {
          $result[] = $row; // found column 
          break; // next record.
        }
      }
    }
    return $result;
  }

  function sum_hours($data) {
    $sum = 0;
    foreach($data as $row) {
      $sum += $row['0']['hours'];
    }
    return $sum;
  }
  function options_for_period_select() {
    return array(
      'all'           => __('all time'),
      'today'         => __('today'),
      'yesterday'     => __('yesterday'),
      'current_week'  => __('this week'),
      'last_week'     => __('last week'),
      '7_days'        => sprintf(__('last %d days'), 7),
      'current_month' => __('this month'),
      'last_month'    => __('last month'),
      '30_days'       => sprintf(__('last %d days'), 30),
      'current_year'  => __('this year'),
    );
  }
  function selectable_criterias($available_criterias, $criterias) {
    $selectable_criterias = array();
    foreach($available_criterias as $k=>$available) {
      if(!in_array($k, $criterias)) {
        $selectable_criterias[$k] = __($available['label']);
      }
    }
    return $selectable_criterias;
  }
  function clear_link($project, $columns) {
    $project_id = null;
    if (!empty($project['Project']['identifier'])) {
      $project_id = $project['Project']['identifier'];
    }
    $get_params = $this->request->query;
    $url = $this->AppAjax->link(__('Clear'), 
        array('project_id' => $project_id, '?'=>array_merge($get_params, array('columns' => $columns))),
        array('class' => 'icon icon-reload', 'update' => 'content'));
    return $url;
  }

  function format_criteria_value($available_criterias, $criteria, $value) {
    $out = '';
    if(!empty($available_criterias[$criteria]['klass'])) {
      if(!empty($value)) {
        $k = $available_criterias[$criteria]['klass'];
        $k->_customFieldAfterFindDisable = true;
        $k->read(null, $value);
        $out = $k->to_string();
      }
    } else {
      $out = $this->CustomField->format_value($value, $available_criterias[$criteria]['format']);
    }
    if(empty($out)) {
      $out = __('none');
    }
    return $out;
  }
  function empty_td($count) {
    $out = '';
    for($i = 0; $i < $count; $i++) {
      $out .= '<td></td>';
    }
    return $out;
  }

  function criteria_values($criterias, $hours, $level) {
    $values = array();
    $col = $criterias[$level];
    foreach($hours as $hour) {
      foreach($hour as $model => $h) { // some model include a record
        if(array_key_exists($col, $h)) {
          $values[$h[$col]] = true; // found column 
          break; // next record.
        }
      }
    }
    $values = array_keys($values);
    return $values;
  }

  function report_criteria_to_csv($csv, $availableCriterias, $criterias, $periods, $hours, $columns, $level=0) {
    $values = $this->criteria_values($criterias, $hours, $level);
    foreach($values as $value) {
      $row = array();
      $hours_for_value = $this->select_hours($hours, $criterias[$level], $value);
      if(empty($hours_for_value)) {
        continue;
      }
      for($i = 0; $i < $level; $i++) {
        $row[] = '';
      }
      $row[] = $this->format_criteria_value($availableCriterias, $criterias[$level], $value);
      for($i = 0; $i < (count($criterias) - $level - 1); $i++) {
        $row[] = '';
      }
      $total = 0;
      foreach($periods as $period) {
        $sum = $this->sum_hours($this->select_hours($hours_for_value, $columns, $period));
        $total += $sum;
        $row[] = ($sum > 0 ? sprintf("%.2f", $sum) : '');
      }
      $row[] = sprintf("%.2f", $total);
      $csv->addRow($row);
      if(count($criterias) > $level + 1) {
        $this->report_criteria_to_csv($csv, $availableCriterias, $criterias, $periods, $hours_for_value, $columns, $level + 1);
      }
    }
  }

}