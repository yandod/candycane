<?php
// @todo custom_field_values
// @todo auth_sources
?>
<!--[form:user]-->
<div class="box">
	<?php echo $this->Form->input('id', array('type' => 'hidden', 'value' => $user['User']['id'])); ?>
	<p><?php echo $this->Form->input('login', array('value' => $user['User']['login'], 'size' => '25%', 'div' => false)); ?></p>
	<p><?php echo $this->Form->input('firstname', array('value' => $user['User']['firstname'], 'div' => false)); ?></p>
	<p><?php echo $this->Form->input('lastname', array('value' => $user['User']['lastname'], 'div' => false)); ?></p>
	<p><?php echo $this->Form->input('mail', array('value' => $user['User']['mail'], 'div' => false)); ?></p>
	<p><?php echo $this->Form->input('language', array('type' => 'select', 'options' => $this->Candy->lang_options_for_select(), 'selected' => $user['User']['language'], 'div' => false)); ?></p>
	<!-- 
	<% @user.custom_field_values.each do |value| %>
	  <p><%= custom_field_tag_with_label :user, value %></p>
	<% end %>
	-->
	<p>
		<?php if ($currentuser['admin']):?>
			<?php echo $this->Form->input('admin', array('type' => 'checkbox', 'options' => array(1), 'div' => false, 'checked' => $user['User']['admin'] ? 'checked' : false)); ?>
		<?php else: ?>
			<?php echo $this->Form->input('admin', array('type' => 'checkbox', 'options' => array(1), 'disabled' => 'disabled', 'div' => false)); ?>
		<?php endif; ?>
	</p>
</div>

<div class="box">
  <h3><?php echo __('Authentication'); ?></h3>
  <?php if (isset($auth_sources)): ?>
  <p>
    <%= f.select :auth_source_id, ([[l(:label_internal), ""]] + @auth_sources.collect { |a| [a.name, a.id] }), {}, :onchange => "if (this.value=='') {Element.show('password_fields');} else {Element.hide('password_fields');}" %>
  </p>
  <?php endif; ?>

  <div id="password_fields" style="<%= 'display:none;' if @user.auth_source %>">

    <p>
      <label for="password"><?php echo __('Password'); ?><span class="required"> *</span></label>
      <?php echo $this->Form->password('password', array('size' => '25%')); ?>
      <em><?php printf(__('Must be at least %d characters long.'), 4); ?></em>
    </p>

    <p>
      <label for="password_confirmation"><?php echo __('Confirmation'); ?><span class="required"> *</span></label>
      <?php echo $this->Form->password('password_confirmation', array('size' => '25%')); ?>
    </p>

  </div>
</div>
<!--[eoform:user]-->
