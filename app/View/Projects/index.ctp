<?php
if ($currentuser['admin'])
	$this->set( 'Sidebar', $this->element('admin/sidebar'));
?>

<div class="contextual">
	<?php if ($currentuser['admin']) 
		echo $this->Html->link(__('New project',TRUE),array('controller' => 'projects', 'action' => 'add'),array('class' => 'icon icon-add'))." | "; 
	?>
	<?php echo $this->Html->link(__('View all issues',TRUE), array('controller' => 'issues')); ?> | 
	<?php echo $this->Html->link(__('Overall activity', TRUE), array('controller' => 'projects', 'action' => 'activity')); ?>
</div>

<h2><?php echo $this->Candy->html_title(__('Projects')); ?></h2>

<?php foreach($project_tree as $project): ?>
	<h3>
		<?php echo $this->Html->link($project['name'], array('action' => 'show', 'project_id' => $project['identifier']), array('class' => 'icon icon-fav')); ?>
	</h3>
	<?php echo $this->Candy->textilizable($project['short_description']); ?>
	<?php 
		if (!empty($sub_project_tree[ $project['id'] ])):
			if (isset($sub_project_tree[$project['id']])): ?>
  				<p>
  				<?php 
  					echo __('Subprojects'); ?> :
				<?php
					foreach ($sub_project_tree[ $project['id'] ] as $key => $subproject) {
				  		echo $this->Html->link($subproject['name'], array('action' => 'show', 'project_id' => $subproject['identifier']), array('class' => 'icon icon-fav'));
					}
				?>
				</p>
  		<?php 
  			endif;
		endif;
	endforeach; ?>

	<p style="text-align:right;"><span class="icon icon-fav"><?php echo __('My projects'); ?></span></p>
	<p class="other-formats"><?php echo __('Also available in:'); ?><span></span>
</p>