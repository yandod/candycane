<!--
<h2><%=l(:label_report_plural)%></h2>
-->
<h2><?php echo h(__('Report')); ?></h2>

<div class="splitcontentleft">
<!--
<h3><%=l(:field_tracker)%>&nbsp;&nbsp;<%= link_to image_tag('zoom_in.png'), :detail => 'tracker' %></h3>
<%= render :partial => 'simple', :locals => { :data => @issues_by_tracker, :field_name => "tracker_id", :rows => @trackers } %>
-->
<h3><?php echo h(__('Tracker')); ?>&nbsp;&nbsp;<?php echo $this->Html->link($this->Html->image('zoom_in.png'), array($project['identifier'], '?' => 'detail=tracker'), array('escape' => false)); ?></h3>
<?php echo $this->element('reports/_simple', array('data' => $issues_by_tracker, 'field_name' => 'tracker_id', 'rows' => $trackers)); ?>
<br />
<!--
<h3><%=l(:field_priority)%>&nbsp;&nbsp;<%= link_to image_tag('zoom_in.png'), :detail => 'priority' %></h3>
<%= render :partial => 'simple', :locals => { :data => @issues_by_priority, :field_name => "priority_id", :rows => @priorities } %>
-->
<br />
<h3><?php echo h(__('Priority')); ?>&nbsp;&nbsp;<?php echo $this->Html->link($this->Html->image('zoom_in.png'), array($project['identifier'], '?' => 'detail=priority'), array('escape' => false)); ?></h3>
<?php echo $this->element('reports/_simple', array('data' => $issues_by_priority, 'field_name' => 'priority_id', 'rows' => $priorities)); ?>
<br />
<!--
<h3><%=l(:field_assigned_to)%>&nbsp;&nbsp;<%= link_to image_tag('zoom_in.png'), :detail => 'assigned_to' %></h3>
<%= render :partial => 'simple', :locals => { :data => @issues_by_assigned_to, :field_name => "assigned_to_id", :rows => @assignees } %>
-->
<h3><?php echo h(__('Assigned to')); ?>&nbsp;&nbsp;<?php echo $this->Html->link($this->Html->image('zoom_in.png'), array($project['identifier'], '?' => 'detail=assigned_to'), array('escape' => false)); ?></h3>
<?php echo $this->element('reports/_simple', array('data' => $issues_by_assigned_to, 'field_name' => 'assigned_to_id', 'rows' => $assignees)); ?>
<br />
<!--
<h3><%=l(:field_author)%>&nbsp;&nbsp;<%= link_to image_tag('zoom_in.png'), :detail => 'author' %></h3>
<%= render :partial => 'simple', :locals => { :data => @issues_by_author, :field_name => "author_id", :rows => @authors } %>
-->
<h3><?php echo h(__('Author')); ?>&nbsp;&nbsp;<?php echo $this->Html->link($this->Html->image('zoom_in.png'), array($project['identifier'], '?' => 'detail=author'), array('escape' => false)); ?></h3>
<?php echo $this->element('reports/_simple', array('data' => $issues_by_author, 'field_name' => 'author_id', 'rows' => $authors)); ?>
<br />
</div>

<div class="splitcontentright">
<!--
<h3><%=l(:field_version)%>&nbsp;&nbsp;<%= link_to image_tag('zoom_in.png'), :detail => 'version' %></h3>
<%= render :partial => 'simple', :locals => { :data => @issues_by_version, :field_name => "fixed_version_id", :rows => @versions } %>
-->
<h3><?php echo h(__('Version')); ?>&nbsp;&nbsp;<?php echo $this->Html->link($this->Html->image('zoom_in.png'), array($project['identifier'], '?' => 'detail=version'), array('escape' => false)); ?></h3>
<?php echo $this->element('reports/_simple', array('data' => $issues_by_version, 'field_name' => 'fixed_version_id', 'rows' => $versions)); ?>
<br />
<!--
<% if @project.children.any? %>
<h3><%=l(:field_subproject)%>&nbsp;&nbsp;<%= link_to image_tag('zoom_in.png'), :detail => 'subproject' %></h3>
<%= render :partial => 'simple', :locals => { :data => @issues_by_subproject, :field_name => "project_id", :rows => @subprojects } %>
<br />
<% end %>
-->
<?php if (!empty($subprojects)): ?>
<h3><?php echo h(__('Subproject')); ?>&nbsp;&nbsp;<?php echo $this->Html->link($this->Html->image('zoom_in.png'), array($project['identifier'], '?' => 'detail=subproject'), array('escape' => false)); ?></h3>
<?php echo $this->element('reports/_simple', array('data' => $issues_by_subproject, 'field_name' => 'project_id', 'rows' => $subprojects)); ?>
<br />
<?php endif; ?>
<!--
<h3><%=l(:field_category)%>&nbsp;&nbsp;<%= link_to image_tag('zoom_in.png'), :detail => 'category' %></h3>
<%= render :partial => 'simple', :locals => { :data => @issues_by_category, :field_name => "category_id", :rows => @categories } %>
-->
<h3><?php echo h(__('Category')); ?>&nbsp;&nbsp;<?php echo $this->Html->link($this->Html->image('zoom_in.png'), array($project['identifier'], '?' => 'detail=category'), array('escape' => false)); ?></h3>
<?php echo $this->element('reports/_simple', array('data' => $issues_by_category, 'field_name' => 'category_id', 'rows' => $categories)); ?>
<br />
</div>

