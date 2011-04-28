<h2><?php __('Custom query') ?></h2>

<?php echo $form->create('Query', array('url' => array('action'=>'edit'), 'onsubmit' => 'selectAllOptions("selected_columns");')); ?>
	<?php echo $this->renderElement('error_explanation'); ?>
	<?php echo $this->renderElement('queries/form', array('query' => $this->data)) ?>
	<?php echo $form->submit(__('Save', true)) ?>
<?php echo $form->end(); ?>
