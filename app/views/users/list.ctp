<div class="contextual">
  <?php echo $html->link(__('label_user_new', true), '/users/add', array('class' => 'icon icon-add')); ?>
</div>

<h2><?php __('label_filter_plural'); ?></h2>

<% form_tag({}, :method => :get) do %>
<fieldset>
  <legend><?php __('label_filter_plural'); ?></legend>
  <label><?php __('field_status'); ?>:</label>

<%= select_tag 'status', users_status_options_for_select(@status), :class => "small", :onchange => "this.form.submit(); return false;"  %>

<label><?php __('label_user'); ?>:</label>
<%= text_field_tag 'name', params[:name], :size => 30 %>
<%= submit_tag l(:button_apply), :class => "small", :name => nil %>
</fieldset>
<% end %>
&nbsp;

<table class="list">
  <thead><tr>
	<%= sort_header_tag('login', :caption => l(:field_login)) %>
	<%= sort_header_tag('firstname', :caption => l(:field_firstname)) %>
	<%= sort_header_tag('lastname', :caption => l(:field_lastname)) %>
	<%= sort_header_tag('mail', :caption => l(:field_mail)) %>
	<%= sort_header_tag('admin', :caption => l(:field_admin), :default_order => 'desc') %>
	<%= sort_header_tag('created_on', :caption => l(:field_created_on), :default_order => 'desc') %>
	<%= sort_header_tag('last_login_on', :caption => l(:field_last_login_on), :default_order => 'desc') %>
    <th></th>
  </tr></thead>
  <tbody>

  <?php foreach($users as $user): ?>
  <tr class="user <%= cycle("odd", "even") %> <%= %w(anon active registered locked)[user.status] %>">
  <td class="username">
    <%= avatar(user, :size => "14") %>
    <?php echo $html->link(h($user['User']['login']), '/users/edit/'.$user['User']['id']); ?>
  </td>
  <td class="firstname"><?php e(h($user['User']['firstname'])); ?></td>
  <td class="lastname"><?php e(h($user['User']['lastname'])); ?></td>
  <td class="email"><%= mail_to(h(user.mail)) %></td>
  <td align="center">
    <?php if ($user['User']['admin'] == '1'): ?>
    <?php echo $html->image('true.png'); ?>
    <?php endif; ?>
  </td>
  <td class="created_on" align="center">
    <?php echo $candy->format_time($user['User']['created_on']); ?>
  </td>
  <td class="last_login_on" align="center"><%= format_time(user.last_login_on) unless user.last_login_on.nil? %></td>
    <td><small><%= change_status_link(user) %></small></td>
  </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<p class="pagination">
  <%= pagination_links_full @user_pages, @user_count %>
</p>

<?php echo $candy->html_title(__('label_user_plural', true)); ?>
