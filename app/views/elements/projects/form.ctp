<?php echo $form->error('Project/add') ?>

<div class="box">
<!--[form:project]-->
<p><?php echo $form->input('name',aa('div',false)) ?><br /><em><?php echo $candy->lwr('%d characters maximum.', 30) ?></em></p>
<?php /*
<p><%= f.text_field :name, :required => true %><br /><em><%= l(:text_caracters_maximum, 30) %></em></p>
 */ ?>

<?php if ((count($root_projects) > 0)): ?>
<?php /*
<% if User.current.admin? and !@root_projects.empty? %>
    <p><%= f.select :parent_id, (@root_projects.collect {|p| [p.name, p.id]}), { :include_blank => true } %></p>
 */ ?>
 <p><?php echo $form->input('parent_id', array('type'=>'select', 'options'=>$root_projects, 'div'=>false, 'label'=>__('Subproject of', true))) ?></p>
<?php endif ?>

<p><?php echo $form->input('description', array('class'=>'wiki-edit', 'rows'=>5, 'div' => false )) ?></p>
<p><?php echo $form->input('identifier', array('div' => false, 'disabled' => (isset($main_project) && $project->identifier_frozen($main_project['Project'])))) ?>
<?php /*
<%= f.text_field :identifier, :required => true, :disabled => @project.identifier_frozen? %>
 */ ?>
<?php if ( !isset($main_project) || !$project->identifier_frozen($main_project['Project'])): ?>
<br /><em><?php echo $candy->lwr('Length between %d and %d characters.', 2, 20) ?> <?php __("'Only lower case letters (a-z), numbers and dashes are allowed.<br />Once saved, the identifier can not be changed.'") ?></em>
<?php endif ?>
</p>
<p><?php echo $form->input('homepage', array('size'=>60, 'div'=>false)) ?></p>
<p><label for="ProjectIsPublic"><?php __('Public') ?></label><?php echo $form->input('is_public', array('type'=>'checkbox', 'label'=>false, 'div'=>false)) ?></p>
<?php /*
<%= wikitoolbar_for 'project_description' %>
 */ ?>
<?php
#<% @project.custom_field_values.each do |value| %>
#	<p><%= custom_field_tag_with_label :project, value %></p>
#<% end %>
#<%= call_hook(:view_projects_form, :project => @project, :form => f) %>
?>
</div>

<?php if (count($trackers) > 0): ?>
<?php
  $checked_trackers = array();
  if (isset($main_project)) {
    $checked_trackers = Set::extract('Project/Tracker/id',a($main_project));
  }
?>
<fieldset class="box"><legend><?php __('Trackers') ?></legend>
<?php foreach($trackers as $tracker): ?>
    <label class="floating">
<?php
    $checked = "";
    if ( in_array($tracker['Tracker']['id'],$checked_trackers) ) {
      $checked = 'checked';
    }
    echo $form->input('Tracker][',
                  array('type'=>'checkbox',
                        'value'=>$tracker['Tracker']['id'],
                        'checked' => $checked,
                        'label'=>false,
                        'div' => false
                      )) ?>
<?php echo h($tracker['Tracker']['name']); ?>
<?php /*
    <%= check_box_tag 'project[tracker_ids][]', tracker.id, @project.trackers.include?(tracker) %>
 */ ?>
    </label>
<?php endforeach ?>
<?php //echo $form->input('[tracker_ids][]', array('type'=>'hidden', 'value'=>'', 'div'=>false)) ?>
</fieldset>
<?php endif ?>

<?php if (count($issue_custom_fields) > 0): ?>
<?php
  $checked_cfields = array();
  if (isset($main_project)) {
    $checked_cfields = Set::extract('/CustomField/id',a($main_project));
  }
?>
<fieldset class="box"><legend><?php __('Custom fields') ?></legend>
<?php foreach($issue_custom_fields as $custom_field): ?>
    <label class="floating">
<?php 
    $checked = "";
    if ( in_array($custom_field['IssueCustomField']['id'],$checked_cfields) ) {
      $checked = 'checked';
    }
    echo $form->input('issue_custom_field_ids][',
                  array('type'=>'checkbox',
                        'value'=>$custom_field['IssueCustomField']['id'],
			'checked' => $checked,
                        'label'=>false,
                        'div' => false
                      )) ?>
<?php echo h($custom_field['IssueCustomField']['name']) ?>
<?php /*
	<%= check_box_tag 'project[issue_custom_field_ids][]', custom_field.id, (@project.all_issue_custom_fields.include? custom_field), (custom_field.is_for_all? ? {:disabled => "disabled"} : {}) %>
	<%= custom_field.name %>
 */ ?>
	</label>
<?php endforeach ?>
<?php echo $form->input('[issue_custom_field_ids][]', array('type'=>'hidden', 'value'=>'', 'div'=>false)) ?>
</fieldset>
<?php endif ?>
<!--[eoform:project]-->
