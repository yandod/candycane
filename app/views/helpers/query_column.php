<?php
class QueryColumnHelper extends AppHelper
{
  var $name = 'QueryColumn';
  var $available_columns = array(
    'tracker' => array(
      'sortable' => 'Tracker.position',
    ),
    'status' => array(
      'sortable' => 'Status.position',
    ),
    'priority' => array(
      'sortable' => 'Priority.position',
      'default_order' => 'desc',
    ),
    'subject' => array(
      'sortable' => 'Issue.subject',
    ),
    'author' => array(
    ),
    'assigned_to' => array(
      'sortable' => 'AssignedTo.lastname',
    ),
    'updated_on' => array(
      'sortable' => 'Issue.updated_on',
      'default_order' => 'desc',
    ),
    'category' => array(
      'sortable' => 'Category.name',
    ),
    'fixed_version' => array(
      'sortable' => 'FixedVersion.effective_date',
      'default_order' => 'desc',
    ),
    'start_date' => array(
      'sortable' => 'Issue.start_date',
    ),
    'due_date' => array(
      'sortable' => 'Issue.due_date',
    ),
    'estimated_hours' => array(
      'sortable' => 'Issue.estimated_hours',
    ),
    'done_ratio' => array(
      'sortable' => 'Issue.done_ratio',
    ),
    'created_on' => array(
      'sortable' => 'Issue.created_on',
      'default_order' => 'desc',
    ),
  );
#  @@available_columns = [
#    QueryColumn.new(:tracker, :sortable => "#{Tracker.table_name}.position"),
#    QueryColumn.new(:status, :sortable => "#{IssueStatus.table_name}.position"),
#    QueryColumn.new(:priority, :sortable => "#{Enumeration.table_name}.position", :default_order => 'desc'),
#    QueryColumn.new(:subject, :sortable => "#{Issue.table_name}.subject"),
#    QueryColumn.new(:author),
#    QueryColumn.new(:assigned_to, :sortable => "#{User.table_name}.lastname"),
#    QueryColumn.new(:updated_on, :sortable => "#{Issue.table_name}.updated_on", :default_order => 'desc'),
#    QueryColumn.new(:category, :sortable => "#{IssueCategory.table_name}.name"),
#    QueryColumn.new(:fixed_version, :sortable => "#{Version.table_name}.effective_date", :default_order => 'desc'),
#    QueryColumn.new(:start_date, :sortable => "#{Issue.table_name}.start_date"),
#    QueryColumn.new(:due_date, :sortable => "#{Issue.table_name}.due_date"),
#    QueryColumn.new(:estimated_hours, :sortable => "#{Issue.table_name}.estimated_hours"),
#    QueryColumn.new(:done_ratio, :sortable => "#{Issue.table_name}.done_ratio"),
#    QueryColumn.new(:created_on, :sortable => "#{Issue.table_name}.created_on", :default_order => 'desc'),
#  ]
  function sortable($column)
  {
    return isset($this->available_columns[$column]['sortable']) ? $this->available_columns[$column]['sortable'] : null;
  }
  function default_order($column)
  {
    return isset($this->available_columns[$column]['default_order']) ? $this->available_columns[$column]['default_order'] : null;
  }
}