<h2><?php __('Role'); ?>: <?php echo h($role['Role']['name']); ?></h2>

<?php echo $form->create('Role', array('action' => 'edit/' . $role['Role']['id'] ,'id' => 'role_form','class' => 'tabular')); ?>
<?php echo $this->renderElement('roles/form'); ?>

<?php echo $form->submit(__('Save', TRUE)); ?>
<?php echo $form->end(); ?>
