 <?php 
$roles_list = array();
foreach ($roles_data as $roles_row) {
  $roles_list[$roles_row['Role']['id']] = $roles_row['Role']['name'];
}
?>
<?php if ( !empty($members) ): ?>
<table class="list">
	<thead>
	  <th><?php echo __('User') ?></th>
	  <th><?php echo __('Role') ?></th>
	  <th style="width:15%"></th>
<!--            <%= call_hook(:view_projects_settings_members_table_header, :project => @project) %> -->
	</thead>
	<tbody>
	<?php foreach ($members as $member_row): ?>
	<tr class="<?php echo $this->Candy->cycle() ?>">
	<td><?php echo $this->Candy->format_username($member_row['User']) ?></td>
    <td align="center">
    <?php if ($this->Candy->authorize_for(array('controller' => 'members','action' => 'edit'))): ?>
  <?php echo $this->Form->create('Member', array(
  		'url' => array(
			'controller' => 'members',
			'action' => 'edit',
			'project_id' => $main_project['Project']['identifier'],
			'id' => $member_row['Member']['id']
		)
	));?>
        <?php echo $this->Form->select(
			'Member.role_id',
			$roles_list,
			array(
				'class' => 'small',
				'value' => $member_row['Member']['role_id']
			)
		) ?>
	<?php echo $this->Js->submit(__('Change'),array('div' => false, 'class' => 'small', 'url' => 
    		array(
			'controller' => 'members',
			'action' => 'edit',
			'project_id' => $main_project['Project']['identifier'],
			'id' => $member_row['Member']['id']
		),
		'update' => 'tab-content-members', 
		'buffer' => false, 
		'evalScripts' => true))    
	?>
	<?php echo '</form>'; ?>
      <?php endif; ?>
    </td>
    <td align="center">
  <?php echo $this->Js->link(__('Delete'),
  	array(
        	'controller' => 'members',
        	'action' => 'destroy',
        	'project_id' => $main_project['Project']['identifier'],
        	'id' => $member_row['Member']['id'],
      ),array(
      		'class' => 'icon icon-del',
		'update' => 'tab-content-members', 
		'buffer' => false, 
		'evalScripts' => true
	)
    )
   ?>
    </td>
<!--     <%= call_hook(:view_projects_settings_members_table_row, { :project => @project, :member => member}) %> -->
	</tr>
    <?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
<p class="nodata"><?php echo __('No data to display') ?></p>
<?php endif; ?>

<?php
  $project_member_map = Set::extract('/Member/user_id',$members);
  $users_list = array();
  foreach ($users_data as $user_row) {
    if (in_array($user_row['User']['id'],$project_member_map) ) { continue; } 
    $users_list[$user_row['User']['id']] = $this->Candy->format_username($user_row['User']);
  }
?>
<?php if (
	$this->Candy->authorize_for(
		array(
			'controller' => 'members',
			'action' => 'new'
		)
	) && !empty($users_list)
): ?>
  <?php 
  	echo $this->Form->create('Member', array(
		'url' => array(
			'controller' => 'members',
			'action' => 'add',
			'project_id' => $main_project['Project']['identifier']
		)	
	));
  ?>
    <p><label for="member_user_id"><?php echo __('New member') ?></label><br />
    <?php echo $this->Form->select('Member.user_id',$users_list,
    		array(
			'empty' => false
		)
	  ) ?>
    <?php echo __('Role') ?>: <?php echo $this->Form->select(
		'Member.role_id',
		$roles_list,
		array(
			'class' => 'small',
			'empty' => false
		)
	) ?>
    <?php echo $this->Js->submit(__('Add'),array('div' => false, 'url' => 
    		array(
			'controller' => 'members',
			'action' => 'add',
			'project_id' => $main_project['Project']['identifier']
		
		),
		'update' => 'tab-content-members', 
		'buffer' => false, 
		'evalScripts' => true)) ?></p>
  <?php echo '</form>' ?>
<?php endif; ?>
