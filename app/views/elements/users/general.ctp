<?php echo $form->create('User', array('class' => 'tabular', 'action' => 'edit')); ?>
<?php echo $this->renderElement('users/form'); ?>
<?php echo $form->submit(__('Save', true)); ?>
<?php echo $form->end(); ?>
