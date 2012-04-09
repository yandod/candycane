<div id="login-form">
<?php echo $this->Form->create('User', array('url' => '/account/login')); ?>
<!--  <%= back_url_hidden_field_tag %> -->
<?php echo $this->Form->hidden('back_url',array('name' => 'back_url','value' => $back_url)); ?>

<table>
<tr>
  <td align="right" colspan="2">
    <p>
    <?php echo $this->Form->input('username', array('size' => '40','id' => 'username','label' => array('text' => __('Login').':'))); ?>
    </p>
  </td>
</tr>

<tr>
  <td align="right" colspan="2">
    <p><?php echo $this->Form->input('password', array('type' => 'password', 'size' => '40','label' => array('text' => __('Password').':'))); ?></p>
  </td>
</tr>

<tr>
  <td align="left" colspan="2">
    &nbsp;&nbsp;<?php if ($setting->autologin): ?>
    <?php echo $this->Form->input('autologin', array('type' => 'checkbox', 'options' => array(1))); ?>
    <?php endif; ?>
  </td>
</tr>

<tr>
  <td align="left">
    <?php if ($setting->lost_password): ?>
      <?php echo $this->Html->link(__('Lost password'), '/account/lost_password'); ?>
    <?php endif; ?>
  </td>
  <td align="right">
    <?php echo $this->Form->submit(__('Login') . ' &#187', array('name' => 'login','escape' => false)); ?>
  </td>
</tr>
</table>

<?php echo $this->Html->scriptBlock("Form.Element.focus('username');") ?>

<?php echo $this->Form->end(); ?>

</div>
