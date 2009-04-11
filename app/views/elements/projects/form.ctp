<%= error_messages_for 'project' %>

<div class="box">
<!--[form:project]-->
<p><?php echo $form->input('name') ?><br /><em><?php echo $candy->lwr('%d characters maximum.', 30) ?></em></p>
<?php /*
<p><%= f.text_field :name, :required => true %><br /><em><%= l(:text_caracters_maximum, 30) %></em></p>
 */ ?>

<?php if ((count($root_projects) > 0)): ?>
<?php /*
<% if User.current.admin? and !@root_projects.empty? %>
    <p><%= f.select :parent_id, (@root_projects.collect {|p| [p.name, p.id]}), { :include_blank => true } %></p>
 */ ?>
 <p><?php echo $form->input('parent_id', array('type'=>'select', 'options'=>$root_projects, 'label'=>__('Subproject of', true))) ?></p>
<?php endif ?>

<p><?php echo $form->input('description', array('class'=>'wiki-edit', 'rows'=>5)) ?></p>
<p><?php echo $form->input('identifier') ?>
<?php /*
<%= f.text_field :identifier, :required => true, :disabled => @project.identifier_frozen? %>
 */ ?>
<?php if (!$project->identifier_frozen($this->data)): ?>
<br /><em><?php echo $candy->lwr('Length between %d and %d characters.', 2, 20) ?> <?php __("'Only lower case letters (a-z), numbers and dashes are allowed.<br />Once saved, the identifier can not be changed.'") ?></em>
<?php endif ?>
</p>
<p><?php echo $form->input('homepage', array('size'=>60)) ?></p>
<p><?php echo $form->input('is_public', array('type'=>'checkbox', 'label'=>__('Public', true))) ?></p>
<?php /*
<%= wikitoolbar_for 'project_description' %>
 */ ?>

<% @project.custom_field_values.each do |value| %>
	<p><%= custom_field_tag_with_label :project, value %></p>
<% end %>
<%= call_hook(:view_projects_form, :project => @project, :form => f) %>
</div>

<?php if (count($trackers) > 0): ?>
<fieldset class="box"><legend><?php __('Trackers') ?></legend>
<?php foreach($trackers as $tracker): ?>
    <label class="floating">
<?php echo $form->input('project[tracker_ids][]',
                  array('type'=>'checkbox',
                        'value'=>$tracker['Tracker']['id'],
                        'label'=>h($tracker['Tracker']['name']),
                      )) ?>
<?php /*
    <%= check_box_tag 'project[tracker_ids][]', tracker.id, @project.trackers.include?(tracker) %>
 */ ?>
    </label>
<?php endforeach ?>
<?php echo $form->input('project[tracker_ids][]', array('type'=>'hidden', 'value'=>'')) ?>
</fieldset>
<?php endif ?>

<?php if (count($issue_custom_fields) > 0): ?>
<fieldset class="box"><legend><?php __('Custom fields') ?></legend>
<?php foreach($issue_custom_fields as $custom_field): ?>
    <label class="floating">
<?php echo $form->input('project[issue_custom_field_ids][]',
                  array('type'=>'checkbox',
                        'value'=>$custom_field['IssueCustomField']['id'],
                        'label'=>h($custom_field['IssueCustomField']['name']),
                      )) ?>
<?php /*
	<%= check_box_tag 'project[issue_custom_field_ids][]', custom_field.id, (@project.all_issue_custom_fields.include? custom_field), (custom_field.is_for_all? ? {:disabled => "disabled"} : {}) %>
	<%= custom_field.name %>
 */ ?>
	</label>
<?php endforeach ?>
<?php echo $form->input('project[issue_custom_field_ids][]', array('type'=>'hidden', 'value'=>'')) ?>
</fieldset>
<?php endif ?>
<!--[eoform:project]-->
