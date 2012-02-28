<h2><?php __('New user'); ?></h2>
<?php e($form->create('User', array('class'=>'tabular'))); ?>
<?php echo $this->renderElement('users/form'); ?>
<?php echo $form->submit(__('Create', true)); ?>
<br />
<div class="box">
<p>
  <?php echo $form->label('Send account information to the user', __('Send account information to the user', true)); ?>
   <?php echo $form->input('Send account information to the user', array(
       'div' => false, 'label'=>false,
       'type' => 'checkbox',
       'options' => array(1),
       'checked' => 1,
     )
   ); ?>
</p>
</div>
<?php e($form->end()); ?>
