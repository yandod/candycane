<div class="contextual">
<!--    <%= link_to(l(:label_project_new), {:controller => 'projects', :action => 'add'}, :class => 'icon icon-add') + ' |' if User.current.admin? %> -->
<!--    <%= link_to l(:label_issue_view_all), { :controller => 'issues' } %> | -->
<!--    <%= link_to l(:label_overall_activity), { :controller => 'projects', :action => 'activity' }%> -->

<?php echo $html->link(__('New project',TRUE),array('controller' => 'projects', 'action' => 'add'),array('class' => 'icon icon-add')); ?> |
	<?php echo $html->link(__('View all issues',TRUE), array('controller' => 'issues')); ?> | 
	<?php echo $html->link(__('Overall activity', TRUE), array('controller' => 'projects', 'action' => 'activity')); ?>
</div>

<h2><?php __('Projects'); ?></h2>

<!--
<% @project_tree.keys.sort.each do |project| %>
<h3><%= link_to h(project.name), {:action => 'show', :id => project}, :class => (User.current.member_of?(project) ? "icon icon-fav" : "") %></h3>
<%= textilizable(project.short_description, :project => project) %>

<% if @project_tree[project].any? %>
    <p><%= l(:label_subproject_plural) %>:
    <%= @project_tree[project].sort.collect {|subproject| 
       link_to(h(subproject.name), {:action => 'show', :id => subproject}, :class => (User.current.member_of?(subproject) ? "icon icon-fav" : ""))}.join(', ') %></p>
<% end %>
<% end %>
-->


<?php foreach($project_tree as $project): ?>
<h3><?php echo $html->link($project['name'], array('action' => 'show', 'id' => $project['identifier']), array('class' => 'icon icon-fav')); ?></h3>
<?php echo $candy->textilizable($project['short_description']); ?>
<?php if (!empty($sub_project_tree[ $project['id'] ])): ?>
  <p><?php __('Subprojects'); ?>:
  <?php
  foreach ($sub_project_tree[ $project['id'] ] as $key => $subproject) {
	  echo $html->link($subproject['name'], array('action' => 'show', 'id' => $subproject['identifier']), array('class' => 'icon icon-fav'));
  }
?></p>
<!-- <%= @project_tree[project].sort.collect {|subproject| 
       link_to(h(subproject.name), {:action => 'show', :id => subproject}, :class => (User.current.member_of?(subproject) ? "icon icon-fav" : ""))}.join(', ') %></p>
-->
<?php endif; ?>

<?php endforeach; ?>


<!-- <% if User.current.logged? %> -->
<p style="text-align:right;">
<span class="icon icon-fav"><?php __('My projects'); ?></span>
</p>
<!-- <% end %> -->

<p class="other-formats">
<?php __("'Also available in:'"); ?>
<span><!-- <%= link_to 'Atom', {:format => 'atom', :key => User.current.rss_key}, :class => 'feed' %> --></span>
</p>

<!-- <% html_title(l(:label_project_plural)) -%> -->
