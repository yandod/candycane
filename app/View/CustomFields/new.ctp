<?php
if( $currentuser[ 'admin' ] )
{
	$this->set( 'Sidebar', $this->element( 'admin/sidebar' ) );
}
?>

<h2><?php echo __('New custom field'); ?> (<?php echo $this->CustomField->type_name($custom_field); ?>)</h2>

<?php echo $this->Form->create('CustomField', array('url' => array('action' => "add"), 'class'=>'tabular')); ?>
<?php echo $this->element('custom_fields/form'); ?>
<?php echo $this->Form->hidden('type'); ?>
<?php echo $this->Form->submit(__('Save')); ?>
<?php echo $this->Form->end(); ?>
