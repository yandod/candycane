<h2><?php __('Issue category')?></h2>

<?php echo $form->create('IssueCategory', array('url'=>array('controller'=>'issue_categories','action'=>'edit', 'project_id'=>$main_project['Project']['identifier'],'id'=>$issue_category_data['IssueCategory']['id']), 'class'=>'tabular')) ?>
<?php echo $form->error('projects/add_issue_category') ?>
<?php
$users_list = array();
foreach($main_project['User'] as $member) {
  $users_list[$member['id']] = $candy->format_username($member);
}
?>
<div class="box">
<p><label for="IssueCategoryName"><?php __('Name') ?></label><?php echo $form->input('IssueCategory.name', array('size'=>30,'div'=>false,'label'=>false,'value'=>$issue_category_data['IssueCategory']['name'])) ?></p>
<p><label for="IssueCategoryAssignedToId"><?php __('Assigned to') ?></label><?php echo $form->select('IssueCategory.assigned_to_id', $users_list, $issue_category_data['IssueCategory']['assigned_to_id'], array('options'=>$users_list,'div'=>false,'label'=>false),true) ?></p>
</div>

<?php echo $form->submit(__('Save', true)) ?>
<?php echo $form->end() ?>
