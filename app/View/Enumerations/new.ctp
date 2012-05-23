<?php
if( $currentuser[ 'admin' ] )
{
	$this->set( 'Sidebar', $this->element( 'admin/sidebar' ) );
}
?>

<h2><?php echo __($options[$opt]['label']) ?>: <?php echo __('New value') ?></h2>

<?php echo $this->Form->create('Enumeration',array('url' => array('action'=>'add','opt'=>$opt),'class'=>'tabular')); ?>
  <?php echo $this->element('enumerations/_form') ?>
  <?php echo $this->Form->submit(__('Create')) ?>
<?php echo $this->Form->end(); ?>
