<?php
class QueriesHelper extends AppHelper
{
  var $name = 'Queries';
  var $helpers = array(
    'Html',
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
      return $this->Html->link($issue['Issue']['subject'], array('controller' => 'issues', 'action' => 'show', 'id' => $issue['Issue']['id']));
    case 'tracker':
      return h($issue['Tracker']['name']);
    case 'status':
      return h($issue['Status']['name']);
    case 'priority':
      return h($issue['Priority']['name']);
    case 'assigned_to':
      return h($issue['AssignedTo']['firstname'] . ' ' . $issue['AssignedTo']['lastname']);
    case 'updated_on':
      return h($issue['Issue']['updated_on']);
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
    $sort_by = a();
    foreach ($filters as $v) $sort_by[] = $v['order'];
    array_multisort($sort_by, SORT_ASC, $filters);
    return $filters;
  }
  function add_filter_select_options($filters) {
    $results = a();
    foreach ($filters as $field => $filter) {
      $results[$field] = __(preg_replace('/_id$/', '', $field), true);
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