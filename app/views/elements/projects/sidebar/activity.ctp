<?php echo $form->create('Project', array('url'=>array('action'=>'activity', 'project_id'=>$this->params['project_id']), 'type'=>'get')) ?>
<h3><?php __('Activity') ?></h3>
<p><?php foreach ($activity_event_types as $t) : ?>
  <?php echo $form->checkbox("show_{$t}", array('value'=>1, 'checked'=>in_array($t, $activity_scope)?'checked':null)); ?>
  <?php echo $html->link(__(Inflector::classify($t),true), array('project_id'=>$this->params['project_id'], '?'=>array("show_{$t}"=>1))); ?><br />
<?php endforeach; ?></p>
<?php if (!empty($active_children)) : ?>
<p>
  <?php echo $form->checkbox('with_subprojects', array('value'=>1, 'checked'=>$with_subprojects?'checked':null)); ?>
  <?php __('Subprojects') ?>
</p>
<?php echo $form->hidden('with_subprojects', array('value'=>0)); ?>
<?php endif; ?>
<?php if(!empty($param_user_id)) { echo $form->hidden('user_id', array('value'=>$param_user_id)); } ?>
<p><?php echo $form->submit(__('Apply', true), array('class'=>'button-small')) ?></p>
<?php echo $form->end() ?>
