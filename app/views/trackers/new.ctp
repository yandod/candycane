<h2><?php __('New tracker'); ?></h2>

<?php echo $form->create(null, array('action'=>'add', 'class'=>"tabular")); ?>
  <div class="box">
    <p>
      <?php echo $form->label('name', __('Name', true).'<span class="required"> *</span>'); ?>
      <?php echo $form->input('name', array('div'=>false, 'label'=>false)); ?></p>
    <p>
      <?php echo $form->label('tracker_is_in_chlog', __('Issues displayed in changelog', true)); ?>
      <?php echo $form->input('tracker_is_in_chlog', array('type'=>'checkbox', 'label'=>false, 'div'=>false)); ?>
    </p>
    <p>
      <?php echo $form->label('tracker_is_in_roadmap', __('Issues displayed in roadmap', true)); ?>
      <?php echo $form->input('tracker_is_in_roadmap', array('type'=>'checkbox', 'label'=>false, 'div'=>false,'checked'=>true)); ?>
    </p>
    <p>
      <?php echo $form->label('copy_workflow_from', __('Copy workflow from', true)); ?>
      <?php echo $form->select('copy_workflow_from',$trackers); ?>
    </p>
  </div>
  <?php echo $form->submit(__('Create', true)); ?>
<?php echo $form->end(); ?>
