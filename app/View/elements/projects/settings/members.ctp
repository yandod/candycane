<!-- 
<%= error_messages_for 'member' %>
-->   
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
    <?php if ($this->Candy->authorize_for(aa('controller','members','action','edit'))): ?>
  <?php echo $ajax->form(
    array('options' =>array(
      'model' => 'Member',
      'update' => 'tab-content-members',
      'url' => array(
        'controller' => 'members',
        'action' => 'edit',
        'project_id' => $main_project['Project']['identifier'],
        'id' => $member_row['Member']['id'],
      )
    ))
  ) ?>
        <?php echo $this->Form->select('Member.role_id',$roles_list,$member_row['Member']['role_id'],aa('class','small'),false) ?>
        <?php echo $this->Form->submit(__('Change'),aa('class','small','div',false)) ?>
      <?php echo '</form>'; ?>
      <?php endif; ?>
    </td>
    <td align="center">
  <?php echo $ajax->link(__('Delete'),array(
        'controller' => 'members',
        'action' => 'destroy',
        'project_id' => $main_project['Project']['identifier'],
        'id' => $member_row['Member']['id'],
      ),aa('class','icon icon-del','update','tab-content-members')
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
<?php if ($this->Candy->authorize_for(aa('controller','members','action','new') && !empty($users_list))): ?>
  <?php echo $ajax->form(
    array('options' =>array(
      'model' => 'Member',
      'update' => 'tab-content-members',
      'url' => array(
        'controller' => 'members',
        'action' => 'add',
        'project_id' => $main_project['Project']['identifier'],
      )
    ))
  ) ?>
    <p><label for="member_user_id"><?php echo __('New member') ?></label><br />
    <?php echo $this->Form->select('Member.user_id',$users_list,false,false,false) ?>
    <?php echo __('Role') ?>: <?php echo $this->Form->select('Member.role_id',$roles_list,false,aa('class','small'),false) ?>
    <?php echo $this->Form->submit(__('Add'),aa('div',false)) ?></p>
  <?php echo '</form>' ?>
<?php endif; ?>
