<span id="attachments_fields">
<?php
echo $this->Form->input(
	'Attachments.attachments_file.1',
	array(
		'type' => 'file',
		'size' => 30,
		'div' => false,
		'label' => false
	)
);

echo $this->Form->input(
	'Attachments.attachments_description.1',
	array(
		'type' => 'text',
		'size' => 60,
		'div' => false,
		'label' => false,
		'required' => false
	)
); ?>
  <em><?php echo __('Optional description'); ?></em>
</span>
<br />
<small><?php echo $this->Html->link(__('Add another file'), '#', array('onclick'=>'addFileField(); return false;')); ?>
(<?php echo __('Maximum size'); ?>: <?php echo $this->Number->toReadableSize($Settings->attachment_max_size*1024); ?>)
</small>
