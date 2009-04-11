<h2><?php __('label_user_new'); ?></h2>
<?php e($form->create('User')); ?>
<?php echo $this->renderElement('users/form'); ?>
<?php e($form->submit('button_create')); ?>
<?php echo $form->input('send_information',
  array(
    'type' => 'checkbox',
    'options' => array(1),
    'checked' => 1,
  )
); ?>
<?php e($form->end()); ?>
