<h2><?php echo __('Confirmation') ?></h2>

<div class="box"><center>
<p><strong><?php echo h($main_project['Project']['name']) ?></strong><br /><?php echo __('Are you sure you want to delete this wiki and all its content ?') ?></p>

<?php echo $this->Form->create('Wiki',aa('url',aa('controller','wikis','action','destroy','project_id',$main_project['Project']['identifier']))); ?>
<?php echo $this->Form->hidden('confirm',aa('value',1)) ?>
<?php echo $this->Form->submit(__('Delete'),aa('div',false)); ?>
<?php echo $this->Form->end(); ?>
</center></div>
