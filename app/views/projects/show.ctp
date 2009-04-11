<h2><?php __('Overview') ?></h2> 

<div class="splitcontentleft">
  <?php echo $candy->textilizable($this->data['Project']['description']) ?>	
  <ul>
<?php if (!empty($this->data['Project']['homepage'])): ?>
    <li><?php __('Homepage') ?>: <a href="<?php echo h($this->data['Project']['homepage']) ?>"><?php echo h($this->data['Project']['homepage']) ?></a></li>
<?php endif ?>
<?php if (count($subprojects) > 0): ?>
  <li><?php __('Subprojects') ?>: 
<?php foreach($subprojects as $key=>$subproject): ?>
<?php if ($key != 0) { echo ', '; } ?>
<?php echo $html->link(h($subproject['Project']['name']), array('controller'=>'projects', 'action'=>'show', 'project_id'=>$subproject['Project']['identifier_or_id'])) ?>
<?php endforeach ?>
</li>
<?php endif ?>
  <?php if ($parent_project): ?>
  <li><?php __('Subproject of') ?>: <?php echo $html->link(h($parent_project['Project']['name']), array('controller'=>'projects', 'action'=>'show', 'project_id'=>$parent_project['Project']['identifier_or_id'])) ?></li>
  <?php endif ?>
<?php foreach($custom_values as $custom_value): ?>
  <?php if (!empty($custom_value['CustomValue']['value'])): ?>
  <li><?php echo h($custom_value['CustomField']['name']) ?>: <?php echo h($custom_field->show_value($custom_value['CustomValue']['value'])) ?></li>
   <?php endif ?>
<?php endforeach ?>
	</ul>	

  <% if User.current.allowed_to?(:view_issues, @project) %>
  <div class="box">    
    <h3 class="icon22 icon22-tracker"><?php __('Issue tracking') ?></h3>
    <ul>
    <?php foreach($this->data['Tracker'] as $tracker): ?>
    <li><?php echo $html->link(h($tracker['name']), array('controller'=>'issues', 'action'=>'index', 'project_id'=>$this->data['Project']['identifier_or_id'], 'set_filter'=>1, 'tracker_id'=>$tracker['id'])) ?>:
    <?php echo $tracker['open_issues_by_tracker'] ?> <?php echo $candy->lwr('open', $tracker['open_issues_by_tracker']) ?>
    <?php __("'on'") ?> <?php echo $tracker['total_issues_by_tracker'] ?></li>
    <?php endforeach ?>
    </ul>
    <p><?php echo $html->link(__('View all issues', true), array('controller'=>'issues', 'action'=>'index', 'project_id'=>$this->data['Project']['identifier_or_id'],'set_filter'=>1)) ?></p>
  </div>
  <% end %>
</div>

<div class="splitcontentright">
    <% if @members_by_role.any? %>
	<div class="box">
  <h3 class="icon22 icon22-users"><?php __('Members') ?></h3>	
		<p><% @members_by_role.keys.sort.each do |role| %>
		<%= role.name %>:
		<%= @members_by_role[role].collect(&:user).sort.collect{|u| link_to_user u}.join(", ") %>
		<br />
		<% end %></p>
	</div>
	<% end %>
    
  <% if @news.any? && authorize_for('news', 'index') %>
  <div class="box">
    <h3><?php  __('Latest news') ?></h3>  
    <?php echo $this->element('news/news') ?>
<?php /*
    <%= render :partial => 'news/news', :collection => @news %>
 */ ?>
    <p><?php echo $html->link(__('View all news', true), array('controller'=>'news', 'action'=>'index', 'project_id'=>$this->data['Project']['identifier_or_id'])) ?></p>
  </div>  
  <% end %>
</div>

<% content_for :sidebar do %>
    <% planning_links = []
      planning_links << link_to_if_authorized(l(:label_calendar), :controller => 'issues', :action => 'calendar', :project_id => @project)
      planning_links << link_to_if_authorized(l(:label_gantt), :controller => 'issues', :action => 'gantt', :project_id => @project)
      planning_links.compact!
      unless planning_links.empty? %>
    <h3><?php __('Planning') ?></h3>
    <p><%= planning_links.join(' | ') %></p>
    <% end %>
    
    <% if @total_hours && User.current.allowed_to?(:view_time_entries, @project) %>
    <h3><?php __('Spent time') ?></h3>
    <p><span class="icon icon-time"><%= lwr(:label_f_hour, @total_hours) %></span></p>
    <p><?php echo $html->link(__('Details', true), array('controller'=>'timelog', 'action'=>'details', 'project_id'=>$this->data['Project']['identifier_or_id'])) ?> |
       <?php echo $html->link(__('Report', true), array('controller'=>'timelog', 'action'=>'report', 'project_id'=>$this->data['Project']['identifier_or_id'])) ?></p>
    <% end %>
<% end %>

<% content_for :header_tags do %>
<%= auto_discovery_link_tag(:atom, {:action => 'activity', :id => @project, :format => 'atom', :key => User.current.rss_key}) %>
<% end %>

<?php $candy->html_title(__('Overview', true)) ?>
