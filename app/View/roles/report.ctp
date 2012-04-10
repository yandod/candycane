<h2><?php echo __('Permissions report'); ?></h2>

<?php echo $this->Form->create('Role', array('action' => 'report','id' => 'permissions_form')); ?>
<!-- <input name="data[permissions][0]" type="hidden" value="" /> -->

<table class="list">
  <thead>
    <tr>
      <th><?php echo __('Permissions'); ?></th>
      <?php foreach($roles as $role): ?>
      <th>
        <?php
          $tag = ($role['Role']['builtin'] == 1) ? 'em' : 'span';
echo $this->Html->tag($tag,h($role['Role']['name']));
        ?>
          <?php
            echo $this->Html->link($this->Html->image('toggle_check.png'),
                             '#',
                             array('onclick' => "toggleCheckboxesBySelector('input.role-" . $role['Role']['id'] . "')",
                                   'title' => __('Check all') . '/' . __('Uncheck all'),
				   'escape' => false)
                             );
          ?>
      </th>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($permissions as $mod => $val): ?>
    <?php if ($mod != ''): ?>
    <tr><?php echo $this->Html->tag('th', __($project_module_name[$mod], true, array('conspan' => count($roles) + 1, 'align' => 'left'))); ?></tr>
    <?php endif; ?>
    <?php foreach ($permissions[$mod] as $permission): ?>
    <tr class="<?php echo $this->Candy->cycle('odd', 'even'); ?> permission-<?php echo $permission['name'];?>">
    <td>
      <?php
        echo $this->Html->link($this->Html->image('toggle_check.png'),
                         '#',
                         array('onclick' => "toggleCheckboxesBySelector('.permission-" . $permission['name'] . " input')",
                               'title' => __('Check all') . '/' . __('Uncheck all'),
			       'escape' => false)
                         );
      ?>
      <?php echo __($permission_name[ $permission['name'] ]); ?>
    </td>
    <?php foreach($roles as $role): ?>
    <td align="center">
      <?php if (in_array(':' . $permission['name'], $role['Role']['setable_permissions'])): ?>
      <?php
        $checked = (in_array($permission['name'], $role['Role']['permissions'])) ? 'checked="checked"' : '';
            echo sprintf('<input type="checkbox" class="role-%s" name="data[permissions][%s][]" value="%s" %s/>',
                         h($role['Role']['id']),
                         h($role['Role']['id']),
                         h($permission['name']),
                         $checked);
      ?>
      <?php endif; ?>

    </td>
    <?php endforeach; ?>
  </tr>
  <?php endforeach; ?>
  <?php endforeach; ?>
</tbody>
</table>
<p><?php echo $this->Candy->check_all_links('permissions_form'); ?></p>
<p><?php echo $this->Form->submit(__('Save')); ?></p>
<?php echo $this->Form->end(); ?>
