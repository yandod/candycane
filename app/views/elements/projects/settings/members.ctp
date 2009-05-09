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
	  <th><?php __('User') ?></th>
	  <th><?php __('Role') ?></th>
	  <th style="width:15%"></th>
<!--            <%= call_hook(:view_projects_settings_members_table_header, :project => @project) %> -->
	</thead>
	<tbody>
	<?php foreach ($members as $member_row): ?>
<?php	#<% next if member.new_record? %> ?>
	<tr class="<?php echo $candy->cycle() ?>">
	<td><?php echo $candy->format_username($member_row['User']) ?></td>
    <td align="center">
    <?php if ($candy->authorize_for(aa('contrller','members','action','edit'))): ?>
    <!-- <% if authorize_for('members', 'edit') %> -->
  <?php echo $ajax->form(
    array('options' =>array(
      'model' => 'Member',
      'update' => 'tab-content-members',
      'url' => array(
        'controller' => 'members',
        'action' => 'edit',
        'id' => $member_row['Member']['id'],
      )
    ))
  ) ?>
<?php       //<% remote_form_for(:member, member, :url => {:controller => 'members', :action => 'edit', :id => member}, :method => :post) do |f| %> ?> 
        <!-- <%= f.select :role_id, roles.collect{|role| [role.name, role.id]}, {}, :class => "small" %> -->
        <?php echo $form->select('Member.role_id',$roles_list,false,aa('class','small'),false) ?>
        <?php echo $form->submit(__('Change',true),aa('class','small','div',false)) ?>
      <?php endif; ?>
    <?php endforeach; ?>
    </td>
    <td align="center">
  <?php echo $ajax->link(__('Delete',true),array(
        'controller' => 'members',
        'action' => 'destroy',
        'id' => $member_row['Member']['id'],
      ),aa('class','icon icon-del')
    )
   ?>
<!--       <%= link_to_remote l(:button_delete), { :url => {:controller => 'members', :action => 'destroy', :id => member},                                              
                                              :method => :post
                                            }, :title => l(:button_delete),
                                               :class => 'icon icon-del' %> -->
    </td>
<!--     <%= call_hook(:view_projects_settings_members_table_row, { :project => @project, :member => member}) %> -->
	</tr>
	</tbody>
<!-- <% end; reset_cycle %> -->
</table>
<?php else: ?>
<p class="nodata"><?php __('No data to display') ?></p>
<?php endif; ?>

<?php
  $project_member_map = Set::extract('/Member/user_id',$members);
  $users_list = array();
  foreach ($users_data as $user_row) {
    if (in_array($user_row['User']['id'],$project_member_map) ) { continue; } 
    $users_list[$user_row['User']['id']] = $candy->format_username($user_row['User']);
  }
?>
<!-- <% if authorize_for('members', 'new') && !users.empty? %> -->
  <?php echo $ajax->form(
    array('options' =>array(
      'model' => 'Member',
      'update' => 'tab-content-members',
      'url' => array(
        'controller' => 'members',
        'action' => 'add',
        'id' => $main_project['Project']['identifier'],
      )
    ))
  ) ?>
    <p><label for="member_user_id"><?php __('New member') ?></label><br />
    <?php echo $form->select('Member.user_id',$users_list,false,false,false) ?>
    <?php __('Role') ?>: <?php echo $form->select('Member.role_id',$roles_list,false,aa('class','small'),false) ?>
    <?php echo $form->submit(__('Add',true),aa('div',false)) ?></p>
  <?php echo '</form>' ?>
<!-- <% end %> -->
