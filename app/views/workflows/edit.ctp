<div class="contextual">
  <?php echo $html->link(__('Summary', TRUE), array('action' => 'index')); ?>
</div>

<h2><?php __('Workflow'); ?></h2>

<p><?php __('Select a role and a tracker to edit the workflow'); ?>:</p>

<?php echo $form->create('Workflow', array('type' => 'get','action' => 'edit')); ?>
<p><label for="role_id"><?php __('Role'); ?>:</label>
<?php
echo $form->input('role_id',
                  array('type' => 'select',
                        'options' => $roles_options,
                        'div' => FALSE,
                        'label' => FALSE,
                        'selected' => $role['Role']['id']));
?>
<label for="tracker_id"><?php __('Tracker'); ?>:</label>
<?php
echo $form->input('tracker_id',
                  array('type' => 'select',
                        'options' => $trackers_options,
                        'div' => FALSE,
                        'label' => FALSE,
                        'selected' => $tracker['Tracker']['id']));
?>
<?php echo $form->submit(__('Edit', TRUE), array('name' => '', 'div' => FALSE)); ?>
</p>
<?php echo $form->end(); ?>
  
<!-- <% unless @tracker.nil? or @role.nil? or @statuses.empty? %> -->
<?php if (! (empty($tracker) || empty($role) || empty($statuses)) ): ?>

<?php echo $form->create('Workflow',array('action' => 'edit', 'id' => 'workflow_form')); ?>
<?php echo $form->input('tracker_id', array('type' => 'hidden', 'value' => $tracker['Tracker']['id'])); ?>
<?php echo $form->input('role_id', array('type' => 'hidden', 'value' => $role['Role']['id'])); ?>
<table class="list">
  <thead>
    <tr>
      <th align="left"><?php __('Current status'); ?></th>
      <th align="center" colspan="<?php echo count($statuses); ?>"><?php __('New statuses allowed'); ?></th>
    </tr>
    <tr>
      <td></td>
      <?php foreach ($statuses as $new_status): ?>
      <td width="<?php echo intval(75 / count($statuses)); ?>%" align="center"><?php echo h($new_status['IssueStatus']['name']); ?></td>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach($statuses as $old_status): ?>
    <tr class="<?php echo $candy->cycle('odd','even'); ?>">
    
      <td><?php echo h($old_status['IssueStatus']['name']); ?></td>
      <!--		<% new_status_ids_allowed = old_status.find_new_statuses_allowed_to(@role, @tracker).collect(&:id) -%> -->
      <?php foreach ($statuses as $new_status): ?>
      <td align="center">
        <input type="checkbox"
               name="data[issue_status][<?php echo h($old_status['IssueStatus']['id']); ?>][]"
        value="<?php echo h($new_status['IssueStatus']['id']); ?>"
        <?php
          if (in_array($new_status['IssueStatus']['id'], $new_status_ids_allowed[$old_status['IssueStatus']['id']])) {
            echo 'checked="checked"';
          }
        ?>
        />
       </td>
      <?php endforeach; ?>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<p><?php echo $candy->check_all_links('workflow_form'); ?></p>

<?php echo $form->submit(__('Save', TRUE)); ?>
<?php echo $form->end(); ?>

<?php endif; ?>

<!-- <% html_title(l(:label_workflow)) -%> -->
