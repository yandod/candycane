<h2><?php echo __('Spent time') ?></h2>

<?php echo $this->Form->create('TimeEntry', array('url'=>'/projects/'.$main_project['Project']['identifier'].'/timelog/edit/' . (isset($this->request->params['url_param']['id']) ? $this->request->params['url_param']['id'] : ''), 'class'=>'tabular')); ?>
  <?php echo $this->element('error_explanation'); ?>
  <?php echo $this->Candy->back_url_hidden_field_tag(); ?>

<div class="box">
<p>
  <?php echo $this->Form->label('issue_id', __('Issue')); ?>
  <?php echo $this->Form->input('issue_id', array('type'=>'text', 'size' => 6, 'div'=>false, 'label'=>false)); ?>
  <em><?php echo !empty($time_entry['Issue']) ? h($time_entry['Issue']['Tracker']['name'].' #'.$time_entry['Issue']['id'].': '.$time_entry['Issue']['subject']) : ''; ?></em>
</p>
<p>
  <?php echo $this->Form->label('spent_on', __('Spent On').'<span class="required"> *</span>'); ?>
  <?php echo $this->Form->input('spent_on', array('type'=>'text', 'size' =>10, 'div'=>false, 'label'=>false)); ?>
  <?php echo $this->Candy->calendar_for('TimeEntrySpentOn'); ?>
</p>
<p>
  <?php echo $this->Form->label('hours', __('Hours').'<span class="required"> *</span>'); ?>
  <?php echo $this->Form->input('hours', array('type'=>'text', 'size' => 6, 'div'=>false, 'label'=>false)); ?>
</p>
<p>
  <?php echo $this->Form->label('comments', __('Comment')); ?>
  <?php echo $this->Form->input('comments', array('type'=>'text', 'size' =>100, 'div'=>false, 'label'=>false)); ?>
</p>
<p>
  <?php echo $this->Form->label('activity_id', __('Activity').'<span class="required"> *</span>'); ?>
  <?php echo $this->Form->input(
	'activity_id',
	array(
		'div' => false,
		'label' => false,
		'type' => 'select',
		'options' => $time_entry_activities,
		'empty' => '--- '.__('Please Select').' ---'
	)
); ?> 
</p>
<?php foreach($time_entry_custom_fields as $value): ?>
  <p><?php echo $this->CustomField->custom_field_tag_with_label('time_entry', $value); ?></p>
<?php endforeach; ?>
<?php /* TODO : call_hook(:view_timelog_edit_form_bottom, { :time_entry => @time_entry, :form => f }) */ ?>
</div>

<?php echo $this->Form->submit(__('Save')); ?>

<?php $this->Form->end(); ?>
