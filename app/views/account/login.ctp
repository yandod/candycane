<div id="login-form">
<?php e($form->create('User', array('url' => '/account/login'))); ?>
<%= back_url_hidden_field_tag %>

<table width="50%">
<tr>
  <td align="left" colspan="2">
    <p>
    <?php e($form->input('username', array('size' => '40%'))); ?>
    </p>
  </td>
</tr>

<tr>
  <td align="left" colspan="2">
    <p><?php e($form->input('password', array('type' => 'password', 'size' => '40%'))); ?></p>
  </td>
</tr>

<tr>
  <td align="left" colspan="2">
    &nbsp;&nbsp;<?php if (isset($setting->autologin)): ?>
    <?php echo $form->input('autologin', array('type' => 'checkbox', 'options' => array(1))); ?>
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
    <?php e($form->submit(__('button_login', true) . ' &#187', array('name' => 'login'))); ?>
  </td>
</tr>
</table>

<%= javascript_tag "Form.Element.focus('username');" %>

<?php e($form->end()); ?>

</div>
