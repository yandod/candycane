<h2><?php echo __('New version') ?></h2>

<?php echo $this->Form->create(
	'Version',
	array(
		'url' => array(
			'controller' => 'projects',
    		'action' => 'add_version',
			'project_id' => $main_project['Project']['identifier']
			),
		'class' => 'tabular'
	)
) ?>

<div class="box">
<p>
  <?php echo $this->Form->label('name', __('Name')); ?>
  <?php echo $this->Form->input('name',array('div'=>false, 'label'=>false, 'size'=>60)) ?>
</p>
<p>
  <?php echo $this->Form->label('description', __('Description')); ?>
  <?php echo $this->Form->input('description',array('div'=>false, 'label'=>false, 'size'=>60)) ?>
</p>
<p>
  <?php echo $this->Form->label('wiki_page_title', __('Wiki page')); ?>
  <?php echo $this->Form->input('wiki_page_title', array('div'=>false, 'label'=>false, 'size'=>60)) ?>
</p>
<p>
  <?php echo $this->Form->label('effective_date', __('Date')); ?>
  <?php echo $this->Form->input('effective_date', array('div'=>false, 'label'=>false, 'size'=>10, 'type'=>'text')); ?>
<?php echo $this->Candy->calendar_for('VersionEffectiveDate'); ?>
</p>
</div>

<?php echo $this->Form->submit(__('Create')) ?>

<?php echo $this->Form->end() ?>


