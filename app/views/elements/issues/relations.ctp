<div class="contextual">
<?php 
if($candy->authorize_for(array('controller'=>'issue_relations', 'action'=>'new'))) {
  echo $candy->toggle_link(__('Add',true), 'new-relation-form');
}
?>
</div>

<p><strong><?php __('Related issues')?></strong></p>

<?php if(!empty($issueRelations)): ?>
<table style="width:100%">
<?php foreach($issueRelations as $relation): ?>
<tr>
<td>
  <?php echo $issues->relation_label_for($issue, $relation); ?>
  <?php echo $issues->relation_delay_day($relation);  ?>
  <?php
  $other_issue = $issues->relation_other_issue($issue, $relation);
  if($Settings->cross_project_issue_relations) {
    echo h($other_issue['Project']['name']).' - ';
  }
  echo $candy->link_to_issue($other_issue);
  ?>
</td>
<td><?php echo h($other_issue['Issue']['subject']); ?></td>
<td><?php echo $other_issue['Status']['name']; ?></td>
<td><?php echo $candy->format_date($other_issue['Issue']['start_date']); ?></td>
<td><?php echo $candy->format_date($other_issue['Issue']['due_date']); ?></td>
<td>
  <?php if($candy->authorize_for(array('controller'=>'issue_relations', 'action'=>'destroy'))) {
    echo $ajax->link($html->image('delete.png'), 
      array('controller'=>'issue_relations', 'action'=>'destroy', 'issue_id'=>$issue['Issue']['id'], 'id'=>$relation['IssueRelation']['id']),
      array('method'=>'post', 'title'=> __('Delete relation',true), 'update'=>'relations'), null, false);
  }?>
</td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>
<?php
$url = array('controller'=>'issue_relations', 'action'=>'add', 'id'=>$issue['Issue']['id']);
echo $form->create('IssueRelation', array(
        'id'=>"new-relation-form", 'url'=>$url,
        'onsubmit'=>$ajax->remoteFunction(array('url'=>$url, 'form'=>true, 'after'=>'return false', 'update'=>'relations')),
        'style'=> empty($this->validationErrors) ? 'display: none;' : ''
      )
    );
?>
<?php echo $this->renderElement('error_explanation'); ?>
<p>
  <?php echo $form->input('relation_type', array('type'=>'select', 'options'=>$issues->relation_type_select(), 'onchange'=>"setPredecessorFieldsVisibility();", 'id'=>'relation_relation_type', 'div'=>false, 'label'=>false)); ?>
  <?php __('Issue') ?> #
  <?php echo $form->input('issue_to_id', array('type'=>'text', 'size'=>6, 'div'=>false, 'label'=>false)); ?>
  <span id="predecessor_fields" style="display:none;">
    <?php __('Delay'); ?>: <?php echo $form->input('delay', array('size'=>3, 'div'=>false, 'label'=>false)); ?> <?php __('days'); ?>
  </span>
  <?php echo $form->submit(__('Add',true), array('div'=>false)); ?>
  <?php echo $candy->toggle_link(__('Cancel',true), 'new-relation-form'); ?>
</p>
<?php echo $form->end(); ?>
<?php echo $javascript->codeBlock("setPredecessorFieldsVisibility();"); ?>
