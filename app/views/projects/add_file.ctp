<h2><?php __('New file') ?></h2>

<div class="box">
<?php echo $form->create('Attachment', array('url'=>array('controller'=>'projects', 'project_id'=>$this->data['Project']['project_id']), 'action'=>'add_file', 'type' => 'file', 'class'=>'tabular')) ?>
<?php echo $form->error('projects/add_file') ?>
<?php /*
<% form_tag({ :action => 'add_file', :id => @project }, :multipart => true, :class => "tabular") do %>
 */ ?>

<?php if (count($versions) > 0): ?>
<p><label for="version_id"><?php __('Version') ?></label>
<?php echo $form->input('version_id', array('type'=>'select', 'options'=>$versions, 'label'=>false, 'div'=>false)) ?>
<?php /*
<%= select_tag "version_id", content_tag('option', '') +
														 options_from_collection_for_select(@versions, "id", "name") %></p>
 */ ?>
</p>
<?php endif ?>

<p><label><?php __('Files') ?></label><?php echo $this->element('attachments/form') ?></p>
</div>
<?php echo $form->submit(__('Add', true)) ?>

<?php echo $form->end() ?>

