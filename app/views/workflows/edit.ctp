<div class="contextual">
<?php echo $html->link(__('Summary', TRUE), array('action' => 'index')); ?>
</div>

<h2><?php __('Workflow'); ?></h2>

<p><?php __('Select a role and a tracker to edit the workflow'); ?>:</p>

<?php echo $form->create('Workflow', array('type' => 'get','action' => 'edit')); ?>
<p><label for="role_id"><?php __('Role'); ?>:</label>
<!-- <select name="role_id">  -->
  <!-- <%= options_from_collection_for_select @roles, "id", "name", (@role.id unless @role.nil?) %> -->
<!-- </select> -->
<?php echo $form->input('role_id',array('type' => 'select', 'options' => $roles_options,'div' => FALSE, 'label' => FALSE)); ?>


<label for="tracker_id"><?php __('Tracker'); ?>:</label>
<!-- <select name="tracker_id">
  <%= options_from_collection_for_select @trackers, "id", "name", (@tracker.id unless @tracker.nil?) %>
</select> -->
   <?php echo $form->input('tracker_id', array('type' => 'select','options' => $trackers_options, 'div' => FALSE, 'label' => FALSE)); ?>

<!-- <%= submit_tag l(:button_edit), :name => nil %> -->
<?php echo $form->submit(__('Edit', TRUE), array('name' => '', 'div' => FALSE)); ?>
</p>
<!-- <% end %> -->
<?php echo $form->end(); ?>
  
<!-- <% unless @tracker.nil? or @role.nil? or @statuses.empty? %> -->
<?php if (TRUE): ?>

<!-- <% form_tag({}, :id => 'workflow_form' ) do %> -->
<?php echo $form->create('Workflow'); ?>
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
<?php
//  <tr class="<%= cycle("odd", "even") %>">
?>
    <tr class="">

      <td><?php echo h($old_status['IssueStatus']['name']); ?></td>
<!--		<% new_status_ids_allowed = old_status.find_new_statuses_allowed_to(@role, @tracker).collect(&:id) -%> -->
      <?php foreach ($statuses as $new_status): ?>
			<td align="center">
      <input type="checkbox"
      name="issue_status[<?php echo h($old_status['IssueStatus']['id']); ?>][]"
      value="<?php echo h($new_status['IssueStatus']['id']); ?>"
      <?php echo 'checked="checked"' ?> />
      <!-- <%= 'checked="checked"' if new_status_ids_allowed.include? new_status.id %> />	-->
			</td>
      <?php endforeach; ?>
		</tr>
   <?php endforeach; ?>
</tbody>
</table>
<p><%= check_all_links 'workflow_form' %></p>

<?php echo $form->submit(__('Save', TRUE)); ?>
<?php echo $form->end(); ?>

<?php endif; ?>

<!-- <% html_title(l(:label_workflow)) -%> -->
