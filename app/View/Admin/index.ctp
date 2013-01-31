<h2><?php echo $this->Candy->html_title(__('Administration')); ?></h2>

<?php if(isset($no_configuration_data)): ?>
<div class="nodata">
<% form_tag({:action => 'default_configuration'}) do %>
    <%= simple_format(l(:text_no_configuration_data)) %>
    <p><%= l(:field_language) %>:
    <%= select_tag 'lang', options_for_select(lang_options_for_select(false), current_language.to_s) %>
    <%= submit_tag l(:text_load_default_configuration) %></p>
<% end %>
</div>
<?php endif; ?>

<p class="icon22 icon22-projects">
  <?php	echo $this->Html->link(__('Projects',TRUE), array('controller' => 'admin', 'action' => 'projects')); ?> | 
  <?php echo $this->Html->link(__('New', TRUE), array('controller' => 'projects', 'action' => 'add')); ?>
</p>

<p class="icon22 icon22-users">
  <?php echo $this->Html->link(__('Users', TRUE), array('controller' => 'users')); ?> |
  <?php echo $this->Html->link(__('New', TRUE), array('controller' => 'users', 'action' => 'add')); ?>
</p>

<p class="icon22 icon22-role">
  <?php echo $this->Html->link(__('Roles and permissions', TRUE), array('controller' => 'roles')); ?>
</p>

<p class="icon22 icon22-tracker">
  <?php echo $this->Html->link(__('Trackers', TRUE), array('controller' => 'trackers')); ?> |
  <?php echo $this->Html->link(__('Issue statuses', TRUE), array('controller' => 'issue_statuses')); ?> |
  <?php echo $this->Html->link(__('Workflow', TRUE), array('controller' => 'workflows', 'action' => 'edit')); ?>
</p>

<p class="icon22 icon22-workflow">
  <?php echo $this->Html->link(__('Custom fields', TRUE), array('controller' => 'custom_fields')); ?>
</p>

<p class="icon22 icon22-options">
  <?php echo $this->Html->link(__('Enumerations', TRUE), array('controller' => 'enumerations')); ?>
</p>

<p class="icon22 icon22-settings">
  <?php echo $this->Html->link(__('Settings', TRUE), array('controller' => 'settings')); ?>
</p>
<!-- 
<% menu_items_for(:admin_menu) do |item, caption, url, selected| -%>
  <%= content_tag 'p', 
    link_to(h(caption), item.url, item.html_options),
    :class => ["icon22", "icon22-#{item.name}"].join(' ') %>
<% end -%>
-->
<p class="icon22 icon22-plugin">
  <?php echo $this->Html->link(__('Plugins', TRUE), array('controller' => 'admin', 'action' => 'plugins')); ?>
</p>

<p class="icon22 icon22-info">
  <?php echo $this->Html->link(__('Information', TRUE), array('controller' => 'admin', 'action' => 'info')); ?>
</p>
