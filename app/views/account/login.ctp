<div id="login-form">
<?php e($form->create('User', array('url' => '/account/login'))); ?>
<!--  <%= back_url_hidden_field_tag %> -->
<?php echo $form->hidden('back_url',array('name' => 'back_url','value' => $back_url)); ?>

<table>
<tr>
  <td align="right" colspan="2">
    <p>
    <?php e($form->input('username', array('size' => '40','id' => 'username','label' => array('text' => __('Login',true).':')))); ?>
    </p>
  </td>
</tr>

<tr>
  <td align="right" colspan="2">
    <p><?php e($form->input('password', array('type' => 'password', 'size' => '40','label' => array('text' => __('Password',true).':')))); ?></p>
  </td>
</tr>

<tr>
  <td align="left" colspan="2">
    &nbsp;&nbsp;<?php if ($setting->autologin): ?>
    <?php echo $form->input('autologin', array('type' => 'checkbox', 'options' => array(1))); ?>
    <?php endif; ?>
  </td>
</tr>

<tr>
  <td align="left">
    <?php if ($setting->lost_password): ?>
      <?php e($html->link(__('Lost password', true), '/account/lost_password')); ?>
    <?php endif; ?>
  </td>
  <td align="right">
    <?php e($form->submit(__('Login', true) . ' &#187', array('name' => 'login','escape' => false))); ?>
  </td>
</tr>
</table>

<?php echo $javascript->codeBlock("Form.Element.focus('username');") ?>

<?php e($form->end()); ?>

</div>
