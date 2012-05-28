<h2><?php echo $this->Candy->html_title(__('Register')); ?></h2>
<?php echo $this->element('error_explanation'); ?>
<?php
echo $this->Form->create('User', array('url' => '/account/register', 'class' => 'tabular'));
?>

<div class="box">
<!--[form:user]-->

<?php if (empty($user['User']['auth_source_id'])): ?>
<p>
  <label for="user_login"><?php echo __('Login'); ?> <span class="required">*</span></label>
  <?php echo $this->Form->input('login', array('size' => '25', 'div' => false, 'label' => false, 'error' => false)); ?>
</p>

<p>
  <label for="password"><?php echo __('Password'); ?> <span class="required">*</span></label>
  <?php echo $this->Form->input('password', array('type' => 'password', 'size' => '25', 'div' => false, 'label' => false, 'error' => false)); ?><br />
  <em><?php echo sprintf(__('Must be at least %d characters long.'), 4); ?></em>
</p>

<p>
  <label for="password_confirmation"><?php echo __('Confirmation'); ?> <span class="required">*</span></label>
  <?php echo $this->Form->input('password_confirmation', array('type' => 'password', 'size' => '25', 'div' => false, 'label' => false, 'error' => false)); ?><br />
</p>
<?php endif; ?>

<p>
  <label for="user_firstname"><?php echo __('Firstname'); ?> <span class="required">*</span></label>
  <?php echo $this->Form->input('firstname', array('div' => false, 'label' => false, 'error' => false)); ?>
</p>

<p>
  <label for="user_lastname"><?php echo __('Lastname'); ?> <span class="required">*</span></label>
  <?php echo $this->Form->input('lastname', array('div' => false, 'label' => false, 'error' => false)); ?>
</p>

<p>
  <label for="user_mail"><?php echo __('Email'); ?> <span class="required">*</span></label>
  <?php echo $this->Form->input('mail', array('div' => false, 'label' => false, 'error' => false)); ?>
</p>

<p>
  <label for="user_language"><?php echo __('Language'); ?></label>
  <?php echo $this->Form->input('language', array('type' => 'select', 'options' => $this->Candy->lang_options_for_select(), 'div' => false, 'label' => false, 'error' => false)); ?>
</p>
<?php
/* TODO:port
<% @user.custom_field_values.each do |value| %>
  <p><%= custom_field_tag_with_label :user, value %></p>
<% end %>
**/
?>
<!--[eoform:user]-->
</div>

<?php echo $this->Form->submit(__('Submit')); ?>
<?php echo $this->Form->end(); ?>
