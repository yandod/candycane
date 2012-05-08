<h2><?php echo __($options[$enumeration['Enumeration']['opt']]['label']) ?>: <?php echo h($enumeration['Enumeration']['name']) ?></h2>

<?php echo $this->Form->create(
	null,
	array(
		'url' => array(
			'action' => 'destroy',
			$enumeration['Enumeration']['id']
		)
	)
) ?>
<div class="box">
<p><strong><?php echo sprintf(__("'%d objects are assigned to this value.'"),$objects_count) ?></strong></p>
<p><?php echo __("'Reassign them to this value:'") ?>
<?php //<%= select_tag 'reassign_to_id', ("<option>--- #{l(:actionview_instancetag_blank_option)} ---</option>" + options_from_collection_for_select(@enumerations, 'id', 'name')) %> ?>
<?php
$reoptions = Set::combine($enumerations,'{n}.Enumeration.id','{n}.Enumeration.name');
unset($reoptions[$enumeration['Enumeration']['id']]);
?>
<?php echo $this->Form->select('reassign_to_id',$reoptions) ?>
</p>
</div>

<?php echo $this->Form->submit(__('Apply')) ?>
<?php echo $this->Html->link(__('Cancel'),array('action'=>'index')) ?>
<?php echo $this->Form->end() ?>
