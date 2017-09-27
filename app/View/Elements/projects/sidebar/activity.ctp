<?php echo $this->Form->create('Project', array('url'=>array('action'=>'activity', /*'project_id'=>$this->request->params['project_id']*/), 'type'=>'get')) ?>
<h3><?php echo __('Activity') ?></h3>

<ul>
	<?php 
		foreach ($activity_event_types as $t) {
			echo $this->Html->tag('li',
				$this->Form->checkbox("show_{$t}", array('value' => 1, 'checked' => in_array($t, $activity_scope) ? 'checked' : null))
				. $this->Html->link(__(Inflector::classify($t)), array(/*'project_id'=>$this->request->params['project_id'],*/ '?'=>array("show_{$t}"=>1)))
			);
		}; 
	?>
</ul>

<?php if (!empty($active_children)) : ?>
	<p>
		<?php echo $this->Form->checkbox('with_subprojects', array('value' => 1, 'checked' => $with_subprojects ? 'checked' : null)); ?>
		<?php echo __('Subprojects') ?>
	</p>
	<?php echo $this->Form->hidden('with_subprojects', array('value' => 0)); ?>
<?php endif; ?>

<?php if(!empty($param_user_id)) { echo $this->Form->hidden('user_id', array('value'=>$param_user_id)); } ?>

<p>
	<?php echo $this->Form->submit(__('Apply'), array('class' => 'button-small')) ?>
</p>

<?php echo $this->Form->end() ?>
