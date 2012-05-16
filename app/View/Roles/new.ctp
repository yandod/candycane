<?php
if( $currentuser[ 'admin' ] )
{
	$this->set( 'Sidebar', $this->element( 'admin/sidebar' ) );
}
?>

<h2><?php echo __('New role'); ?></h2>

<!-- <% labelled_tabular_form_for :role, @role, :url => { :action => 'new' }, :html => {:id => 'role_form'} do |f| %> -->
<?php echo $this->Form->create('Role', array('action' => 'add','id' => 'role_form','class' => 'tabular')); ?>

<!-- <%= render :partial => 'form', :locals => { :f => f } %> -->

<?php echo $this->element('roles/form'); ?>

<?php echo $this->Form->submit(__('Save', TRUE)); ?>
<?php echo $this->Form->end(); ?>
