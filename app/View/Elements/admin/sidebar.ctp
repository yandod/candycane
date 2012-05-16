<?php
/**
 Not use
    echo $this->Html->link(__('Projects',TRUE), array('controller' => 'admin', 'action' => 'projects'));
  for generate link, because on some pages (f.e. http://candycane.local/roles/edit/id:1) in menu link will be incorrect
*/
?>

<h3><?php echo $this->Candy->html_title(__('Administration')); ?></h3>

<p class="icon22 icon22-projects">
  <a href="/admin/projects" title="<?php echo __( 'Projects', true ); ?>"><?php echo __( 'Projects', true ); ?></a> |
  <a href="/projects/add" title="<?php echo __( 'New', true ); ?>"><?php echo __( 'New', true ); ?></a>
</p>

<p class="icon22 icon22-users">
  <a href="/users" title="<?php echo __( 'Users', true ); ?>"><?php echo __( 'Users', true ); ?></a> |
  <a href="/users/add" title="<?php echo __( 'New', true ); ?>"><?php echo __( 'New', true ); ?></a>
</p>

<p class="icon22 icon22-role">
  <a href="/roles" title="<?php echo __( 'Roles and permissions', true ); ?>"><?php echo __( 'Roles and permissions', true ); ?></a>
</p>

<p class="icon22 icon22-tracker">
  <a href="/trackers" title="<?php echo __( 'Trackers', true ); ?>"><?php echo __( 'Trackers', true ); ?></a>
</p>

<p class="icon22 icon22-statuses">
  <a href="/issue_statuses" title="<?php echo __( 'Issue statuses', true ); ?>"><?php echo __( 'Issue statuses', true ); ?></a>
</p>

<p class="icon22 icon22-sequence">
  <a href="/workflows/edit" title="<?php echo __( 'Workflow', true ); ?>"><?php echo __( 'Workflow', true ); ?></a>
</p>

<p class="icon22 icon22-workflow">
  <a href="/custom_fields" title="<?php echo __( 'Custom fields', true ); ?>"><?php echo __( 'Custom fields', true ); ?></a>
</p>

<p class="icon22 icon22-options">
  <a href="/enumerations" title="<?php echo __( 'Enumerations', true ); ?>"><?php echo __( 'Enumerations', true ); ?></a>
</p>

<p class="icon22 icon22-settings">
  <a href="/settings" title="<?php echo __( 'Settings', true ); ?>"><?php echo __( 'Settings', true ); ?></a>
</p>

<p class="icon22 icon22-plugin">
  <a href="/admin/plugins" title="<?php echo __( 'Plugins', true ); ?>"><?php echo __( 'Plugins', true ); ?></a>
</p>

<p class="icon22 icon22-info">
  <a href="/admin/info" title="<?php echo __( 'Information', true ); ?>"><?php echo __( 'Information', true ); ?></a>
</p>