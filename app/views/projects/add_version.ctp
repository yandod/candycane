<h2><?php __('New version') ?></h2>

<?php echo $form->create('Version', array('url'=>array('controller'=>'projects', 'id'=>$main_project['Project']['identifier']), 'action'=>'add_version','class'=>'tabular')) ?>

<div class="box">
<p>
  <?php echo $form->label('name', __('Name', true)); ?>
  <?php echo $form->input('name',array('div'=>false, 'label'=>false, 'size'=>60)) ?>
</p>
<p>
  <?php echo $form->label('description', __('Description', true)); ?>
  <?php echo $form->input('description',array('div'=>false, 'label'=>false, 'size'=>60)) ?>
</p>
<p>
  <?php echo $form->label('wiki_page_title', __('Wiki page', true)); ?>
  <?php echo $form->input('wiki_page_title', array('div'=>false, 'label'=>false, 'size'=>60)) ?>
</p>
<p>
  <?php echo $form->label('effective_date', __('Date', true)); ?>
  <?php echo $form->input('effective_date', array('div'=>false, 'label'=>false, 'size'=>10, 'type'=>'text')); ?>
<?php echo $candy->calendar_for('VersionEffectiveDate'); ?>
</p>
</div>

<?php echo $form->submit(__('Create', true)) ?>

<?php echo $form->end() ?>


