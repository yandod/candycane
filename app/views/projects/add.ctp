<h2><?php __('New project') ?></h2>

<?php echo $form->create('Project', array('action'=>'add')) ?>
<?php /*
<% labelled_tabular_form_for :project, @project, :url => { :action => "add" } do |f| %>
 */ ?>
<?php echo $this->element('projects/form') ?>
<?php /*
<%= render :partial => 'form', :locals => { :f => f } %>
 */ ?>

<fieldset class="box"><legend><?php __('Modules') ?></legend>
<?php foreach($enabled_module_names as $key=>$module): ?>
    <label class="floating">
<?php echo $form->input("[enabled_module][]", array('type'=>'checkbox', 'value'=>$module, 'label'=>__($module, true))) ?>
<?php /*
    <%= check_box_tag 'enabled_modules[]', m, @project.module_enabled?(m) %>
    <%= (l_has_string?("project_module_#{m}".to_sym) ? l("project_module_#{m}".to_sym) : m.to_s.humanize) %>
 */?>
    </label>
<?php endforeach ?>
</fieldset>

<?php echo $form->submit(__('Save', true)) ?>
<?php /*
<% end %>
 */ ?>
<?php echo $form->end() ?>

