<?php echo $form->create('Project',aa('url','/projects/edit/'.$main_project['Project']['identifier'].'?tab=info','class','tabular')) ?>
<?php echo $this->renderElement('projects/form'); ?>
<?php echo $form->submit(__('Save',true)) ?>
<?php echo $form->end(); ?>
