<?php
// <%= error_messages_for 'role' %>
?>

<?php if (! ($role['Role']['builtin'] == 1)): ?>
<div class="box">
   <p><?php echo $this->Form->input('name', array('required' => TRUE, 'div' => FALSE, 'value' => $role['Role']['name'])); ?></p>
   <p><?php echo $this->Form->input('assignable', array('type' => 'checkbox', 'div' => FALSE, 'label' => __('Issues can be assigned to this role', TRUE), 'checked' => ($role['Role']['assignable'] == 1) ? 'checked' : FALSE)); ?></p>

<?php if (isset($role['Role']['new_record']) && $role['Role']['new_record']): ?>
<p><?php echo $this->Form->input('copy_workflow', array('options' => $roles,
                                                  'label' => __('Copy workflow from', TRUE),'div' => false)); ?></p>
<?php endif; ?>

</div>
<?php endif; ?>

<h3><?php echo __('Permissions'); ?></h3>
<div class="box" id="permissions">
  <?php foreach ($permissions as $mod => $val): ?>
  <fieldset><legend><?php ($mod == '') ? __('Project') : __($project_module_name[$mod]);?></legend>
  <?php foreach($permissions[$mod] as $permission): ?>
  <label class="floating">
    <?php $checked = (in_array($permission['name'],$permissions_array)) ? 'checked' : '';?>
    <input type="checkbox" name="data[Role][permissions][]" value="<?php echo h($permission['name']); ?>" <?php echo $checked; ?> />
    <?php echo __($permission_name[ $permission['name'] ]); ?>
  </label>
  <?php endforeach; ?>
</fieldset>
<?php endforeach; ?>
<br />
<?php echo $this->Candy->check_all_links('permissions'); ?>
</div>

