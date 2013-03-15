<?php
if( $currentuser[ 'admin' ] )
{
	$this->set( 'Sidebar', $this->element( 'admin/sidebar' ) );
}
?>

<div class="contextual">
<?php echo $this->Html->link(__('New project', TRUE), array('controller' => 'projects', 'action' => 'add'), array('class' => 'icon icon-add')); ?> 
</div>

<h2><?php echo $this->Candy->html_title(__('Projects'));  ?></h2>

<?php echo $this->Form->create(null, array('type' => 'get', 'url' => '/admin/projects')); ?>
<fieldset>
  <legend><?php echo __('Filters'); ?></legend>
  <?php echo $this->Form->input('status', array(
    'type' => 'select',
    'options' => $status_options,
    'selected' => array($status),
    'class' => 'small',
    'div' => false,
    'onchange' => 'this.form.submit(); return false;',
    'label' => __('Status', TRUE) . ' :',
    )
  ); ?>

  <?php echo $this->Form->input('name', array(
    'div' => FALSE,
    'size' => '30',
    'value' => $name,
    'label' => __('Project', TRUE) . ':' ));
  ?>
<?php echo $this->Form->submit(__('Apply',TRUE), array('type' => 'submit', 'div' => FALSE, 'class' => 'small')); ?>
</fieldset>
<?php echo $this->Form->end(); ?>

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

  <?php if (is_array($projects)) foreach ($projects as $project): ?>
  <tr class="<?php echo $this->Candy->cycle();?>">
     <td>
	 <?php echo (($project['Project']['status'] == PROJECT_STATUS_ACTIVE) ? ($this->Html->link($project['Project']['name'], array('controller' => 'projects', 'action' => 'settings', 'project_id' => $project['Project']['identifier']))) : h($project['Project']['name'])); ?>
	 <td><?php echo nl2br($project['Project']['short_description']); ?></td>
	 <td align="center"> </td>
	 <td align="center"><?php if ($project['Project']['is_public'] == TRUE) { echo $this->Html->image('true.png'); } ?></td>
	 <td align="center"><?php echo $this->Candy->format_time($project['Project']['created_on']); ?></td>
	 <td align="center" style="width:10%">
	 <small>
	 <?php
	 if ($project['Project']['status'] == 1 /* (project.parent.nil? || project.parent.active?) */) { 
	   echo $this->Html->link(__('Archive', TRUE),
	     array('controller' => 'projects', 'action' => 'archive', 
	     'project_id' => $project['Project']['identifier']),array('class' => 'icon icon-lock'));
     } else {
	   echo $this->Html->link(__('Unarchive', TRUE), 
	     array('controller' => 'projects', 'action' => 'unarchive', 
	     'project_id' => $project['Project']['identifier']),array('class' => 'icon icon-unlock'));
     }
	 ?>
    </small>
	 </td>
	 <td align="center" style="width:10%">
	<?php if ($project['Project']['status'] == Project::STATUS_ARCHIVED): ?>
	 <small><?php echo $this->Html->link(__('Delete', TRUE), array('controller' => 'projects', 'action' => 'destroy', 'project_id' => $project['Project']['identifier']),array('class' => 'icon icon-del')); ?></small>
	<?php endif; ?> 
  </td>
  </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php // @todo paginate ?>
<p class="pagination"><!-- <%= pagination_links_full @project_pages, @project_count %>--></p>

