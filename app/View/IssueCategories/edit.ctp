<h2><?php echo $this->Candy->html_title(__('Issue category'))?></h2>

<?php echo $this->Form->create('IssueCategory', array('url'=>array('controller'=>'issue_categories','action'=>'edit', 'project_id'=>$main_project['Project']['identifier'],'id'=>$issue_category_data['IssueCategory']['id']), 'class'=>'tabular')) ?>
<?php echo $this->Form->error('projects/add_issue_category') ?>
<?php
$users_list = array();
foreach($main_project['User'] as $member) {
  $users_list[$member['id']] = $this->Candy->format_username($member);
}
?>
<div class="box">
<p><label for="IssueCategoryName"><?php echo __('Name') ?></label><?php echo $this->Form->input('IssueCategory.name', array('size'=>30,'div'=>false,'label'=>false,'value'=>$issue_category_data['IssueCategory']['name'])) ?></p>
<p><label for="IssueCategoryAssignedToId"><?php echo __('Assigned to') ?></label><?php echo $this->Form->select('IssueCategory.assigned_to_id', $users_list, array('div'=>false,'label'=>false, 'value' => $issue_category_data['IssueCategory']['assigned_to_id'] )) ?></p>
</div>

<?php echo $this->Form->submit(__('Save')) ?>
<?php echo $this->Form->end() ?>
