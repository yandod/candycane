<h2><?php __('New status'); ?></h2>

<?php echo $form->create(null, array('action'=>'add', 'class'=>"tabular")); ?>
  <div class="box">
    <p>
      <?php echo $form->label('name', __('Name', true).'<span class="required"> *</span>'); ?>
      <?php echo $form->input('name', array('div'=>false, 'label'=>false)); ?></p>
    <p>
      <?php echo $form->label('is_closed', __('Issue closed', true)); ?>
      <?php echo $form->input('is_closed', array('type'=>'checkbox', 'label'=>false, 'div'=>false)); ?>
    </p>
    <p>
      <?php echo $form->label('is_default', __('Default value', true)); ?>
      <?php echo $form->input('is_default', array('div'=>false, 'label'=>false)); ?>
    </p>
  </div>
  <?php echo $form->submit(__('Create', true)); ?>
<?php echo $form->end(); ?>
