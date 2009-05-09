<h2><?php __('Confirmation') ?></h2>

<div class="box"><center>
<p><strong><?php echo h($main_project['Project']['name']) ?></strong><br /><?php __('Are you sure you want to delete this wiki and all its content ?') ?></p>

<?php echo $form->create('Wiki',aa('url',aa('controller','wikis','action','destroy','project_id',$main_project['Project']['identifier']))); ?>
<?php echo $form->hidden('confirm',aa('value',1)) ?>
<?php echo $form->submit(__('Delete',true),aa('div',false)); ?>
<?php echo $form->end(); ?>
</center></div>
