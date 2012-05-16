<?php
if( $currentuser[ 'admin' ] )
{
	$this->set( 'Sidebar', $this->element( 'admin/sidebar' ) );
}
?>

<h2><?php echo __('New user'); ?></h2>

<?php echo $this->Form->create('User', array('class'=>'tabular')); ?>
<?php echo $this->element('users/form'); ?>
<?php echo $this->Form->submit(__('Create')); ?>
<br />
<div class="box">
<p>
  <?php echo $this->Form->label('Send account information to the user', __('Send account information to the user')); ?>
   <?php echo $this->Form->input('Send account information to the user', array(
       'div' => false, 'label'=>false,
       'type' => 'checkbox',
       'options' => array(1),
       'checked' => 1,
     )
   ); ?>
</p>
</div>
<?php echo $this->Form->end(); ?>
