<h2><?php __('Register'); ?></h2>
<?php echo $this->renderElement('error_explanation'); ?>
<?php
echo $form->create('User', array('url' => '/account/register', 'class' => 'tabular'));
?>

<div class="box">
<!--[form:user]-->

<?php if (empty($user['User']['auth_source_id'])): ?>
<p>
  <label for="user_login"><?php __('Login'); ?> <span class="required">*</span></label>
  <?php echo $form->input('login', array('size' => '25', 'div' => false, 'label' => false, 'error' => false)); ?>
</p>

<p>
  <label for="password"><?php __('Password'); ?> <span class="required">*</span></label>
  <?php echo $form->input('password', array('type' => 'password', 'size' => '25', 'div' => false, 'label' => false, 'error' => false)); ?><br />
  <em><?php echo sprintf(__('Must be at least %d characters long.', true), 4); ?></em>
</p>

<p>
  <label for="password_confirmation"><?php __('Confirmation'); ?> <span class="required">*</span></label>
  <?php echo $form->input('password_confirmation', array('type' => 'password', 'size' => '25', 'div' => false, 'label' => false, 'error' => false)); ?><br />
</p>
<?php endif; ?>

<p>
  <label for="user_firstname"><?php __('Firstname'); ?> <span class="required">*</span></label>
  <?php echo $form->input('firstname', array('div' => false, 'label' => false, 'error' => false)); ?>
</p>

<p>
  <label for="user_lastname"><?php __('Lastname'); ?> <span class="required">*</span></label>
  <?php echo $form->input('lastname', array('div' => false, 'label' => false, 'error' => false)); ?>
</p>

<p>
  <label for="user_mail"><?php __('Email'); ?> <span class="required">*</span></label>
  <?php echo $form->input('mail', array('div' => false, 'label' => false, 'error' => false)); ?>
</p>

<p>
  <label for="user_language"><?php __('Language'); ?></label>
  <?php echo $form->input('language', array('type' => 'select', 'options' => $candy->lang_options_for_select(), 'div' => false, 'label' => false, 'error' => false)); ?>
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

<?php echo $form->submit(__('Submit', true)); ?>
<?php echo $form->end(); ?>
