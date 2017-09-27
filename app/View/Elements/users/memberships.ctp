<?php
$roles_list = Set::combine($roles,'{n}.Role.id','{n}.Role.name');
$projects_list = Set::combine($projects,'{n}.Project.id','{n}.Project.name');
$membership_list = Set::combine($user['Membership'],'{n}.project_id','{n}.id');

$projects_options = array();
	foreach ($projects_list as $k => $v) {
		if ( !isset($membership_list[$k]) ) {
			$projects_options[$k] = $v;
		}
	}
?>
<?php 
// debug($user);
// debug($projects_list);
// debug($membership_list);
?>
<?php if (isset($user['Membership']) && is_array($user['Membership'])) :
		$rows = '';
		foreach ($user['Membership'] as $membership) :
			// If exist, prepare table rows
			if(array_key_exists($membership['project_id'], $projects_list)) :
				$rows .= '
					<tr class="' . $this->Candy->cycle() . '">
						<td>' . h($projects_list[$membership['project_id']]) . '</td>
						<td align="center">' 
							. $this->Form->create('User', array( 'url' => array(
								'controller' => 'users',
								'action' => 'edit_membership',
								$user['User']['id'],
								'membership_id' => $membership['id']
							))) 
							. $this->Form->select('Member.role_id',$roles_list,array('class' => 'small', 'value' => $membership['role_id'])) 
							. $this->Form->submit(__('Change'),array('class' => 'small', 'div' => false))
							. $this->Form->end() . '
						</td>
					<td align="center">' 
						. $this->Html->link(
							__('Delete'),
							array(
								'action' => 'destroy_membership',
								$user['User']['id'],
								'membership_id' => $membership['id']
							),
							array('class' => 'icon icon-del', 'confirm' => __('Are you sure ?'))) . '
					</td>
					</tr>';
			endif;
		endforeach;
		// debug($rows);
		if(isset($rows)) :
			// Prepare table header
			$header = '
			<table class="list memberships">
				<thead>
					<th>' . __('Project') . '</th>
					<th>' . __('Roles') . '</th>
					<th style="width:15%"></th>
				</thead>
				<tbody>';
			// Prepare table footer
			$footer = '
				</tbody>
			</table>';

			// Display table
			echo $header . $rows . $footer;
		else : ?>
		<p class="nodata">
		  <?php echo __('No data to display'); ?>
		</p>
	<?php 
		endif; 
	endif;
	?>

<?php if (isset($projects) && is_array($projects)): ?>
	<p>
		<label><?php echo __('New project'); ?></label><br/>
		<?php echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'edit_membership', $user['User']['id']))); ?>
		<?php echo $this->Form->select(
			'Member.project_id',
			$projects_options,
			array('class' => 'small')
		); ?>
		<?php echo __('Roles'); ?> :
		<?php echo $this->Form->select(
			'Member.role_id',
			$roles_list,
			array('class' => 'small')
		); ?>
		<?php echo $this->Form->submit(__('Add'),array('div' => false)); ?>
		<?php echo $this->Form->end(); ?>
	</p>
<?php endif; ?>
