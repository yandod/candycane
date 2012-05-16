<?php
if( $currentuser[ 'admin' ] )
{
	$this->set( 'Sidebar', $this->element( 'admin/sidebar' ) );
}
?>

<h2><?php echo __('Tracker'); ?></h2>

<?php echo $this->Form->create(null, array('action'=>'edit', 'class'=>"tabular")); ?>
  <div class="box">
    <p>
      <?php echo $this->Form->label('name', __('Name').'<span class="required"> *</span>'); ?>
      <?php echo $this->Form->input('name', array('div'=>false, 'label'=>false)); ?></p>
    <p>
      <?php echo $this->Form->label('tracker_is_in_chlog', __('Issues displayed in changelog')); ?>
      <?php echo $this->Form->input('tracker_is_in_chlog', array('type'=>'checkbox', 'label'=>false, 'div'=>false)); ?>
    </p>
    <p>
      <?php echo $this->Form->label('tracker_is_in_roadmap', __('Issues displayed in roadmap')); ?>
      <?php echo $this->Form->input('tracker_is_in_roadmap', array('type'=>'checkbox', 'label'=>false, 'div'=>false,'checked'=>true)); ?>
    </p>
  </div>
  <?php echo $this->Form->submit(__('Save')); ?><?php echo $this->Form->hidden('id'); ?>
<?php echo $this->Form->end(); ?>
