<h2><?php echo __('New category')?></h2>

<?php echo $this->Form->create('IssueCategory', array('url'=>array('controller'=>'projects', 'project_id'=>$this->request->data['Project']['project_id']), 'action'=>'add_issue_category','class'=>'tabular')) ?>

<?php echo $this->Form->error('projects/add_issue_category') ?>

<div class="box">
<?php /*
<p><%= f.text_field :name, :size => 30, :required => true %></p>
<p><%= f.select :assigned_to_id, @project.users.collect{|u| [u.name, u.id]}, :include_blank => true %></p>
 */ ?>

<p><label for="IssueCategoryName"><?php echo __('Name') ?></label><?php echo $this->Form->input('name', array('size'=>30,'div'=>false,'label'=>false)) ?></p>
<p><label for="IssueCategoryAssignedToId"><?php echo __('Assigned to') ?></label><?php echo $this->Form->input('assigned_to_id', array('type'=>'select', 'options'=>$project_users,'div'=>false,'label'=>false)) ?></p>
</div>

<?php echo $this->Form->submit(__('Create')) ?>
<?php echo $this->Form->end() ?>

