<h2><?__('Issue categories') ?>: <?php echo h($issue_category_data['IssueCategory']['name']) ?></h2>

<?php echo $this->Form->create(
	'IssueCategory',
	array(
		'url' => array(
			'controller' => 'issue_categories',
			'action' => 'destroy',
			'project_id' => $main_project['Project']['identifier'],
			$issue_category_data['IssueCategory']['id']
		)
	)
); ?>
<div class="box">
<p><strong><?php echo $this->Candy->lwr_r('Some issues (%d) are assigned to this category. What do you want to do ?',$issue_count) ?></strong></p>
<p><label><?php echo $this->Form->input(
	'todo',
	array(
		'type' => 'radio',
		'options' => array(
			'nullfy' => ''
		),
		'label' => false,
		'div' => false,
		'value' => false,
		'checked' => true
	)); ?> <?php echo __('Remove category assignments') ?></label><br />
<?php
$issue_category_list = Set::combine($main_project['IssueCategory'],'{n}.id','{n}.name');
unset($issue_category_list[$issue_category_data['IssueCategory']['id']]);
?>
<?php if (count($issue_category_list) > 0) : ?>
<label><?php echo $this->Form->input(
	'todo',
	array(
		'type' => 'radio',
		'options' => array(
			'reassgin_to' => ''
		),
		'label' => false,
		'div' => false,
		'value' => false
	)
); ?> <?php echo __('Reassign issues to this category') ?></label>:
<?php echo $this->Form->select('reassign_to_id',$issue_category_list,false,false,false) ?></p>
<?php endif; ?>
</div>

<?php echo $this->Form->submit(__('Apply'),array('div' => false)); ?> 
<?php echo $this->Html->link(
	__('Cancel'),
	array(
		'controller' => 'projects',
		'action' => 'settings',
		'project_id' => $main_project['Project']['identifier'],
		'?' => 'tab=categories'
	)
); ?>
<?php echo $this->Form->end(); ?>
