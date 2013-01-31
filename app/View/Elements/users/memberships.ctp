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
<?php if (isset($user['Membership']) && is_array($user['Membership'])): ?>
<table class="list memberships">

  <thead>
    <th><?php echo __('Project'); ?></th>
    <th><?php echo __('Roles'); ?></th>
    <th style="width:15%"></th>
  </thead>

  <tbody>
	<?php foreach ($user['Membership'] as $membership): ?>
      <tr class="<?php echo $this->Candy->cycle(); ?>">
      <td><?php echo h($projects_list[$membership['project_id']])?></td>
      <td align="center">
		<?php echo $this->Form->create('User', array( 'url' => array(
			'controller' => 'users',
			'action' => 'edit_membership',
			$user['User']['id'],
			'membership_id' => $membership['id']
		))); ?>
		<?php echo $this->Form->select('Member.role_id',$roles_list,array('class' => 'small', 'value' => $membership['role_id'])) ?>
		<?php echo $this->Form->submit(__('Change'),array('class' => 'small', 'div' => false)); ?>
		<?php echo $this->Form->end(); ?>
    </td>
    <td align="center">
		<?php echo $this->Html->link(
				__('Delete'),
				array(
					'action' => 'destroy_membership',
					$user['User']['id'],
					'membership_id' => $membership['id']
				),
				array('class' => 'icon icon-del')) ?>
    </td>
	</tr>
	</tbody>
<?php endforeach; ?>
</table>
<?php else: ?>
<p class="nodata">
  <?php echo __('No data to display'); ?>
</p>
<?php endif; ?>

<?php if (isset($projects) && is_array($projects)): ?>
<p>
<label><?php echo __('New project'); ?></label><br/>
<?php echo $this->Form->create('User', array('url' => '/users/edit_membership/'.$user['User']['id'])); ?>
<?php echo $this->Form->select(
	'Member.project_id',
	$projects_options,
	array('class' => 'small')
); ?>
<?php echo __('Roles'); ?>:
<?php echo $this->Form->select(
	'Member.role_id',
	$roles_list,
	array('class' => 'small')
) ?>
<?php echo $this->Form->submit(__('Add'),array('div' => false)); ?>
<?php echo $this->Form->end(); ?>
</p>
<?php endif; ?>
