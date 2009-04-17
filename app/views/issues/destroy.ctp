<h2><?php $candy->html_title();__('confirmation'); ?></h2>

<?php echo $form->create(array('url'=>array('action'=>'destroy', 'id'=>$issue['Issue']['id']), 'id'=>'IssueDestroyForm')); ?>
  <div class="box">
    <p><strong><?php echo sprintf(__('%.02f hours were reported on the issues you are about to delete. What do you want to do ?', true), $hours); ?></strong></p>
    <p>
      <?php echo $form->input('todo', array('type'=>'radio', 'options'=>array('destroy'=>__('Delete reported hours',true)), 'div'=>false)); ?><br />
      <?php echo $form->input('todo', array('type'=>'radio', 'options'=>array('nullify'=>__('Assign reported hours to the project',true)), 'div'=>false)); ?><br />
      <?php echo $form->input('todo', array('type'=>'radio', 'options'=>array('reassign'=>__('Reassign reported hours to this issue:',true)), 'div'=>false, 'onchange'=>'if (this.checked) { $("IssueReassignToId").focus(); }')); ?>
      <?php echo $form->input('reassign_to_id', array('size'=>6, 'onfocus'=>'$("IssueTodoReassign").checked=true;', 'label'=>false, 'div'=>false)); ?>
    </p>
  </div>
<?php echo $form->end(__('Apply',true)); ?>
