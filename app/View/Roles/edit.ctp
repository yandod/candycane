<?php
if( $currentuser[ 'admin' ] )
{
	$this->set( 'Sidebar', $this->element( 'admin/sidebar' ) );
}
?>

<h2><?php echo __('Role'); ?>: <?php echo h($role['Role']['name']); ?></h2>

<?php echo $this->Form->create('Role', array('action' => 'edit/' . $role['Role']['id'] ,'id' => 'role_form','class' => 'tabular')); ?>
<?php echo $this->element('roles/form'); ?>

<?php echo $this->Form->submit(__('Save', TRUE)); ?>
<?php echo $this->Form->end(); ?>
