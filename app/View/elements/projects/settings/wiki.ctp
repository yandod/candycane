<?php echo $this->element('error_explanation'); ?>
  <?php echo $this->AppAjax->form(
    array('options' =>array(
      'model' => 'Wiki',
      'update' => 'tab-content-wiki',
      'url' => array(
        'controller' => 'wikis',
        'action' => 'edit',
        'project_id' => $main_project['Project']['identifier'],
        'id' => null
      )
    ))
  ) ?>
<div class="box tabular">
<p><?php echo $this->Form->text('Wiki.start_page',array(
	'size' => 60,
	'div' => false,
	'label' => false
)); ?><br />
<em><?php echo __('Unallowed characters') ?>: , . / ? ; : |</em></p>
</div>

<div class="contextual">
<?php
if ( !empty($main_project['Wiki'])) { 
  echo $this->Html->link(__('Delete'),array(
	'controller' => 'wikis',
	'action' => 'destroy',
	'project_id' => $main_project['Project']['identifier']
),
array(
	'class' => 'icon icon-del'
));
} ?>
</div>

<?php
 if ( !isset($main_project['Wiki']['id'])) {
   echo $this->Form->submit(__('Create'),array('div' => false));
 } else {
   echo $this->Form->submit(__('Save'),array('div' => false));
 }
?>
<?php echo $this->Form->end(); ?>
