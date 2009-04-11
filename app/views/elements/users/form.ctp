<?php
// @todo custom_field_values
// @todo auth_sources
// @todo selected current language
?>
<!--[form:user]-->
<div class="box">
  <p><?php e($form->input('login', array('size' => '25%'))); ?></p>
  <p><?php e($form->input('firstname')); ?></p>
  <p><?php e($form->input('lastname')); ?></p>
  <p><?php e($form->input('mail')); ?></p>
  <p>
    <?php echo $form->input('language', array('type' => 'select', 'options' => $candy->lang_options_for_select())); ?>
  </p>

<% @user.custom_field_values.each do |value| %>
  <p><%= custom_field_tag_with_label :user, value %></p>
<% end %>

  <p>
  <?php if ("@user == User.current"): // @todo implement me ?>
  <?php echo $form->input('admin', array('type' => 'checkbox', 'options' => array(1), 'disabled' => 'disabled')); ?>
  <?php else: ?>
  <?php echo $form->input('admin', array('type' => 'checkbox', 'options' => array(1))); ?>
  <?php endif; ?>
  </p>
</div>

<div class="box">
  <h3><?php __('Authentication'); ?></h3>
  <?php if (isset($auth_sources)): ?>
  <p>
    <%= f.select :auth_source_id, ([[l(:label_internal), ""]] + @auth_sources.collect { |a| [a.name, a.id] }), {}, :onchange => "if (this.value=='') {Element.show('password_fields');} else {Element.hide('password_fields');}" %>
  </p>
  <?php endif; ?>

  <div id="password_fields" style="<%= 'display:none;' if @user.auth_source %>">

    <p>
      <label for="password"><?php __('Password'); ?><span class="required"> *</span></label>
      <?php e($form->password('password', array('size' => '25%'))); ?>
      <em><?php printf(__('%d characters maximum.', true), 4); ?></em>
    </p>

    <p>
      <label for="password_confirmation"><?php __('Confirmation'); ?><span class="required"> *</span></label>
      <?php e($form->password('password_confirmation', array('size' => '25%'))); ?>
    </p>

  </div>
</div>
<!--[eoform:user]-->
