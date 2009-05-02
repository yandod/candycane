<h2><?php __('Spent time') ?></h2>

<?php echo $form->create('TimeEntry', array('url'=>'/projects/'.$main_project['Project']['identifier'].'/timelog/edit', 'class'=>'tabular')); ?>
  <?php echo $this->renderElement('error_explanation'); ?>
  <?php echo $candy->back_url_hidden_field_tag($form); ?>

<div class="box">
<p>
  <?php echo $form->label('issue_id', __('Issue', true)); ?>
  <?php echo $form->input('issue_id', array('type'=>'text', 'size' => 6, 'div'=>false, 'label'=>false)); ?>
  <em><?php echo !empty($time_entry['Issue']) ? h($time_entry['Issue']['Tracker']['name'].' #'.$time_entry['Issue']['id'].': '.$time_entry['Issue']['subject']) : ''; ?></em>
</p>
<p>
  <?php echo $form->label('spent_on', __('Spent On', true).'<span class="required"> *</span>'); ?>
  <?php echo $form->input('spent_on', array('type'=>'text', 'size' =>10, 'div'=>false, 'label'=>false)); ?>
  <?php echo $candy->calendar_for('TimeEntrySpentOn'); ?>
</p>
<p>
  <?php echo $form->label('hours', __('Hours', true).'<span class="required"> *</span>'); ?>
  <?php echo $form->input('hours', array('type'=>'text', 'size' => 6, 'div'=>false, 'label'=>false)); ?>
</p>
<p>
  <?php echo $form->label('comments', __('Comment', true)); ?>
  <?php echo $form->input('comments', array('type'=>'text', 'size' =>100, 'div'=>false, 'label'=>false)); ?>
</p>
<p>
  <?php echo $form->label('activity_id', __('Activity', true).'<span class="required"> *</span>'); ?>
  <?php echo $form->input('activity_id', array('div'=>false, 'label'=>false, 'type'=>'select', 'options'=>$timeEntryActivities, 'empty'=>'--- '.__('Please Select', true).' ---')); ?> 
</p>
<?php foreach($timeEntryCustomFields as $value): ?>
  <p><?php echo $customField->custom_field_tag_with_label($form, 'time_entry', $value); ?></p>
<?php endforeach; ?>
<?php /* TODO : call_hook(:view_timelog_edit_form_bottom, { :time_entry => @time_entry, :form => f }) */ ?>
</div>

<?php echo $form->submit(__('Save',true)); ?>

<?php $form->end(); ?>
