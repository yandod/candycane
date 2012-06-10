<h3><?php echo $this->Candy->html_title(__('Administration')); ?></h3>

<p class="icon22 icon22-projects">
  <?php	echo $this->Html->link(__('Projects',TRUE), array('controller' => 'admin', 'action' => 'projects')); ?> | 
  <?php echo $this->Html->link(__('New', TRUE), array('controller' => 'projects', 'action' => 'add')); ?>
</p>

<p class="icon22 icon22-users">
  <?php echo $this->Html->link(__('Users', TRUE), array('controller' => 'users', 'action' => 'index')); ?> |
  <?php echo $this->Html->link(__('New', TRUE), array('controller' => 'users', 'action' => 'add')); ?>
</p>

<p class="icon22 icon22-role">
  <?php echo $this->Html->link(__('Roles and permissions', TRUE), array('controller' => 'roles', 'action' => 'index')); ?>
</p>

<p class="icon22 icon22-tracker">
  <?php echo $this->Html->link(__('Trackers', TRUE), array('controller' => 'trackers', 'action' => 'index')); ?>
</p>

<p class="icon22 icon22-statuses">
  <?php echo $this->Html->link(__('Issue statuses', TRUE), array('controller' => 'issue_statuses', 'action' => 'index')); ?>
</p>

<p class="icon22 icon22-sequence">
  <?php echo $this->Html->link(__('Workflow', TRUE), array('controller' => 'workflows', 'action' => 'edit')); ?>
</p>

<p class="icon22 icon22-workflow">
  <?php echo $this->Html->link(__('Custom fields', TRUE), array('controller' => 'custom_fields', 'action' => 'index')); ?>
</p>

<p class="icon22 icon22-options">
  <?php echo $this->Html->link(__('Enumerations', TRUE), array('controller' => 'enumerations', 'action' => 'index')); ?>
</p>

<p class="icon22 icon22-settings">
  <?php echo $this->Html->link(__('Settings', TRUE), array('controller' => 'settings')); ?>
</p>

<p class="icon22 icon22-plugin">
  <?php echo $this->Html->link(__('Plugins', TRUE), array('controller' => 'admin', 'action' => 'plugins')); ?>
</p>

<p class="icon22 icon22-info">
  <?php echo $this->Html->link(__('Information', TRUE), array('controller' => 'admin', 'action' => 'info')); ?>
</p>
