<h2><?php echo __('New file') ?></h2>

<div class="box">
<?php echo $this->Form->create('Attachment', array('url'=>array('controller'=>'projects', 'project_id'=>$this->request->data['Project']['project_id']), 'action'=>'add_file', 'type' => 'file', 'class'=>'tabular')) ?>
<?php echo $this->Form->error('projects/add_file') ?>
<?php /*
<% form_tag({ :action => 'add_file', :id => @project }, :multipart => true, :class => "tabular") do %>
 */ ?>

<?php if (count($versions) > 0): ?>
<p><label for="version_id"><?php echo __('Version') ?></label>
<?php echo $this->Form->input('version_id', array('type'=>'select', 'options'=>$versions, 'label'=>false, 'div'=>false)) ?>
<?php /*
<%= select_tag "version_id", content_tag('option', '') +
														 options_from_collection_for_select(@versions, "id", "name") %></p>
 */ ?>
</p>
<?php endif ?>

<p><label><?php echo __('Files') ?></label><?php echo $this->element('attachments/form') ?></p>
</div>
<?php echo $this->Form->submit(__('Add')) ?>

<?php echo $this->Form->end() ?>

