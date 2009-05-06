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
	  <th><%= l(:label_user) %></th>
	  <th><%= l(:label_role) %></th>
	  <th style="width:15%"></th>
          <%= call_hook(:view_projects_settings_members_table_header, :project => @project) %>
	</thead>
	<tbody>
	<% members.each do |member| %>
	<% next if member.new_record? %>
	<tr class="<%= cycle 'odd', 'even' %>">
	<td><%= member.name %></td>
    <td align="center">
    <% if authorize_for('members', 'edit') %>
      <% remote_form_for(:member, member, :url => {:controller => 'members', :action => 'edit', :id => member}, :method => :post) do |f| %>
        <%= f.select :role_id, roles.collect{|role| [role.name, role.id]}, {}, :class => "small" %>
        <%= submit_tag l(:button_change), :class => "small" %>
      <% end %>
    <% end %>
    </td>
    <td align="center">
      <%= link_to_remote l(:button_delete), { :url => {:controller => 'members', :action => 'destroy', :id => member},                                              
                                              :method => :post
                                            }, :title => l(:button_delete),
                                               :class => 'icon icon-del' %>
    </td>
    <%= call_hook(:view_projects_settings_members_table_row, { :project => @project, :member => member}) %>
	</tr>
	</tbody>
<% end; reset_cycle %>
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
    <?php __('Role') ?>: <%= f.select :role_id, roles.collect{|role| [role.name, role.id]}, :selected => nil %>
    <?php echo $form->submit(__('Add',true)) ?></p>
  <?php echo '</form>' ?>
<!-- <% end %> -->
