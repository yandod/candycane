<div class="contextual">
<?php echo $html->link(__('New project', TRUE), array('controller' => 'projects', 'action' => 'add'), array('class' => 'icon icon-add')); ?> 
</div>

<h2><?php __('Projects'); ?></h2>

<?php echo $form->create('Project', array('type' => 'get')); ?>
<fieldset><legend><?php __('Filters'); ?></legend>
<?php echo $form->input('status', array('div' => FALSE, 'options' => $status, 'class' => 'small', 'onchange' => 'this.form.submit(); return false;', 'label' => __('Status', TRUE) . ' :' )); ?>
<?php echo $form->input('name', array('div' => FALSE, 'size' => '30', 'label' => __('Project', TRUE) . ':' )); ?>
<?php echo $form->submit(__('Apply',TRUE), array('type' => 'submit', 'div' => FALSE, 'class' => 'small')); ?>
</fieldset>
<?php echo $form->end(); ?>

<!-- 
<% form_tag({}, :method => :get) do %>
<fieldset><legend><%= l(:label_filter_plural) %></legend>
<label><%= l(:field_status) %> :</label>
<%= select_tag 'status', project_status_options_for_select(@status), :class => "small", :onchange => "this.form.submit(); return false;"  %>
<label><%= l(:label_project) %>:</label>
<%= text_field_tag 'name', params[:name], :size => 30 %>
<%= submit_tag l(:button_apply), :class => "small", :name => nil %>
</fieldset>
<% end %>
-->

&nbsp;

<table class="list">
  <thead><tr>
	<!-- <%= sort_header_tag('name', :caption => l(:label_project)) %> -->
    <th><?php echo h(__('Project', TRUE)); ?></th>
	<th><?php echo h(__('Description',TRUE)); ?></th>
	<th><?php echo h(__('Subprojects', TRUE)); ?></th>
<!--	<%= sort_header_tag('is_public', :caption => l(:field_is_public), :default_order => 'desc') %>
	<%= sort_header_tag('created_on', :caption => l(:field_created_on), :default_order => 'desc') %> -->
	 <th><?php echo h(__('Public', TRUE)); ?></th>
	 <th><?php echo h(__('Created', TRUE)); ?></th>
    <th></th>
    <th></th>
  </tr></thead>
  <tbody>
<!--
<% for project in @projects %>
  <tr class="<%= cycle("odd", "even") %>">
	<td><%= project.active? ? link_to(h(project.name), :controller => 'projects', :action => 'settings', :id => project) : h(project.name) %>
	<td><%= textilizable project.short_description, :project => project %>
	<td align="center"><%= project.children.size %>
	<td align="center"><%= image_tag 'true.png' if project.is_public? %>
	<td align="center"><%= format_date(project.created_on) %>
  <td align="center" style="width:10%">
    <small>
    <%= link_to(l(:button_archive), { :controller => 'projects', :action => 'archive', :id => project }, :confirm => l(:text_are_you_sure), :method => :post, :class => 'icon icon-lock') if project.active? %>
    <%= link_to(l(:button_unarchive), { :controller => 'projects', :action => 'unarchive', :id => project }, :method => :post, :class => 'icon icon-unlock') if !project.active? && (project.parent.nil? || project.parent.active?) %>
    </small>
  </td>
  <td align="center" style="width:10%">
    <small><%= link_to(l(:button_delete), { :controller => 'projects', :action => 'destroy', :id => project }, :class => 'icon icon-del') %></small>
  </td>
  </tr>
<% end %>
-->

  <?php foreach ($projects as $project): ?>
	 <tr class="odd">
     <td>
	 <?php echo (($project['active'] == TRUE) ? ($html->link(h($project['name']), array('controller' => 'projects', 'action' => 'settings', 'id' => 'project'))) : h($project['name'])); ?>
	 <td><?php echo $project['short_description']; ?></td>
	 <td align="center">project.children.size
	 <td align="center"><?php if ($project['is_public'] == TRUE) { echo $html->image('true.png'); } ?>
	 <td align="center"><?php echo $project['created_on']; ?>
	 <td align="center" style="width:10%">
	 <small>
	 <?php echo $html->link(__('Archive', TRUE), array('controller' => 'projects', 'action' => 'archive', 'id' => $project['id'])); ?>
<!--
    <%= link_to(l(:button_archive), { :controller => 'projects', :action => 'archive', :id => project }, :confirm => l(:text_are_you_sure), :method => :post, :class => 'icon icon-lock') if project.active? %>
    <%= link_to(l(:button_unarchive), { :controller => 'projects', :action => 'unarchive', :id => project }, :method => :post, :class => 'icon icon-unlock') if !project.active? && (project.parent.nil? || project.parent.active?) %>
-->	 
    </small>
	 </td>
	 <td align="center" style="width:10%">
    <small><%= link_to(l(:button_delete), { :controller => 'projects', :action => 'destroy', :id => project }, :class => 'icon icon-del') %></small>
  </td>
  </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<p class="pagination"><%= pagination_links_full @project_pages, @project_count %></p>

<% html_title(l(:label_project_plural)) -%>
