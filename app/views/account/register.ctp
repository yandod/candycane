<h2><?php __('label_register'); ?></h2>

<?php echo $form->create('User', array('url' => '/account/register', 'class' => 'tabular')); ?>

<div class="box">
<!--[form:user]-->

<?php if (empty($user['User']['auth_source_id'])): ?>
<p>
  <label for="user_login"><?php __('field_login'); ?> <span class="required">*</span></label>
  <?php echo $form->input('login', array('size' => '25%', 'div' => false, 'label' => false)); ?>
</p>

<p>
  <label for="password"><?php __('field_password'); ?> <span class="required">*</span></label>
  <?php echo $form->input('password', array('type' => 'password', 'size' => '25%', 'div' => false, 'label' => false)); ?><br />
  <em><?php __('text_caracters_minimum'); ?></em>
</p>

<p>
  <label for="password_confirmation"><?php __('field_password_confirmation'); ?> <span class="required">*</span></label>
  <?php echo $form->input('password_confirmation', array('type' => 'password', 'size' => '25%', 'div' => false, 'label' => false)); ?><br />
</p>
<?php endif; ?>

<p>
  <label for="user_firstname"><?php __('field_firstname'); ?> <span class="required">*</span></label>
  <?php echo $form->input('firstname', array('div' => false, 'label' => false)); ?>
</p>

<p><label for="user_lastname"><?php __('field_lastname'); ?> <span class="required">*</span></label>
  <?php echo $form->input('lastname', array('div' => false, 'label' => false)); ?>
</p>

<p>
<label for="user_mail"><?php __('field_mail'); ?> <span class="required">*</span></label>
  <?php echo $form->input('mail', array('div' => false, 'label' => false)); ?>
</p>

<p>
<label for="user_language"><?php __('field_language'); ?></label>
<%= select("user", "language", lang_options_for_select) %>
</p>

<% @user.custom_field_values.each do |value| %>
	<p><%= custom_field_tag_with_label :user, value %></p>
<% end %>
<!--[eoform:user]-->
</div>

<?php echo $form->submit(__('button_submit', true)); ?>
<?php echo $form->end(); ?>
