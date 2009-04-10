<h2><?php __('New file') ?></h2>

<%= error_messages_for 'attachment' %>
<div class="box">
<?php echo $form->create('Project', array('action'=>'add_file', array('enctype' => 'multipart/form-data'), 'class'=>'tabular')) ?>
<?php /*
<% form_tag({ :action => 'add_file', :id => @project }, :multipart => true, :class => "tabular") do %>
 */ ?>

<% if @versions.any? %>
<p><label for="version_id"><?php __('Version') ?></label>
<%= select_tag "version_id", content_tag('option', '') +
														 options_from_collection_for_select(@versions, "id", "name") %></p>
<% end %>

<p><label><?php __('Files') ?></label><?php echo $this->element('attachments/form') ?></p>
</div>
<?php echo $form->submit(__('Add', true)) ?>

<?php echo $form->end() ?>

