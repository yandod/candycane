<div class="contextual">
<?php if ( empty($currentuser['auth_source_id'])) echo $html->link(__('Change password',true),aa('action','password')) ?>
</div>

<h2><?php echo $candy->html_title(__('My account',true)) ?></h2>
<?php echo $this->renderElement('error_explanation'); ?>


<?php e($form->create('User',array('url'=>aa('controller','my','action','account')))); ?>
<div class="splitcontentleft">
  <h3><?php e(__('Information')); ?></h3>
  <div class="box tabular">
    <p>
      <label for="UserFirstname"><?php __('Firstname') ?> <span class="required">*</span></label>
      <?php e($form->input('firstname',aa('div',false,'label',false,'size',30,'error',false))); ?>
    </p>
    <p>
      <label for="UserLastname"><?php __('Lastname') ?> <span class="required">*</span></label>
      <?php e($form->input('lastname',aa('div',false,'label',false,'size',30,'error',false))); ?>
    </p>
    <p>
      <label for="UserEmail"><?php __('Email') ?> <span class="required">*</span></label>
      <?php e($form->input('mail',aa('div',false,'label',false,'size',30,'error',false))); ?>
    </p>
    <p>
      <label for="UserLanguage"><?php __('Language'); ?></label>
      <?php echo $form->select('language', $candy->lang_options_for_select(),$currentuser['language'],array('type' => 'select', 'div' => false, 'label' => false, 'error' => false),false); ?>
    </p>
  </div>

  <?php e($form->submit(__('Save',true))); ?>
</div>

<div class="splitcontentright">
  <h3><?php __('Email notifications') ?></h3>
  <div class="box">
  <%= select_tag 'notification_option',
  options_for_select(@notification_options, @notification_option),
  :onchange => 'if ($("notification_option").value == "selected") {Element.show("notified-projects")} else {Element.hide("notified-projects")}' %>

  <% content_tag 'div', :id => 'notified-projects', :style => (@notification_option == 'selected' ? '' : 'display:none;') do %>

  <p>
    <% User.current.projects.each do |project| %>
    <label><%= check_box_tag 'notified_project_ids[]', project.id, @user.notified_projects_ids.include?(project.id) %> <%=h project.name %></label><br />
    <% end %>
  </p>

  <p><em><?php __("\"For unselected projects, you will only receive notifications about things you watch or you're involved in (eg. issues you're the author or assignee).\""); ?></em></p>
<% end %>

  <p><label><%= check_box_tag 'no_self_notified', 1, @user.pref[:no_self_notified] %> <%= l(:label_user_mail_no_self_notified) %></label></p>
</div>

<h3><?php __('Preferences') ?></h3>
<div class="box tabular">
<% fields_for :pref, @user.pref, :builder => TabularFormBuilder, :lang => current_language do |pref_fields| %>
<p><%= pref_fields.check_box :hide_mail %></p>
<p><%= pref_fields.select :time_zone, ActiveSupport::TimeZone.all.collect {|z| [ z.to_s, z.name ]}, :include_blank => true %></p>
<p><%= pref_fields.select :comments_sorting, [[l(:label_chronological_order), 'asc'], [l(:label_reverse_chronological_order), 'desc']] %></p>
<% end %>
</div>
</div>
<?php echo $form->end() ?>
<?php $this->set('Sidebar',$this->renderElement('my/sidebar')) ?>

