<?php
App::uses('AppHelper', 'View/Helper');

class IssuesHelper extends AppHelper
{
  public $name = 'Issues';
  public $helpers = array(
    'Candy',
    'CustomField',
    'Html'
  );
  var $relation_types = array();
  function __construct($view, $settings) {
    $this->relation_types = array(
            "relates" =>     array('name'=>__('related to'), 'sym_name'=>__('related to'), 'order'=> 1),
            "duplicates" =>  array('name'=>__('duplicates'), 'sym_name'=>__('duplicated by'), 'order'=>2),
            "blocks" =>      array('name'=>__('blocks'),     'sym_name'=>__('blocked by'), 'order'=>3),
            "precedes" =>    array('name'=>__('precedes'),   'sym_name'=>__('follows'), 'order'=>4)
          );
	parent::__construct($view, $settings);
  }
#require 'csv'
#
#module IssuesHelper
#  include ApplicationHelper
#
#  def render_issue_tooltip(issue)
#    @cached_label_start_date ||= l(:field_start_date)
#    @cached_label_due_date ||= l(:field_due_date)
#    @cached_label_assigned_to ||= l(:field_assigned_to)
#    @cached_label_priority ||= l(:field_priority)
#    
#    link_to_issue(issue) + ": #{h(issue.subject)}<br /><br />" +
#      "<strong>#{@cached_label_start_date}</strong>: #{format_date(issue.start_date)}<br />" +
#      "<strong>#{@cached_label_due_date}</strong>: #{format_date(issue.due_date)}<br />" +
#      "<strong>#{@cached_label_assigned_to}</strong>: #{issue.assigned_to}<br />" +
#      "<strong>#{@cached_label_priority}</strong>: #{issue.priority.name}"
#  end
#  
  function css_issue_classes($issue)
  {
    return 'issue status-' . $issue['Status']['position'] . ' priority-' . $issue['Priority']['position'];
  }
#  # Returns a string of css classes that apply to the given issue
#  def css_issue_classes(issue)
#    s = "issue status-#{issue.status.position} priority-#{issue.priority.position}"
#    s << ' overdue' if issue.overdue?
#    s
#  end
#  
#  def sidebar_queries
#    unless @sidebar_queries
#      # User can see public queries and his own queries
#      visible = ARCondition.new(["is_public = ? OR user_id = ?", true, (User.current.logged? ? User.current.id : 0)])
#      # Project specific queries and global queries
#      visible << (@project.nil? ? ["project_id IS NULL"] : ["project_id IS NULL OR project_id = ?", @project.id])
#      @sidebar_queries = Query.find(:all, 
#                                    :order => "name ASC",
#                                    :conditions => visible.conditions)
#    end
#    @sidebar_queries
#  end
#
  public function show_detail($detail, $no_html=false) {
    $result = $this->requestAction(array('controller'=>'issues', 'action'=>'detail_values'), compact('detail'));
    // $label, $value, $old_value, $field_format, $attachment
    extract($result);
    switch($detail['property']) {
    case 'attr' :
      $label = __($detail['prop_key']);
      switch($detail['prop_key']) {
      case 'due_date' :
      case 'start_date' :
        if(!empty($detail['value'])) $value = $this->Candy->format_date($detail['value']);
        if(!empty($detail['old_value'])) $old_value = $this->Candy->format_date($detail['old_value']);
        break;
      case 'estimated_hours' :
        if($detail['value'] != '') $value = ($detail['value'] ==0 ? 0 : "%0.02f" % $detail['value']);
        if($detail['old_value'] != '') $old_value = ($detail['old_value'] == 0 ? 0 : "%0.02f" % $detail['old_value']);
        break;
      }
      break;
    case 'cf' :
      if(!empty($field_format)) {
        $value = $this->CustomField->format_value($detail['value'], $field_format);
        $old_value = $this->CustomField->format_value($detail['old_value'], $field_format);
      }
      break;
    case 'attachment' :
      $label = __('File');
      break;
    }
    // TODO : For plugin, call_hook 
    // call_hook(:helper_issues_show_detail_after_setting, {:detail => detail, :label => label, :value => value, :old_value => old_value })

    if(empty($label)) $label = $detail['prop_key'];
    if(empty($value)) $value = $detail['value'];
    if(empty($old_value)) $old_value = $detail['old_value'];
    
    if(!empty($label)) $label = $this->Candy->label_text($label);
    if(!$no_html) {
      $label = $this->Html->tag('strong', $label);
      if(!empty($detail['old_value'])) $old_value = $this->Html->tag("i", h($old_value));
      if(!empty($detail['old_value']) && (!$detail['value'] || empty($detail['value']))) $old_value = $this->Html->tag("strike", $old_value); 
      if($detail['property'] == 'attachment' && ($value != '') && !empty($attachment)) {
        # Link to the attachment if it has not been removed
        $value = $this->Candy->link_to_attachment($attachment);
      } else {
        if(!empty($value)) $value = $this->Html->tag("i", h($value));
      }
    }
    
    $out = '';
    if($detail['value'] != '') {
      switch($detail['property']) {
      case 'attr' :
      case 'cf' :
        if($detail['old_value'] != '') {
          $out = $label." ".sprintf(__('changed from %s to %s'), $old_value, $value);
        } else {
          $out = $label." ".sprintf(__('set to %s'), $value);
        }
        break;
      case 'attachment' :
        $out = "$label $value ".__('added');
        break;
      }
    } else {
      switch($detail['property']) {
      case 'attr' :
      case 'cf' :
        $out = $label." ".__('deleted')." ($old_value)";
        break;
      case 'attachment' :
        $out = "$label $old_value ".__('deleted');
        break;
      }
    }
    return $out;
  }
  function relation_issue($issue, $relation) {
    return ($relation['IssueRelation']['issue_from_id'] == $issue['Issue']['id']) ? $relation['IssueFrom'] : $relation['IssueTo'];
  }
  function relation_other_issue($issue, $relation) {
    return ($relation['IssueRelation']['issue_from_id'] == $issue['Issue']['id']) ? $relation['IssueTo'] : $relation['IssueFrom'];
  }
  function relation_type_select() {
    $options = array();
    foreach($this->relation_types as $key=>$type) {
      $options[$key] = $type['name'];
    }
    return $options;
  }
  function relation_label_for($issue, $relation) {
    return array_key_exists($relation['IssueRelation']['relation_type'], $this->relation_types) ? $this->relation_types[$relation['IssueRelation']['relation_type']][($relation['IssueRelation']['issue_from_id'] == $issue['Issue']['id']) ? 'name' : 'sym_name'] : __('unknow');
  }
  function relation_delay_day($relation) {
    $out = '';
    if(array_key_exists('delay', $relation['IssueRelation']) && $relation['IssueRelation']['delay'] != 0) {
      if($relation['IssueRelation']['delay'] > 1) {
        $out = sprintf(__('%d days'), $relation['IssueRelation']['delay']);
      } else {
        $out = sprintf(__('%d day'), $relation['IssueRelation']['delay']);
      }
      $out = "($out)";
    }
    return $out;
  }
#  
#  def issues_to_csv(issues, project = nil)
#    ic = Iconv.new(l(:general_csv_encoding), 'UTF-8')    
#    decimal_separator = l(:general_csv_decimal_separator)
#    export = StringIO.new
#    CSV::Writer.generate(export, l(:general_csv_separator)) do |csv|
#      # csv header fields
#      headers = [ "#",
#                  l(:field_status), 
#                  l(:field_project),
#                  l(:field_tracker),
#                  l(:field_priority),
#                  l(:field_subject),
#                  l(:field_assigned_to),
#                  l(:field_category),
#                  l(:field_fixed_version),
#                  l(:field_author),
#                  l(:field_start_date),
#                  l(:field_due_date),
#                  l(:field_done_ratio),
#                  l(:field_estimated_hours),
#                  l(:field_created_on),
#                  l(:field_updated_on)
#                  ]
#      # Export project custom fields if project is given
#      # otherwise export custom fields marked as "For all projects"
#      custom_fields = project.nil? ? IssueCustomField.for_all : project.all_issue_custom_fields
#      custom_fields.each {|f| headers << f.name}
#      # Description in the last column
#      headers << l(:field_description)
#      csv << headers.collect {|c| begin; ic.iconv(c.to_s); rescue; c.to_s; end }
#      # csv lines
#      issues.each do |issue|
#        fields = [issue.id,
#                  issue.status.name, 
#                  issue.project.name,
#                  issue.tracker.name, 
#                  issue.priority.name,
#                  issue.subject,
#                  issue.assigned_to,
#                  issue.category,
#                  issue.fixed_version,
#                  issue.author.name,
#                  format_date(issue.start_date),
#                  format_date(issue.due_date),
#                  issue.done_ratio,
#                  issue.estimated_hours.to_s.gsub('.', decimal_separator),
#                  format_time(issue.created_on),  
#                  format_time(issue.updated_on)
#                  ]
#        custom_fields.each {|f| fields << show_value(issue.custom_value_for(f)) }
#        fields << issue.description
#        csv << fields.collect {|c| begin; ic.iconv(c.to_s); rescue; c.to_s; end }
#      end
#    end
#    export.rewind
#    export
#  end
#end
  function spent_hours($issue)
  {
    $spent_hours = 0;
    if(!empty($issue['TimeEntry'])) {
      foreach($issue['TimeEntry'] as $time_entry) {
        $spent_hours += $time_entry['hours'];
      }
    }
    return $spent_hours;
  }
}
