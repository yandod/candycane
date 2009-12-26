<h2><?php __($options[$enumeration['Enumeration']['opt']]['label']) ?>: <?php echo h($enumeration['Enumeration']['name']) ?></h2>

<?php echo $form->create() ?>
<div class="box">
<p><strong><?php echo sprintf(__("'%d objects are assigned to this value.'",true),100) ?></strong></p>
<p><?php __("'Reassign them to this value:'") ?>
<%= select_tag 'reassign_to_id', ("<option>--- #{l(:actionview_instancetag_blank_option)} ---</option>" + options_from_collection_for_select(@enumerations, 'id', 'name')) %></p>
</div>

<?php echo $form->submit(__('Apply',true)) ?>
<?php echo $html->link(__('Cancel',true),array('action'=>'index')) ?>
<?php echo $form->end() ?>
