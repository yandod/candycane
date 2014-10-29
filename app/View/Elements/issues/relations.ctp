<div class="contextual">
<?php 
if($this->Candy->authorize_for(array('controller'=>'issue_relations', 'action'=>'new'))) {
  echo $this->Candy->toggle_link(__('Add'), 'new-relation-form');
}
?>
</div>

<p><strong><?php echo __('Related issues')?></strong></p>
<?php if(!empty($issue_relations)): ?>
<table style="width:100%">
<?php foreach($issue_relations as $relation): ?>
<tr>
<td>
  <?php echo $this->Issues->relation_label_for($issue, $relation); ?>
  <?php echo $this->Issues->relation_delay_day($relation);  ?>
  <?php
  $other_issue = $this->Issues->relation_other_issue($issue, $relation);
  if($Settings->cross_project_issue_relations) {
    echo h($other_issue['Project']['name']).' - ';
  }
  echo $this->Candy->link_to_issue($other_issue);
  ?>
</td>
<td><?php echo h($other_issue['Issue']['subject']); ?></td>
<td><?php echo $other_issue['Status']['name']; ?></td>
<td><?php echo $this->Candy->format_date($other_issue['Issue']['start_date']); ?></td>
<td><?php echo $this->Candy->format_date($other_issue['Issue']['due_date']); ?></td>
<td>
  <?php if($this->Candy->authorize_for(array('controller'=>'issue_relations', 'action'=>'destroy'))) {
    echo $this->Js->link($this->Html->image('delete.png'), 
      array('controller'=>'issue_relations', 'action'=>'destroy', 'issue_id'=>$issue['Issue']['id'], $relation['IssueRelation']['id']),
      array('method'=>'post', 'title'=> __('Delete relation'), 'update'=>'relations', 'escape' => false));
  }?>
</td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>
<?php
$url = array('controller'=>'issue_relations', 'action'=>'add', $issue['Issue']['id']);
$data = $this->Js->get("#new-relation-form")->serializeForm(array('isForm' => true, 'inline' => true ));
echo $this->Form->create('IssueRelation', array(
        'id'=>"new-relation-form", 'url'=>$url,
        'onsubmit'=> array($this->Js->request($url, array(
		'evalScripts' => true, 
		'dataExpression' => true,
		'data' => $data,
		'update'=>'relations'
		)),
	 'return false;'),
        'style'=> empty($this->validationErrors['IssueRelation']) ? 'display: none;' : ''
      )
    );
?>
<?php echo $this->element('error_explanation'); ?>
<p>
  <?php echo $this->Form->input('relation_type', array('type'=>'select', 'options'=>$this->Issues->relation_type_select(), 'onchange'=>"setPredecessorFieldsVisibility();", 'id'=>'relation_relation_type', 'div'=>false, 'label'=>false)); ?>
  <?php echo __('Issue') ?> #
  <?php echo $this->Form->input('issue_to_id', array('type'=>'text', 'size'=>6, 'div'=>false, 'label'=>false, 'error' => false)); ?>
  <span id="predecessor_fields" style="display:none;">
    <?php echo __('Delay'); ?>: <?php echo $this->Form->input('delay', array('size'=>3, 'div'=>false, 'label'=>false)); ?> <?php echo __('days'); ?>
  </span>
  <?php echo $this->Form->submit(__('Add'), array('div'=>false)); ?>
  <?php echo $this->Candy->toggle_link(__('Cancel'), 'new-relation-form'); ?>
</p>
<?php echo $this->Form->end(); ?>
<?php echo $this->Html->scriptBlock("setPredecessorFieldsVisibility();"); ?>
