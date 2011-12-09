<?php
$roles_list = Set::combine($roles,'{n}.Role.id','{n}.Role.name');
$projects_list = Set::combine($projects,'{n}.Project.id','{n}.Project');
?>
<?php if (isset($user['Membership']) && is_array($user['Membership'])): ?>
<table class="list memberships">

  <thead>
    <th><?php __('Project'); ?></th>
    <th><?php __('Roles'); ?></th>
    <th style="width:15%"></th>
  </thead>

  <tbody>
	<?php foreach ($user['Membership'] as $membership): ?>
      <tr class="<?php echo $candy->cycle(); ?>">
      <td><?php echo h($projects_list[$membership['project_id']]['name'])?></td>
      <td align="center">
		<?php echo $form->create('User', array('url' => '/users/edit_membership/'.$user['User']['id'])); ?>
		<?php echo $form->select('Member.role_id',$roles_list,null,aa('class','small'),false) ?>
		<?php echo $form->submit(__('Change', true),array('class' => 'small', 'div' => false)); ?>
		<?php echo $form->end(); ?>
    </td>
    <td align="center">
		<?php echo $html->link(
				__('Delete',true),
				array(
					'action' => 'destroy_membership',
					'id' => $user['User']['id'],
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
  <?php __('No data to display'); ?>
</p>
<?php endif; ?>

<?php if (isset($projects) && is_array($projects)): ?>
<p>
<label><?php __('New project'); ?></label><br/>
<?php echo $form->create('User', array('url' => '/users/edit_membership/'.$user['User']['id'])); ?>
<%= select_tag 'membership[project_id]', projects_options_for_select(@projects) %>
<?php __('Roles'); ?>:
<?php echo $form->select('Member.role_id',$roles_list,null,aa('class','small'),false) ?>
<?php echo $form->submit(__('Add', true),array('div' => false)); ?>
<?php echo $form->end(); ?>
</p>
<?php endif; ?>
