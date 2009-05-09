<h2><?php __('New category')?></h2>

<?php echo $form->create('IssueCategory', array('url'=>array('controller'=>'projects', 'project_id'=>$this->data['Project']['project_id']), 'action'=>'add_issue_category','class'=>'tabular')) ?>

<?php echo $form->error('projects/add_issue_category') ?>

<div class="box">
<?php /*
<p><%= f.text_field :name, :size => 30, :required => true %></p>
<p><%= f.select :assigned_to_id, @project.users.collect{|u| [u.name, u.id]}, :include_blank => true %></p>
 */ ?>

<p><label for="IssueCategoryName"><?php __('Name') ?></label><?php echo $form->input('name', array('size'=>30,'div'=>false,'label'=>false)) ?></p>
<p><label for="IssueCategoryAssignedToId"><?php __('Assigned to') ?></label><?php echo $form->input('assigned_to_id', array('type'=>'select', 'options'=>$project_users,'div'=>false,'label'=>false)) ?></p>
</div>

<?php echo $form->submit(__('Create', true)) ?>
<?php echo $form->end() ?>

