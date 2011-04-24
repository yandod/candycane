<h2><?php __('New project') ?></h2>

<?php echo $form->create('Project', array('action'=>'add','class'=>'tabular')) ?>
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
<?php echo $form->checkbox("EnabledModule][", array('type'=>'checkbox', 'value'=>$module, 'label'=>false,'div'=>false)) ?>
<?php /*
    <%= check_box_tag 'enabled_modules[]', m, @project.module_enabled?(m) %>
    <%= (l_has_string?("project_module_#{m}".to_sym) ? l("project_module_#{m}".to_sym) : m.to_s.humanize) %>
 */?>
<?php echo h(__(ucfirst(str_replace('_',' ',$module)), true)) ?>
    </label>
<?php endforeach ?>
</fieldset>

<?php echo $form->submit(__('Save', true)) ?>
<?php /*
<% end %>
 */ ?>
<?php echo $form->end() ?>

