<ul>
<?php if ($issue): ?>
	<li><?php echo $this->Candy->context_menu_link( __('Edit'), 
		array('controller'=>'issues', 'action'=>'edit', $issue['Issue']['id']),
		array('class'=>'icon-edit', 'disabled'=>!$can['edit'])); ?></li>
	<li class="folder">			
		<a href="#" class="submenu" onclick="return false;"><?php echo __('Status') ?></a>
		<ul>
		<?php foreach($statuses as $s): ?>
		    <li><?php echo $this->Candy->context_menu_link( $s['Status']['name'], 
				array('controller'=>'issues', 'action'=>'edit', $issue['Issue']['id'], 
					'?'=>array('issue[status_id]' => $s['Status']['id'], 'back_to' => $back)),
		        array('method' => 'post','selected' => ($s['Status']['id'] == $issue['Issue']['status_id']), 'disabled' => !($can['update'] && array_key_exists($s['Status']['id'], $allowed_statuses)))); ?></li>
		<?php endforeach; ?>
		</ul>
	</li>
<?php else: ?>
	<li><?php echo $this->Candy->context_menu_link( __('Edit'), 
		array('controller' => 'issues', 'action' => 'bulk_edit', 
			'?'=>array('ids' => Set::extract("/Issue/id", $issue_list))),
	    array('class' => 'icon-edit', 'disabled' => !$can['edit'])); ?></li>
<?php endif; ?>

	<li class="folder">			
		<a href="#" class="submenu"><?php echo __('Priority'); ?></a>
		<ul>
		<?php foreach($priorities as $p): ?>
		    <li><?php echo $this->Candy->context_menu_link( $p['Priority']['name'], 
				array('controller' => 'issues', 'action' => 'bulk_edit', 
					'?'=>array('ids'=>Set::extract("/Issue/id", $issue_list), 'priority_id'=>$p['Priority']['id'], 'back_to'=>$back)),
		        array('method' => 'post', 'selected' => ($issue && $p['Priority']['id'] == $issue['Issue']['priority_id']), 'disabled' => !$can['edit'])); ?></li>
		<?php endforeach; ?>
		</ul>
	</li>
	<?php if (!empty($project) && !empty($project['Version'])): ?>
	<li class="folder">			
		<a href="#" class="submenu"><?php echo __('Target version'); ?></a>
		<ul>
		<?php foreach($project['Version'] as $v): ?>
		    <li><?php echo $this->Candy->context_menu_link ($v['name'], 
				array('controller' => 'issues', 'action' => 'bulk_edit', 
					'?'=>array('ids'=>Set::extract("/Issue/id", $issue_list), 'fixed_version_id'=>$v['id'], 'back_to'=>$back)),
		    array('method'=>'post', 'selected'=>($issue && $v['id'] == $issue['Issue']['fixed_version_id']), 'disabled'=>!$can['update']));?></li>
		<?php endforeach; ?>
		    <li><?php echo $this->Candy->context_menu_link (__('none'), 
					array('controller' => 'issues', 'action' => 'bulk_edit', 
						'?'=>array('ids' => Set::extract("/Issue/id", $issue_list), 'fixed_version_id' => 'none', 'back_to' => $back)),
		      array('method'=>'post', 'selected'=>($issue && empty($issue['Issue']['fixed_version_id'])), 'disabled'=>!$can['update'])); ?></li>
		</ul>
	</li>
	<?php endif; ?>
	<?php if (!empty($assignables)): ?>
	<li class="folder">			
		<a href="#" class="submenu"><?php echo __('Assigned to'); ?></a>
		<ul>
		<?php foreach($assignables as $id=>$name): ?>
		  <li><?php echo $this->Candy->context_menu_link ($name, 
				array('controller' => 'issues', 'action' => 'bulk_edit', 
					'?'=>array('ids'=>Set::extract("/Issue/id", $issue_list), 'assigned_to_id'=>$id, 'back_to' => $back)),
		    array('method'=>'post', 'selected' => ($issue && $id == $issue['Issue']['assigned_to_id']), 'disabled' => !$can['update'])); ?></li>
		<?php endforeach; ?>
		  <li><?php echo $this->Candy->context_menu_link (__('nobody'), 
				array('controller' => 'issues', 'action' => 'bulk_edit', 
					'?'=>array('ids'=>Set::extract("/Issue/id", $issue_list), 'assigned_to_id'=>'none', 'back_to'=>$back)),
		    array('method'=>'post', 'selected' => ($issue && $issue['Issue']['assigned_to_id'] == null), 'disabled'=>!$can['update'])); ?></li>
		</ul>
	</li>
	<?php endif; ?>
	<?php if (!empty($project) && !empty($project['IssueCategory'])): ?>
	<li class="folder">			
		<a href="#" class="submenu"><?php echo __('Category'); ?></a>
		<ul>
		<?php foreach($project['IssueCategory'] as $u): ?>
		  <li><?php echo $this->Candy->context_menu_link ($u['name'], 
				array('controller' => 'issues', 'action' => 'bulk_edit', 
					'?'=>array('ids'=>Set::extract("/Issue/id", $issue_list), 'category_id' => $u['id'], 'back_to' => $back)),
		    array('method'=>'post', 'selected' => ($issue && $u['id'] == $issue['Issue']['category_id']), 'disabled'=>!$can['update'])); ?></li>
		<?php endforeach; ?>
		   <li><?php echo $this->Candy->context_menu_link (__('none'), 
				array('controller' => 'issues', 'action' => 'bulk_edit', 
					'?'=>array('ids'=>Set::extract("/Issue/id", $issue_list), 'category_id' => 'none', 'back_to' => $back)),
		    array('method'=>'post', 'selected' => ($issue && $issue['Issue']['category_id']==null), 'disabled' => !$can['update'])); ?></li>
		</ul>
	</li>
	<?php endif; ?>
	<li class="folder">
		<a href="#" class="submenu"><?php echo __('% Done'); ?></a>
		<ul>
		<?php for($i = 0; $i <= 10; $i++): $p = $i * 10; ?>
		   <li><?php echo $this->Candy->context_menu_link ("$p%", 
				array('controller' => 'issues', 'action' => 'bulk_edit', 
					'?'=>array('ids'=>Set::extract("/Issue/id", $issue_list), 'done_ratio' => $p, 'back_to' => $back)),
		    array('method'=>'post', 'selected' => ($issue && $p == $issue['Issue']['done_ratio']), 'disabled' => !$can['edit'])); ?></li>
		<?php endfor; ?>
		</ul>
	</li>
	
<?php if ($issue): ?>
	<li><?php echo $this->Candy->context_menu_link (__('Copy'), 
		array('controller' => 'issues', 'action' => 'add', 'project_id' => $project['Project']['identifier'], 
			'?'=>array('copy_from' => $issue['Issue']['id'])),
	  array('class' => 'icon-copy', 'disabled' => !$can['copy'])); ?></li>
	<?php if ($can['log_time']): ?>
	<li><?php echo $this->Candy->context_menu_link (__('Log time'), 
		array('controller' => 'timelog', 'action' => 'edit', '?'=>array('issue_id' => $issue['Issue']['id'])),
	  array('class' => 'icon-time')); ?></li>
	<?php endif; ?>
<?php endif; ?>

  <li><?php echo $this->Candy->context_menu_link (__('Move'), 
		array('controller' => 'issues', 'action' => 'move', '?'=>array('ids'=>Set::extract("/Issue/id", $issue_list))),
	  array('class' => 'icon-move', 'disabled' => !$can['move']));  ?></li>
  <li><?php echo $this->Candy->context_menu_link (__('Delete'), 
		array('controller' => 'issues', 'action' => 'destroy', '?'=>array('ids'=>Set::extract("/Issue/id", $issue_list))), 
		array('method' => 'post', 'confirm' => __("'Are you sure you want to delete the selected issue(s) ?'"), 'class' => 'icon-del', 'disabled' => !$can['delete'])); ?></li>
</ul>
