<h2><?php echo __('Administration') ?></h2>

<%= render :partial => 'no_data' if @no_configuration_data %>

<p class="icon22 icon22-projects">
  <?php	echo $html->link(__('Projects',TRUE), array('controller' => 'admin', 'action' => 'projects')); ?> | 
  <?php echo $html->link(__('New', TRUE), array('controller' => 'projects', 'action' => 'add')); ?>
</p>

<p class="icon22 icon22-users">
  <?php echo $html->link(__('Users', TRUE), array('controller' => 'users')); ?> |
  <?php echo $html->link(__('New', TRUE), array('controller' => 'users', 'action' => 'add')); ?>
</p>

<p class="icon22 icon22-role">
  <?php echo $html->link(__('Roles and permissions', TRUE), array('controller' => 'roles')); ?>
</p>

<p class="icon22 icon22-tracker">
  <?php echo $html->link(__('Trackers', TRUE), array('controller' => 'trackers')); ?> |
  <?php echo $html->link(__('Issue statuses', TRUE), array('controller' => 'issue_statuses')); ?> |
  <?php echo $html->link(__('Workflow', TRUE), array('controller' => 'workflows', 'action' => 'edit')); ?>
</p>

<p class="icon22 icon22-workflow">
  <?php echo $html->link(__('Custom fields', TRUE), array('controller' => 'custom_fields')); ?>
</p>

<p class="icon22 icon22-options">
  <?php echo $html->link(__('Enumerations', TRUE), array('controller' => 'enumerations')); ?>
</p>

<p class="icon22 icon22-settings">
  <?php echo $html->link(__('Settings', TRUE), array('controller' => 'settings')); ?>
</p>

<% menu_items_for(:admin_menu) do |item, caption, url, selected| -%>
  <%= content_tag 'p', 
    link_to(h(caption), item.url, item.html_options),
    :class => ["icon22", "icon22-#{item.name}"].join(' ') %>
<% end -%>

<p class="icon22 icon22-plugin">
  <?php echo $html->link(__('Plugins', TRUE), array('controller' => 'admin', 'action' => 'plugins')); ?>
</p>

<p class="icon22 icon22-info">
  <?php echo $html->link(__('Information', TRUE), array('controller' => 'admin', 'action' => 'info')); ?>
</p>

<?php 
  // CandyHelper に実装してみたけどうまく動いていない
  // echo $candy->html_title('Administration');
?>
<% html_title(l(:label_administration)) -%>
