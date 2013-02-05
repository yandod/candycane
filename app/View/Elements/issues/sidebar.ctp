<h3><?php echo __('Issues') ?></h3>
<?php echo $this->Html->link(__('View all issues'), am(isset($main_project) && $main_project ? array('project_id' => $main_project['Project']['identifier']) : array(), array('controller' => 'issues', 'action' => 'index', '?'=>array('set_filter'=>1)))) ?><br />
<!--<%= link_to l(:label_issue_view_all), { :controller => 'issues', :action => 'index', :project_id => @project, :set_filter => 1 } %><br />-->
<?php if (isset($main_project) && $main_project): ?>
<?php echo $this->Html->link(__('Summary'), am(isset($main_project) && $main_project ? array('project_id' => $main_project['Project']['identifier']) : array(), array('controller' => 'reports', 'action' => 'issue_report'))) ?><br />
<?php echo $this->Html->link(__('Change log'), am(isset($main_project) && $main_project ? array('project_id' => $main_project['Project']['identifier']) : array(), array('controller' => 'projects', 'action' => 'changelog'))) ?><br />
<!--
<%= link_to l(:field_summary), :controller => 'reports', :action => 'issue_report', :id => @project %><br />
<%= link_to l(:label_change_log), :controller => 'projects', :action => 'changelog', :id => @project %><br />
-->
<?php endif ?>
<!--
<%= call_hook(:view_issues_sidebar_issues_bottom) %>

<% planning_links = []
  planning_links << link_to(l(:label_calendar), :action => 'calendar', :project_id => @project) if User.current.allowed_to?(:view_calendar, @project, :global => true)
  planning_links << link_to(l(:label_gantt), :action => 'gantt', :project_id => @project) if User.current.allowed_to?(:view_gantt, @project, :global => true)
%>
<% unless planning_links.empty? %>
-->
<h3><?php echo __('Planning') ?></h3>
<!--
<p><%= planning_links.join(' | ') %></p>
<%= call_hook(:view_issues_sidebar_planning_bottom) %>
<% end %>
<% unless sidebar_queries.empty? -%>
-->
<h3><?php echo __('Custom queries') ?></h3>
<?php foreach($sidebar_queries as $query): ?>
<?php
	if(isset($main_project)) {
		echo $this->Html->link(h($query['Query']['name']), array('controller' => 'issues', 'action' => 'index', 'project_id' => $main_project['Project']['identifier'], '?'=>array('query_id' => $query['Query']['id'])));
		echo '<br />';
	} elseif(empty($query['Query']['project_id'])) {
		echo $this->Html->link(h($query['Query']['name']), array('controller' => 'issues', 'action' => 'index', '?'=>array('query_id' => $query['Query']['id'])));
		echo '<br />';
	}
?>
<?php endforeach; ?>
