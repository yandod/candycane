<h2><?php __('Overview') ?></h2> 

<div class="splitcontentleft">
  <?php echo $candy->textilizable($this->data['Project']['description']) ?>	
  <ul>
<?php if (!empty($this->data['Project']['homepage'])): ?>
    <li><?php __('Homepage') ?>: <a href="<?php echo h($this->data['Project']['homepage']) ?>"><?php echo h($this->data['Project']['homepage']) ?></a></li>
<?php endif ?>
    <% if @subprojects.any? %>
  <li><?php __('Subprojects') ?>: <%= @subprojects.collect{|p| link_to(h(p.name), :action => 'show', :id => p)}.join(", ") %></li>
    <% end %>
  <?php if ($this->data['Parent']['id'] != null): ?>
  <li><?php __('Subproject of') ?>: <?php echo $html->link(h($this->data['Parent']['name']), array('/projects/show/'.$this->data['Parent']['id'])) ?></li>
  <?php endif ?>
	<% @project.custom_values.each do |custom_value| %>
	<% if !custom_value.value.empty? %>
	   <li><%= custom_value.custom_field.name%>: <%=h show_value(custom_value) %></li>
	<% end %>
	<% end %>
	</ul>	

  <% if User.current.allowed_to?(:view_issues, @project) %>
  <div class="box">    
    <h3 class="icon22 icon22-tracker"><?php __('Issue tracking') ?></h3>
    <ul>
    <% for tracker in @trackers %>    
      <li><%= link_to tracker.name, :controller => 'issues', :action => 'index', :project_id => @project, 
                                                :set_filter => 1, 
                                                "tracker_id" => tracker.id %>:
      <%= @open_issues_by_tracker[tracker] || 0 %> <%= lwr(:label_open_issues, @open_issues_by_tracker[tracker] || 0) %>
      <?php __("'on'") ?> <%= @total_issues_by_tracker[tracker] || 0 %></li>
    <% end %>
    </ul>
    <p><?php echo $html->link(__('View all issues', true), array('/issues/index/'.$this->data['Project']['id'].'?set_filter=1')) ?></p>
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
    <p><?php echo $html->link(__('View all news', true), array('/news/index/'.$this->data['Project']['id'])) ?></p>
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
    <p><?php echo $html->link(__('Details', true), array('/timelog/details/'.$this->data['Project']['id'])) ?> |
       <?php echo $html->link(__('Report', true), array('/timelog/report/'.$this->data['Project']['id'])) ?></p>
    <% end %>
<% end %>

<% content_for :header_tags do %>
<%= auto_discovery_link_tag(:atom, {:action => 'activity', :id => @project, :format => 'atom', :key => User.current.rss_key}) %>
<% end %>

<?php $candy->html_title(__('Overview', true)) ?>
