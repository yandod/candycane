<h2><?php echo __('Enumerations') ?></h2>

<?php echo $this->Form->create('Enumeration',array('url' => array('action'=>'edit','id'=>$enumeration['Enumeration']['id']),'class'=>'tabular')); ?>
  <?php echo $this->element('enumerations/_form',aa('opt',$enumeration['Enumeration']['opt'])) ?>
  <?php echo $this->Form->submit(__('Save')) ?>
<?php echo $this->Form->end(); ?>

<?php 
echo $this->Form->create(null, array('url'=>array('action'=>'destroy', 'id'=>$enumeration['Enumeration']['id']), 'class'=>'button_to'));
echo $this->Form->submit(__('Delete'), array('onclick'=>'return confirm("'.__('Are you sure ?').'");', 'class'=>"button-small"));
echo $this->Form->end();
?>