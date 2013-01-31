<?php
if( $currentuser[ 'admin' ] )
{
	$this->set( 'Sidebar', $this->element( 'admin/sidebar' ) );
}
?>

<h2><?php $this->Candy->html_title();__('Issue statuses'); ?></h2>

<?php echo $this->Form->create(null, array('action'=>'edit', 'class'=>"tabular")); ?>
  <div class="box">
    <p>
      <?php echo $this->Form->label('name', __('Name').'<span class="required"> *</span>'); ?>
      <?php echo $this->Form->input('name', array('div'=>false, 'label'=>false)); ?></p>
    <p>
      <?php echo $this->Form->label('is_closed', __('Issue closed')); ?>
      <?php echo $this->Form->input('is_closed', array('type'=>'checkbox', 'label'=>false, 'div'=>false)); ?>
    </p>
    <p>
      <?php echo $this->Form->label('is_default', __('Default value')); ?>
      <?php echo $this->Form->input('is_default', array('div'=>false, 'label'=>false)); ?>
    </p>
  </div>
  <?php echo $this->Form->submit(__('Save')); ?>
  <?php echo $this->Form->hidden('id'); ?></p>
<?php echo $this->Form->end(); ?>
