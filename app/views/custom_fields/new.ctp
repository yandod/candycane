<h2><?php __('New custom field'); ?> (<?php echo $customField->type_name($custom_field); ?>)</h2>

<?php echo $form->create('CustomField', array('url' => array('action' => "add"), 'class'=>'tabular')); ?>
<?php echo $this->renderElement('custom_fields/form'); ?>
<?php echo $form->hidden('type'); ?>
<?php echo $form->submit(__('Save',true)); ?>
<?php echo $form->end(); ?>
