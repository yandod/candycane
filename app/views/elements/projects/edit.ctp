<?php echo $form->create('Project',aa('action','edit','url',aa('?','tab=info'),'class','tabular')) ?>
<?php echo $this->renderElement('projects/form'); ?>
<?php echo $form->submit(__('Save',true)) ?>
<?php echo $form->end(); ?>
