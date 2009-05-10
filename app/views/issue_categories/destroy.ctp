<h2><?__('Issue categories') ?>: <?php e(h($issue_category_data['IssueCategory']['name'])) ?></h2>

<?php echo $form->create('IssueCategory',aa('url',aa('controller','issue_categories','action','destroy','project_id',$main_project['Project']['identifier'],'id',$issue_category_data['IssueCategory']['id']))); ?>
<div class="box">
<p><strong><?php echo $candy->lwr_r('Some issues (%d) are assigned to this category. What do you want to do ?',$issue_count) ?></strong></p>
<p><label><?php echo $form->input('todo',aa('type','radio','options',array('nullfy'=>''),'label',false,'div',false,'value',false,'checked',true)); ?> <?php __('Remove category assignments') ?></label><br />
<?php
$issue_category_list = Set::combine($main_project['IssueCategory'],'{n}.id','{n}.name');
unset($issue_category_list[$issue_category_data['IssueCategory']['id']]);
?>
<?php if (count($issue_category_list) > 0) : ?>
<label><?php echo $form->input('todo',aa('type','radio','options',array('reassgin_to'=>''),'label',false,'div',false,'value',false)); ?> <?php __('Reassign issues to this category') ?></label>:
<?php echo $form->select('reassign_to_id',$issue_category_list,false,false,false) ?></p>
<?php endif; ?>
</div>

<?php echo $form->submit(__('Apply',true),aa('div',false)); ?> 
<?php echo $html->link(__('Cancel',true),aa('controller','projects','action','settings','project_id',$main_project['Project']['identifier'],'?','tab=categories')); ?>
<?php echo $form->end(); ?>
