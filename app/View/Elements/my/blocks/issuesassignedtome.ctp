<h3><?php echo __('Issues assigned to me') ?></h3>
<?php
$Issue = ClassRegistry::init('Issue');
$assigned_issues = $Issue->find('all',
  array(
    'conditions' => array('Issue.assigned_to_id' => $currentuser['id']),
    'limit' => 10,
    'order' => 'Issue.updated_on DESC'
  )
);
?>
<?php
//<% assigned_issues = Issue.find(:all, 
//                                :conditions => ["assigned_to_id=? AND #{IssueStatus.table_name}.is_closed=? AND #{Project.table_name}.status=#{Project::STATUS_ACTIVE}", user.id, false],
//                                :limit => 10, 
//                                :include => [ :status, :project, :tracker, :priority ], 
//                                :order => "#{Enumeration.table_name}.position DESC, #{Issue.table_name}.updated_on DESC") %>
?>
<?php echo $this->element('issues/list_simple',array('issues'=>$assigned_issues)) ?>
<?php //<%= render :partial => 'issues/list_simple', :locals => { :issues => assigned_issues } %> ?>
<?php if (count($assigned_issues) > 0): ?>
<?php //<p class="small"><%= link_to l(:label_issue_view_all), :controller => 'issues', :action => 'index', :set_filter => 1, :assigned_to_id => 'me' %></p> ?>
<p class="small"><?php echo $this->Html->link(__('View all issues'), array(
	'controller' => 'issues',
	'action' => 'index',
	'set_filter' => 1,
	'assigned_to_id' => 'me'
)) ?></p>
<?php endif; ?>
<?php
//<% content_for :header_tags do %>
//<%= auto_discovery_link_tag(:atom, 
//                            {:controller => 'issues', :action => 'index', :set_filter => 1,
//                             :assigned_to_id => 'me', :format => 'atom', :key => User.current.rss_key},
//                            {:title => l(:label_assigned_to_me_issues)}) %>
//<% end %>
?>
