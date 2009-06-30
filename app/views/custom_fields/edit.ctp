<h2><?php __('Custom fields'); ?> (<?php echo $customField->type_name($custom_field); ?>)</h2>

<?php echo $form->create('CustomField', array('url' => array('action' => "edit", 'id' => $custom_field['CustomField']['id']), 'class'=>'tabular')); ?>
<?php echo $this->renderElement('custom_fields/form'); ?>
<?php echo $form->submit(__('Save',true)); ?>
<?php echo $form->end(); ?>
