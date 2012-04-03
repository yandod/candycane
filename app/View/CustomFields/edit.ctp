<h2><?php echo __('Custom fields'); ?> (<?php echo $this->CustomField->type_name($custom_field); ?>)</h2>

<?php echo $this->Form->create('CustomField', array('url' => array('action' => "edit", 'id' => $custom_field['CustomField']['id']), 'class'=>'tabular')); ?>
<?php echo $this->renderElement('custom_fields/form'); ?>
<?php echo $this->Form->submit(__('Save')); ?>
<?php echo $this->Form->end(); ?>
