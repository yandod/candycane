<?php echo $this->Form->create('Issue', array('url'=>array('action'=>'edit', $issue['Issue']['id']), 'enctype'=>"multipart/form-data", 'id'=>'issue-form')); ?>
  <?php echo $this->element('error_explanation'); ?>
  <div class="box">
  <?php if($this->Candy->authorize_for('edit_issues') && !empty($allowed_statuses)): ?>
  <fieldset class="tabular">
    <legend><?php echo __('Change properties'); ?>
      <?php if(!empty($issue['Issue']['id']) && empty($this->validationErrors['Issue']) && $this->Candy->authorize_for(':edit_issues')): ?>
      <small>(<?php echo $this->Html->link(__('More'), '#', array('onclick'=> 'Effect.toggle("issue_descr_fields", "appear", {duration:0.3}); return false;')); ?>)</small>
      <?php endif; ?>
    </legend>
    <?php
    if($this->Candy->authorize_for('edit_issues')) {
      echo $this->element('issues/form', compact(
        'statuses', 'priorities', 'assignableUsers', 'issueCategories',
        'fixedVersions', 'customFieldValues'));
    } else {
      // render 'form_update'
    }
    ?>
  </fieldset>
  <?php endif; ?>
  <?php if($this->Candy->authorize_for(array('controller'=>'timelog', 'action'=>'edit'))): ?>
  <fieldset class="tabular"><legend><?php echo __('Log time') ?></legend>
    <div class="splitcontentleft">
      <p>
        <?php echo $this->Form->label('TimeEntry.hours', __('Spent time')); ?>
        <?php echo $this->Form->input('TimeEntry.hours', array('div'=>false, 'label'=>false, 'size'=>6, 'type'=>'text', 'required' => false));__('Hours'); ?>
      </p>
    </div>
    <div class="splitcontentright">
      <p>
        <?php echo $this->Form->label('TimeEntry.activity_id', __('Activity')); ?>
        <?php echo $this->Form->input('TimeEntry.activity_id', array('div'=>false, 'label'=>false, 'type'=>'select', 'required' => false, 'options'=>$time_entry_activities, 'empty'=>'--- '.__('Please Select').' ---')); ?>
      </p>
    </div>
    <p>
      <?php echo $this->Form->label('TimeEntry.comments', __('Comment')); ?>
      <?php echo $this->Form->input('TimeEntry.comments', array('div'=>false, 'label'=>false, 'size'=>60, 'type'=>'text', 'id'=>"time_entry_comments", 'required'=>false));?>
    </p>
    <?php foreach($time_entry_custom_fields as $value): ?>
      <p><?php echo $this->CustomField->custom_field_tag_with_label('time_entry', $value); ?></p>
    <?php endforeach; ?>
  </fieldset>
  <?php endif; ?>

  <fieldset><legend><?php echo __('Notes') ?></legend>
    <?php echo $this->Form->input('notes', array('div'=>false, 'label'=>false, 'cols'=>60, 'rows'=>'10', 'type'=>'textarea', 'class'=>'wiki-edit', 'id'=>'notes')); ?>
    <?php echo $this->Html->script(array('jstoolbar/jstoolbar', 'jstoolbar/textile', 'jstoolbar/lang/jstoolbar-ja')); ?>
<script type="text/javascript">
//<![CDATA[
var toolbar = new jsToolBar($('notes')); toolbar.setHelpLink('<?php echo __("Text formatting");?>: <a href="/help/wiki_syntax.html?1236399200" onclick="window.open(&quot;/help/wiki_syntax.html?1236399200&quot;, &quot;&quot;, &quot;resizable=yes, location=no, width=300, height=640, menubar=no, status=no, scrollbars=yes&quot;); return false;"><?php echo __("Help"); ?></a>'); toolbar.draw();
//]]>
</script>

    <p>
      <label><?php echo __('File'); ?></label><br />
      <?php echo $this->element('attachments/form'); ?>
    </p>
  </fieldset>
  </div>

  <?php echo $this->Form->hidden('lock_version'); ?>
  <?php echo $this->Form->submit(__('Submit'), array('div'=>false)); ?>
  <?php echo $this->Js->link(__('Preview'), array(
		'controller' => 'issues',
		'action' => 'preview',
		'project_id' => $main_project['Project']['identifier'],
		$issue['Issue']['id']
	), array(
    'update'=>'preview',
    'data'=>'Form.serialize("issue-form")',
    'dataExpression' => true,
    'buffer' => false,
    'complete'=>"Element.scrollTo('preview')",
    'accesskey'=>'r'
  ));?>
<?php echo $this->Form->end(); ?>
<div id="preview" class="wiki"></div>
