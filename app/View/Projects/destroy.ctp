<h2><?php echo __('Confirmation') ?></h2>
<div class="warning">
<p><strong><?php echo h($this->request->data['Project']['name']) ?></strong><br />
<?php echo __('Are you sure you want to delete this project and related data ?') ?>

<?php if (!empty($subprojects)): ?>
	<?php $str = implode(',',Set::extract('/Project/name',$subprojects));?>
	<br /><strong><?php echo $this->Candy->lwr_r('Its subproject(%s)',$str)?></strong>
<?php endif; ?>
</p>
<p>
<?php echo $this->Form->create(
	'Project',
	array(
		'action'=>'destroy',
		'url' => array(
			'project_id' => $main_project['Project']['identifier']
		)
	)
); ?>
<label><?php echo $this->Form->input('confirm', array('type'=>'checkbox', 'value'=>1, 'label'=>__("Yes"))) ?></label>
<?php echo $this->Form->submit(__('Delete')) ?>
<?php echo $this->Form->end() ?>
</p>
</div>
