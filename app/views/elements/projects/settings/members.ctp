<!-- 
<%= error_messages_for 'member' %>
<% roles = Role.find_all_givable %>
<% users = User.active.find(:all).sort - @project.users %>
<% # members sorted by role position
   members = @project.members.find(:all, :include => [:role, :user]).sort %>
-->   
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
    <?php if ($candy->authorize_for(':members')): ?>
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
        <?php echo $form->select('Member.role_id',$roles,false,aa('class','small')) ?>
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
    <%= f.select :user_id, users.collect{|user| [user.name, user.id]} %>
    <?php __('Role') ?>: <?php echo $form->select('Member.role_id',$roles,false,aa('class','small')) ?>
    <?php echo $form->submit(__('Add',true),aa('div',false)) ?></p>
  <?php echo '</form>' ?>
<!-- <% end %> -->
