<?php
if( $currentuser[ 'admin' ] )
{
	$this->set( 'Sidebar', $this->element( 'admin/sidebar' ) );
}
?>

<h2><?php echo __('Custom fields'); ?> (<?php echo $this->CustomField->type_name($custom_field); ?>)</h2>

<?php echo $this->Form->create('CustomField', array('url' => array('action' => 'edit'), 'class'=>'tabular')); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden', 'value' => $custom_field['CustomField']['id'])); ?>
<?php echo $this->element('custom_fields/form'); ?>
<?php echo $this->Form->submit(__('Save')); ?>
<?php echo $this->Form->end(); ?>
