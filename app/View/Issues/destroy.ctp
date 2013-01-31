<h2><?php echo $this->Candy->html_title(__('confirmation')); ?></h2>

<?php echo $this->Form->create(array('url'=>array('action'=>'destroy'), 'id'=>'IssueDestroyForm')); ?>
  <div class="box">
  <?php foreach($issue_datas as $issue) : ?>
    <?php echo $this->Form->hidden('ids', array('name'=>'data[Issue][ids][]', 'value'=>$issue['Issue']['id'])); ?>
  <?php endforeach; ?>
    <p><strong><?php echo sprintf(__('%.02f hours were reported on the issues you are about to delete. What do you want to do ?'), $hours); ?></strong></p>
    <p>
      <?php echo $this->Form->input('todo', array('type'=>'radio', 'options'=>array('destroy'=>__('Delete reported hours')), 'div'=>false)); ?><br />
      <?php echo $this->Form->input('todo', array('type'=>'radio', 'options'=>array('nullify'=>__('Assign reported hours to the project')), 'div'=>false)); ?><br />
      <?php echo $this->Form->input('todo', array('type'=>'radio', 'options'=>array('reassign'=>__('Reassign reported hours to this issue:')), 'div'=>false, 'onchange'=>'if (this.checked) { $("IssueReassignToId").focus(); }')); ?>
      <?php echo $this->Form->input('reassign_to_id', array('size'=>6, 'onfocus'=>'$("IssueTodoReassign").checked=true;', 'label'=>false, 'div'=>false)); ?>
    </p>
  </div>
<?php echo $this->Form->end(__('Apply')); ?>
