<?php
// @todo custom_field_values
// @todo auth_sources
?>
<!--[form:user]-->
<div class="box">
  <p><?php e($form->input('login', array('size' => '25%'))); ?></p>
  <p><?php e($form->input('firstname')); ?></p>
  <p><?php e($form->input('lastname')); ?></p>
  <p><?php e($form->input('mail')); ?></p>
  <p><%= f.select :language, lang_options_for_select %></p>

  <?php // echo $candy->lang_options_for_select(); ?>

<% @user.custom_field_values.each do |value| %>
	<p><%= custom_field_tag_with_label :user, value %></p>
<% end %>

<p>
  <%= f.check_box :admin, :disabled => (@user == User.current) %>
</p>
</div>

<div class="box">
  <h3><?php __('label_authentication'); ?></h3>
  <?php if (isset($auth_sources)): ?>
  <p>
    <%= f.select :auth_source_id, ([[l(:label_internal), ""]] + @auth_sources.collect { |a| [a.name, a.id] }), {}, :onchange => "if (this.value=='') {Element.show('password_fields');} else {Element.hide('password_fields');}" %>
  </p>
  <?php endif; ?>

  <div id="password_fields" style="<%= 'display:none;' if @user.auth_source %>">

    <p>
      <label for="password"><?php __('field_password'); ?><span class="required"> *</span></label>
      <?php e($form->password('password', array('size' => '25%'))); ?>
      <em><%= l(:text_caracters_minimum, 4) %></em>
    </p>

    <p>
      <label for="password_confirmation"><?php __('field_password_confirmation'); ?><span class="required"> *</span></label>
      <?php e($form->password('password_confirmation', array('size' => '25%'))); ?>
    </p>

  </div>
</div>
<!--[eoform:user]-->
