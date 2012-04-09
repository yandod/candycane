<?php echo $this->Form->create('Setting',aa('action','edit','url',aa('?','tab=projects'))) ?>


<div class="box tabular settings">
<p><label><?php echo __('New projects are public by default') ?></label>
<?php echo $this->Form->checkbox('default_projects_public', aa('checked', ($Settings->default_projects_public == '1'))); ?></p>

<p><label><?php echo __('Generate sequential project identifiers') ?></label>
<?php echo $this->Form->checkbox('sequential_project_identifiers', aa('checked', ($Settings->sequential_project_identifiers == '1'))); ?></p>
</div>

<?php echo $this->Form->submit(__('Save')) ?>
<?php echo $this->Form->end(); ?>
