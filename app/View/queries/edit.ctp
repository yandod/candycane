<h2><?php echo __('Custom query') ?></h2>

<?php echo $this->Form->create('Query', array('url' => array('action'=>'edit'), 'onsubmit' => 'selectAllOptions("selected_columns");')); ?>
	<?php echo $this->renderElement('error_explanation'); ?>
	<?php echo $this->renderElement('queries/form', array('query' => $this->request->data)) ?>
	<?php echo $this->Form->submit(__('Save')) ?>
<?php echo $this->Form->end(); ?>
