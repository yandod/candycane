<?php echo $this->element('error_explanation'); ?>
  <?php echo $this->Form->create(
	'Wiki',
    	array(
      		'url' => array(
        		'controller' => 'wikis',
        		'action' => 'edit',
        		'project_id' => $main_project['Project']['identifier'],
        		//'id' => null
      		),
		'id' => 'WikiMainPage'
    ));
	echo $this->Html->scriptBlock(
		$this->Js->get('#WikiMainPage')->event('submit',
			$this->Js->request(array(
				'controller' => 'wikis',
				'action' => 'edit',
				'project_id' => $main_project['Project']['identifier']
			), array(
				'data' => $this->Js->serializeForm(array(
					'isForm' => true,
					'inline' => true
				)
				),
				'dataExpression' => true
			)
			),
			array(
				'update' => 'tab-content-wiki',
				'buffer' => false
			)
		)
	);
   ?>
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
