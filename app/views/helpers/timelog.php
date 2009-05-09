<?php
# redMine - project management software
# Copyright (C) 2006  Jean-Philippe Lang
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
class TimelogHelper extends AppHelper
{
  var $helpers = array(
    'Candy', 'Html', 'AppAjax', 'CustomField'
  );

  function link_to_timelog_edit_url($project, $issue) {
    if (empty($issue)) {
      $url = $this->url(array('controller'=>'timelog', 'action'=>'edit', 'project_id'=>$project['Project']['identifier']));
    } else {
      $url = $this->url(array('controller'=>'timelog', 'action'=>'edit', 'project_id'=>$project['Project']['identifier'], '?'=>array('issue_id'=>$issue['Issue']['id'])));
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
    $links[] = $this->Html->link(__('All Projects',true), '/timelog/details');
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
    $get_params = $this->params['url'];
    unset($get_params['url']);
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
      if($row['TimeEntry'][$criteria] == $value) {
        $result[] = $row;
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
      'all'           => __('all time',true),
      'today'         => __('today',true),
      'yesterday'     => __('yesterday',true),
      'current_week'  => __('this week',true),
      'last_week'     => __('last week',true),
      '7_days'        => sprintf(__('last %d days',true), 7),
      'current_month' => __('this month',true),
      'last_month'    => __('last month',true),
      '30_days'       => sprintf(__('last %d days',true), 30),
      'current_year'  => __('this year',true),
    );
  }
  function selectable_criterias($available_criterias, $criterias) {
    $selectable_criterias = array();
    foreach($available_criterias as $k=>$available) {
      if(!in_array($k, $criterias)) {
        $selectable_criterias[$k] = __($available['label'],true);
      }
    }
    return $selectable_criterias;
  }
  function clear_link($project, $columns) {
    $get_params = $this->params['url'];
    unset($get_params['url']);
    $url = $this->AppAjax->link(__('Clear',true), 
        array('project_id' => $project['Project']['identifier'], '?'=>array_merge($get_params, array('columns' => $columns))),
        array('class' => 'icon icon-reload', 'update' => 'content'));
    return $url;
  }

/*
  def entries_to_csv(entries)
    ic = Iconv.new(l(:general_csv_encoding), 'UTF-8')    
    decimal_separator = l(:general_csv_decimal_separator)
    custom_fields = TimeEntryCustomField.find(:all)
    export = StringIO.new
    CSV::Writer.generate(export, l(:general_csv_separator)) do |csv|
      # csv header fields
      headers = [l(:field_spent_on),
                 l(:field_user),
                 l(:field_activity),
                 l(:field_project),
                 l(:field_issue),
                 l(:field_tracker),
                 l(:field_subject),
                 l(:field_hours),
                 l(:field_comments)
                 ]
      # Export custom fields
      headers += custom_fields.collect(&:name)

      csv << headers.collect {|c| begin; ic.iconv(c.to_s); rescue; c.to_s; end }
      # csv lines
      entries.each do |entry|
        fields = [format_date(entry.spent_on),
                  entry.user,
                  entry.activity,
                  entry.project,
                  (entry.issue ? entry.issue.id : nil),
                  (entry.issue ? entry.issue.tracker : nil),
                  (entry.issue ? entry.issue.subject : nil),
                  entry.hours.to_s.gsub('.', decimal_separator),
                  entry.comments
                  ]
        fields += custom_fields.collect {|f| show_value(entry.custom_value_for(f)) }

        csv << fields.collect {|c| begin; ic.iconv(c.to_s); rescue; c.to_s; end }
      end
    end
    export.rewind
    export
  end
*/
  function format_criteria_value($available_criterias, $criteria, $value) {
    $out = __('none',true);
    if(!empty($value)) {
      $k = $available_criterias[$criteria]['klass'];
      if(!empty($k)) {
        $k->read(null, $value);
        $out = $k->toString();
      } else {
        $this->CustomField->format_value($value, $available_criterias[$criteria]['format']);
      }
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
/*
  def report_to_csv(criterias, periods, hours)
    export = StringIO.new
    CSV::Writer.generate(export, l(:general_csv_separator)) do |csv|
      # Column headers
      headers = criterias.collect {|criteria| l(@available_criterias[criteria][:label]) }
      headers += periods
      headers << l(:label_total)
      csv << headers.collect {|c| to_utf8(c) }
      # Content
      report_criteria_to_csv(csv, criterias, periods, hours)
      # Total row
      row = [ l(:label_total) ] + [''] * (criterias.size - 1)
      total = 0
      periods.each do |period|
        sum = sum_hours(select_hours(hours, @columns, period.to_s))
        total += sum
        row << (sum > 0 ? "%.2f" % sum : '')
      end
      row << "%.2f" %total
      csv << row
    end
    export.rewind
    export
  end

  def report_criteria_to_csv(csv, criterias, periods, hours, level=0)
    hours.collect {|h| h[criterias[level]].to_s}.uniq.each do |value|
      hours_for_value = select_hours(hours, criterias[level], value)
      next if hours_for_value.empty?
      row = [''] * level
      row << to_utf8(format_criteria_value(criterias[level], value))
      row += [''] * (criterias.length - level - 1)
      total = 0
      periods.each do |period|
        sum = sum_hours(select_hours(hours_for_value, @columns, period.to_s))
        total += sum
        row << (sum > 0 ? "%.2f" % sum : '')
      end
      row << "%.2f" %total
      csv << row

      if criterias.length > level + 1
        report_criteria_to_csv(csv, criterias, periods, hours_for_value, level + 1)
      end
    end
  end

  def to_utf8(s)
    @ic ||= Iconv.new(l(:general_csv_encoding), 'UTF-8')
    begin; @ic.iconv(s.to_s); rescue; s.to_s; end
  end
end
*/
}

