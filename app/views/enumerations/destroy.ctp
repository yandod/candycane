<h2><?php __($options[$enumeration['Enumeration']['opt']]['label']) ?>: <?php echo h($enumeration['Enumeration']['name']) ?></h2>

<?php echo $form->create(null,aa('url',aa('action','destroy','id',$enumeration['Enumeration']['id']))) ?>
<div class="box">
<p><strong><?php echo sprintf(__("'%d objects are assigned to this value.'",true),$objects_count) ?></strong></p>
<p><?php __("'Reassign them to this value:'") ?>
<?php //<%= select_tag 'reassign_to_id', ("<option>--- #{l(:actionview_instancetag_blank_option)} ---</option>" + options_from_collection_for_select(@enumerations, 'id', 'name')) %> ?>
<?php
$reoptions = Set::combine($enumerations,'{n}.Enumeration.id','{n}.Enumeration.name');
unset($reoptions[$enumeration['Enumeration']['id']]);
?>
<?php echo $form->select('reassign_to_id',$reoptions) ?>
</p>
</div>

<?php echo $form->submit(__('Apply',true)) ?>
<?php echo $html->link(__('Cancel',true),array('action'=>'index')) ?>
<?php echo $form->end() ?>
