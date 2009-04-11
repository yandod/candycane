<h2><?php __('Role'); ?>: <?php echo h($role['Role']['name']); ?></h2>

<?php echo $form->create('Role', array('action' => 'edit','id' => 'role_form','class' => 'tabular')); ?>
<!-- <%= error_messages_for 'role' %>  -->

<?php if (! ($role['Role']['builtin'] != 0)): ?>
<div class="box">
   <p><?php echo $form->input('name', array('required' => TRUE, 'div' => FALSE, 'value' => $role['Role']['name'])); ?></p>
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
<?php foreach ($permissions as $mod => $val): ?>
<fieldset><legend><?php ($mod == '') ? __('Project') : __($project_module_name[$mod]);?></legend>
<?php foreach($permissions[$mod] as $permission): ?>
<label class="floating">
   <?php echo $form->input($permission['name'], array('type' => 'checkbox', 'label' => FALSE, 'div' => FALSE, 'checked' => (in_array(':' .$permission['name'],$permissions_array)) ? 'checked' : FALSE)); ?>
  <?php __($permission_name[ $permission['name'] ]); ?>
</label>
<?php endforeach; ?>
</fieldset>
<?php endforeach; ?>
<br /><!-- <%= check_all_links 'permissions' %> -->
<!-- <%= hidden_field_tag 'role[permissions][]', '' %> -->
<?php echo $candy->check_all_links('permissions'); ?>
</div>



<?php echo $form->submit(__('Save', TRUE)); ?>
<?php echo $form->end(); ?>
