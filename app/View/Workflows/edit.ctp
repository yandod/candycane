<?php
if( $currentuser[ 'admin' ] )
{
	$this->set( 'Sidebar', $this->element( 'admin/sidebar' ) );
}
?>

<div class="contextual">
  <?php echo $this->Html->link(__('Summary', TRUE), array('action' => 'index')); ?>
</div>

<h2><?php echo __('Workflow'); ?></h2>

<p><?php echo __('Select a role and a tracker to edit the workflow'); ?>:</p>

<?php echo $this->Form->create('Workflow', array('type' => 'get','action' => 'edit')); ?>
<p><label for="role_id"><?php echo __('Role'); ?>:</label>
<?php
echo $this->Form->input('role_id',
                  array('type' => 'select',
                        'options' => $roles_options,
                        'div' => FALSE,
                        'label' => FALSE,
                        'selected' => $role['Role']['id']));
?>
<label for="tracker_id"><?php echo __('Tracker'); ?>:</label>
<?php
echo $this->Form->input('tracker_id',
                  array('type' => 'select',
                        'options' => $trackers_options,
                        'div' => FALSE,
                        'label' => FALSE,
                        'selected' => $tracker['Tracker']['id']));
?>
<?php echo $this->Form->submit(__('Edit', TRUE), array('name' => '', 'div' => FALSE)); ?>
</p>
<?php echo $this->Form->end(); ?>
  
<!-- <% unless @tracker.nil? or @role.nil? or @statuses.empty? %> -->
<?php if (! (empty($tracker) || empty($role) || empty($statuses)) ): ?>

<?php echo $this->Form->create('Workflow',array('action' => 'edit', 'id' => 'workflow_form')); ?>
<?php echo $this->Form->input('tracker_id', array('type' => 'hidden', 'value' => $tracker['Tracker']['id'])); ?>
<?php echo $this->Form->input('role_id', array('type' => 'hidden', 'value' => $role['Role']['id'])); ?>
<table class="list">
  <thead>
    <tr>
      <th align="left"><?php echo __('Current status'); ?></th>
      <th align="center" colspan="<?php echo count($statuses); ?>"><?php echo __('New statuses allowed'); ?></th>
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
    <tr class="<?php echo $this->Candy->cycle('odd','even'); ?>">
    
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
<p><?php echo $this->Candy->check_all_links('workflow_form'); ?></p>

<?php echo $this->Form->submit(__('Save', TRUE)); ?>
<?php echo $this->Form->end(); ?>

<?php endif; ?>

<!-- <% html_title(l(:label_workflow)) -%> -->
