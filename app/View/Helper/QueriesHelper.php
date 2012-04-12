<?php
class QueriesHelper extends AppHelper
{
  var $name = 'Queries';
  var $helpers = array(
    'Html',
    'Candy',
  );
  
## redMine - project management software
## Copyright (C) 2006-2007  Jean-Philippe Lang
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
#module QueriesHelper
#  
#  def operators_for_select(filter_type)
#    Query.operators_by_filter_type[filter_type].collect {|o| [l(Query.operators[o]), o]}
#  end
#  
#  def column_header(column)
#    column.sortable ? sort_header_tag(column.name.to_s, :caption => column.caption,
#                                                        :default_order => column.default_order) : 
#                      content_tag('th', column.caption)
#  end
#
  function columns($query = null)
  {
    return $this->Settings->issue_list_default_columns;
  }

  function column_content($column, $issue)
  {
    switch ($column) {
    case 'subject':
      return $this->Html->link($issue['Issue']['subject'], array('controller' => 'issues', 'action' => 'show', $issue['Issue']['id']));
    case 'author':
      return $this->Candy->format_username($issue['Author']);
    case 'tracker':
      return h($issue['Tracker']['name']);
    case 'status':
      return h($issue['Status']['name']);
    case 'priority':
      return h($issue['Priority']['name']);
    case 'assigned_to':
      return strlen($issue['Issue']['assigned_to_id']) ? $this->Candy->format_username($issue['AssignedTo']) : '';
    case 'updated_on':
      return $this->Candy->format_time($issue['Issue']['updated_on']);
    case 'category':
      return $issue['Category']['name'];
    case 'fixed_version':
      return $this->Candy->link_to_version($issue['FixedVersion']);
    case 'start_date':
      return $this->Candy->format_date($issue['Issue']['start_date']);
    case 'due_date':
      return $this->Candy->format_date($issue['Issue']['due_date']);
    case 'estimated_hours':
      return sprintf(__('%.2f hour'), $issue['Issue']['estimated_hours']);
    case 'done_ratio':
      return $this->Candy->progress_bar($issue['Issue']['done_ratio'], array('width' => '80px', 'legend' => $issue['Issue']['done_ratio'] . '%'));
    case 'created_on':
      return $this->Candy->format_date($issue['Issue']['created_on']);
    default:
      return $column;
    }
  }
  
  function name($query)
  {
    return strlen($query['Query']['name']) ? $query['Query']['name'] : 'Issues';
  }
  
  function editable($query, $user)
  {
    return true; #TODO
  }
  function available_filters_sort_order($filters) {
    $sort_by = array();
    foreach ($filters as $v) $sort_by[] = $v['order'];
    array_multisort($sort_by, SORT_ASC, $filters);
    return $filters;
  }
  function add_filter_select_options($filters) {
    $results = array();
    foreach ($filters as $field => $filter) {
      $results[$field] = __(preg_replace('/_id$/', '', $field));
    }
    return $results;
  }
#  def column_content(column, issue)
#    if column.is_a?(QueryCustomFieldColumn)
#      cv = issue.custom_values.detect {|v| v.custom_field_id == column.custom_field.id}
#      show_value(cv)
#    else
#      value = issue.send(column.name)
#      if value.is_a?(Date)
#        format_date(value)
#      elsif value.is_a?(Time)
#        format_time(value)
#      else
#        case column.name
#        when :subject
#        h((@project.nil? || @project != issue.project) ? "#{issue.project.name} - " : '') +
#          link_to(h(value), :controller => 'issues', :action => 'show', :id => issue)
#        when :done_ratio
#          progress_bar(value, :width => '80px')
#        when :fixed_version
#          link_to(h(value), { :controller => 'versions', :action => 'show', :id => issue.fixed_version_id })
#        else
#          h(value)
#        end
#      end
#    end
#  end
#end
}
