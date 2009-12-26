<h2><?php __('Enumerations') ?></h2>

<?php echo $form->create('Enumeration',array('url' => array('action'=>'edit','id'=>$enumeration['Enumeration']['id']),'class'=>'tabular')); ?>
  <?php echo $this->element('enumerations/_form',aa('opt',$enumeration['Enumeration']['opt'])) ?>
  <?php echo $form->submit(__('Save',true)) ?>
<?php echo $form->end(); ?>

<?php 
echo $form->create(null, array('url'=>array('action'=>'destroy', 'id'=>$enumeration['Enumeration']['id']), 'class'=>'button_to'));
echo $form->submit(__('Delete',true), array('onclick'=>'return confirm("'.__('Are you sure ?',true).'");', 'class'=>"button-small"));
echo $form->end();
?>