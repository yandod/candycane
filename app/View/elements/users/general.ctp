<?php echo $this->Form->create('User', array('class' => 'tabular', 'action' => 'edit')); ?>
<?php echo $this->element('users/form'); ?>
<?php echo $this->Form->submit(__('Save')); ?>
<?php echo $this->Form->end(); ?>
