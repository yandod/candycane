<?php echo $this->Form->create('Project',array(
	'url' => '/projects/edit/'.$main_project['Project']['identifier'].'?tab=info',
	'class' => 'tabular')) ?>
<?php echo $this->element('projects/form'); ?>
<?php echo $this->Form->submit(__('Save')) ?>
<?php echo $this->Form->end(); ?>
