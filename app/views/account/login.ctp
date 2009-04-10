<div id="login-form">
<?php e($form->create(null, array('url' => '/account/login'))); ?>
<%= back_url_hidden_field_tag %>
<table>
<tr>
  <td align="right">
    <label for="username"><?php __('field_login'); ?>:</label>
  </td>
  <td align="left">
    <p><?php e($form->input('username', array('label' => false, 'size' => '40%'))); ?></p>
  </td>
</tr>

<tr>
  <td align="right"><label for="password"><?php __('field_password'); ?>:</label></td>
  <td align="left">
    <p><?php e($form->input('password', array('type' => 'password', 'label' => false, 'size' => '40%'))); ?></p>
  </td>
</tr>

<tr>
  <td></td>
  <td align="left">
    <?php if (isset($setting->autologin)): ?>
    <label for="autologin"><%= check_box_tag 'autologin' %><?php __('label_stay_logged_in'); ?></label>
    <?php endif; ?>
    </td>
</tr>

<tr>
  <td align="left">
    <?php if (isset($setting->lost_password)): ?>
      <?php e($html->link(__('label_password_lost', true), '/account/lost_password')); ?>
    <?php endif; ?>
  </td>
  <td align="right">
    <?php e($form->submit('button_login' . ' &#187', array('name' => 'login'))); ?>
  </td>
</tr>
</table>
<%= javascript_tag "Form.Element.focus('username');" %>
<?php e($form->end()); ?>
</div>
