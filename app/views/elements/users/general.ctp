<?php echo $form->create('User', array('class' => 'tabular', 'url' => '/users/edit')); ?>
<?php echo $this->renderElement('users/form'); ?>
<?php echo $form->submit(__('Save', true)); ?>
<?php echo $form->end(); ?>
