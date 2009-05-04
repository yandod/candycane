<?php echo $form->create('Setting',aa('action','edit','url',aa('?','tab=projects'))) ?>


<div class="box tabular settings">
<p><label><?php __('New projects are public by default') ?></label>
<?php echo $form->checkbox('default_projects_public', aa('checked', ($Settings->default_projects_public == '1'))); ?></p>

<p><label><?php __('Generate sequential project identifiers') ?></label>
<?php echo $form->checkbox('sequential_project_identifiers', aa('checked', ($Settings->sequential_project_identifiers == '1'))); ?></p>
</div>

<?php echo $form->submit(__('Save',true)) ?>
<?php echo $form->end(); ?>
