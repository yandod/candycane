<h2><?php __('Role'); ?>: <?php echo h($role['Role']['name']); ?></h2>

<?php echo $form->create('Role', array('action' => 'edit','id' => 'role_form','class' => 'tabular')); ?>
<!-- <%= error_messages_for 'role' %>  -->

<?php if (! ($role['Role']['builtin'] != 0)): ?>
<div class="box">
   <p><?php echo $form->input('name', array('required' => TRUE, 'div' => FALSE)); ?></p>
   <p><?php echo $form->input('assignable', array('type' => 'checkbox', 'div' => FALSE, 'label' => __('Issues can be assigned to this role', TRUE), 'checked' => ($role['Role']['assignable'] == 1) ? 'checked' : FALSE)); ?></p>

<!-- <% if @role.new_record? && @roles.any? %> -->
<p><?php echo $form->input('copy_workflow', array('options' => $roles,
                                                  'label' => __('Copy workflow from', TRUE),'div' => false)); ?></p>
<!-- <% end %> -->

</div>
<?php endif; ?>

<h3><?php __('Permissions'); ?></h3>
<div class="box" id="permissions">
<!-- <% perms_by_module = @permissions.group_by {|p| p.project_module.to_s} %> -->
<!-- <% perms_by_module.keys.sort.each do |mod| %> -->
<?php foreach ($permissions as $mod): ?>
    <fieldset><legend><%= mod.blank? ? l(:label_project) : l_or_humanize(mod, :prefix => 'project_module_') %></legend>
    <% perms_by_module[mod].each do |permission| %>
        <label class="floating">
        <%= check_box_tag 'role[permissions][]', permission.name, (@role.permissions.include? permission.name) %>
        <%= l_or_humanize(permission.name, :prefix => 'permission_') %>
        </label>
    <% end %>
    </fieldset>
<!-- <% end %> -->
<?php endforeach; ?>
<br /><%= check_all_links 'permissions' %>
<%= hidden_field_tag 'role[permissions][]', '' %>
</div>



<?php echo $form->submit(__('Save', TRUE)); ?>
<?php echo $form->end(); ?>
