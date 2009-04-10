<h2><?php __('New project') ?></h2>

<?php echo $form->create('Project', array('action'=>'add')) ?>
<% labelled_tabular_form_for :project, @project, :url => { :action => "add" } do |f| %>
<?php echo $this->element('projects/form') ?>
<?php /*
<%= render :partial => 'form', :locals => { :f => f } %>
 */ ?>

<fieldset class="box"><legend><?php __('Modules') ?></legend>
<% Redmine::AccessControl.available_project_modules.each do |m| %>
    <label class="floating">
    <%= check_box_tag 'enabled_modules[]', m, @project.module_enabled?(m) %>
    <%= (l_has_string?("project_module_#{m}".to_sym) ? l("project_module_#{m}".to_sym) : m.to_s.humanize) %>
    </label>
<% end %>
</fieldset>

<?php echo $form->submit(__('Save', true)) ?>
<% end %>
<?php echo $form->end() ?>

