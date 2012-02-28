<h2><?php __('New user'); ?></h2>
<?php e($form->create('User', array('class'=>'tabular'))); ?>
<?php echo $this->renderElement('users/form'); ?>
<?php echo $form->submit(__('Create', true)); ?>
<?php echo $form->input(__('Send account information to the user', true),
  array(
    'type' => 'checkbox',
    'options' => array(1),
    'checked' => 1,
  )
); ?>
<?php e($form->end()); ?>
