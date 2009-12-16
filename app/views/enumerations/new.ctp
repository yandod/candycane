<h2><?php __($options[$opt]['label']) ?>: <?php __('New value') ?></h2>

<?php echo $form->create('Enumeration',array('url' => array('action'=>'add','opt'=>$opt),'class'=>'tabular')); ?>
  <?php echo $this->element('enumerations/_form') ?>
  <?php echo $form->submit(__('Create',true)) ?>
<?php echo $form->end(); ?>
