<?php if (isset($memberships) && is_array($memberships)): ?>
<% if @memberships.any? %>
<table class="list memberships">

  <thead>
    <th><?php __('Project'); ?></th>
    <th><?php __('Roles'); ?></th>
    <th style="width:15%"></th>
  </thead>

  <tbody>
    <% @memberships.each do |membership| %>
      <% next if membership.new_record? %>
      <tr class="<?php echo $candy->cycle(); ?>">
      <td><%=h membership.project %></td>
      <td align="center">
    <% form_tag({ :action => 'edit_membership', :id => @user, :membership_id => membership }) do %>
        <%= select_tag 'membership[role_id]', options_from_collection_for_select(@roles, "id", "name", membership.role_id) %>
        <%= submit_tag l(:button_change), :class => "small" %>
    <% end %>
    </td>
    <td align="center">
      <%= link_to l(:button_delete), {:action => 'destroy_membership', :id => @user, :membership_id => membership }, :method => :post, :class => 'icon icon-del' %>
    </td>
	</tr>
	</tbody>
<% end; reset_cycle %>
</table>
<?php else: ?>
<p class="nodata">
  <?php __('No data to display'); ?>
</p>
<?php endif; ?>

<?php if (isset($projects) && is_array($projects)): ?>
<p>
<label><?php __('New project'); ?></label><br/>
<?php echo $form->create('User', array('url' => '/user/edit_membership')); ?>
<%= select_tag 'membership[project_id]', projects_options_for_select(@projects) %>
<?php __('Roles'); ?>:
<%= select_tag 'membership[role_id]', options_from_collection_for_select(@roles, "id", "name") %>
<?php echo $form->submit(__('Add', true)); ?>
<?php echo $form->end(); ?>
</p>
<?php endif; ?>
