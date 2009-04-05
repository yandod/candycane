<div id="login-form">
<?php e($form->create(null, array('url' => '/account/login'))); ?>
<%= back_url_hidden_field_tag %>
<table>
<tr>
	<td align="right">
		<label for="username"><%=l(:field_login)%>:</label>
	</td>
	<td align="left">
		<p><?php e($form->input('username', array('label' => false, 'size' => '40%'))); ?></p>
	</td>
</tr>

<tr>
    <td align="right"><label for="password"><%=l(:field_password)%>:</label></td>
    <td align="left">
	    <p><?php e($form->input('password', array('type' => 'password', 'label' => false, 'size' => '40%'))); ?></p>
    </td>
</tr>

<tr>
    <td></td>
    <td align="left">
        <% if Setting.autologin? %>
        <label for="autologin"><%= check_box_tag 'autologin' %> <%= l(:label_stay_logged_in) %></label>
        <% end %>
    </td>
</tr>

<tr>
    <td align="left">
        <% if Setting.lost_password? %>
            <%= link_to l(:label_password_lost), :controller => 'account', :action => 'lost_password' %>
        <% end %>
    </td>
    <td align="right">
	    <?php e($form->submit('button_login' . ' &#187', array('name' => 'login'))); ?>
    </td>
</tr>
</table>
<%= javascript_tag "Form.Element.focus('username');" %>
<?php e($form->end()); ?>
</div>
