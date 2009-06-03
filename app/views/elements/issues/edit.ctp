<?php echo $form->create('Issue', array('url'=>array('action'=>'edit', 'id'=>$issue['Issue']['id']), 'enctype'=>"multipart/form-data", 'id'=>'issue-form')); ?>
  <?php echo $this->renderElement('error_explanation'); ?>
  <div class="box">
  <?php if($candy->authorize_for(':edit_issues') || !empty($allowedStatuses)): ?>
  <fieldset class="tabular">
    <legend><?php __('Change properties'); ?>
      <?php if(!empty($issue['Issue']['id']) && empty($this->validationErrors['Issue']) && $candy->authorize_for(':edit_issues')): ?>
      <small>(<?php echo $html->link(__('More',true), '#', array('onclick'=> 'Effect.toggle("issue_descr_fields", "appear", {duration:0.3}); return false;')); ?>)</small>
      <?php endif; ?>
    </legend>
    <?php 
    if($candy->authorize_for(':edit_issues')) {
      echo $this->renderElement('issues/form', compact(
        'statuses', 'priorities', 'assignableUsers', 'issueCategories', 
        'fixedVersions', 'customFieldValues'));
    } else {
      // render 'form_update'
    }
    ?>
  </fieldset>
  <?php endif; ?>
  <?php if($candy->authorize_for(array('controller'=>'timelog', 'action'=>'edit'))): ?>
  <fieldset class="tabular"><legend><?php __('Log time') ?></legend>
    <div class="splitcontentleft">
      <p>
        <?php echo $form->label('TimeEntry.hours', __('Spent time', true)); ?>
        <?php echo $form->input('TimeEntry.hours', array('div'=>false, 'label'=>false, 'size'=>6, 'type'=>'text'));__('Hours'); ?> 
      </p>
    </div>
    <div class="splitcontentright">
      <p>
        <?php echo $form->label('TimeEntry.activity_id', __('Activity', true)); ?>
        <?php echo $form->input('TimeEntry.activity_id', array('div'=>false, 'label'=>false, 'type'=>'select', 'options'=>$timeEntryActivities, 'empty'=>'--- '.__('Please Select', true).' ---')); ?> 
      </p>
    </div>
    <p>
      <?php echo $form->label('TimeEntry.comments', __('Comment', true)); ?>
      <?php echo $form->input('TimeEntry.comments', array('div'=>false, 'label'=>false, 'size'=>60, 'type'=>'text', 'id'=>"time_entry_comments"));?> 
    </p>
    <?php foreach($timeEntryCustomFields as $value): ?>
      <p><?php echo $customField->custom_field_tag_with_label($form, 'time_entry', $value); ?></p>
    <?php endforeach; ?>
  </fieldset>
  <?php endif; ?>

  <fieldset><legend><?php __('Notes') ?></legend>
    <?php echo $form->input('notes', array('div'=>false, 'label'=>false, 'cols'=>60, 'rows'=>'10', 'type'=>'textarea', 'class'=>'wiki-edit', 'id'=>'notes')); ?>
    <script src="/js/jstoolbar/jstoolbar.js?1236399204" type="text/javascript"></script><script src="/js/jstoolbar/textile.js?1236399204" type="text/javascript"></script><script src="/js/jstoolbar/lang/jstoolbar-ja.js?1236399204" type="text/javascript"></script><script type="text/javascript">
//<![CDATA[
var toolbar = new jsToolBar($('notes')); toolbar.setHelpLink('<?php __("Text formatting");?>: <a href="/help/wiki_syntax.html?1236399200" onclick="window.open(&quot;/help/wiki_syntax.html?1236399200&quot;, &quot;&quot;, &quot;resizable=yes, location=no, width=300, height=640, menubar=no, status=no, scrollbars=yes&quot;); return false;"><?php __("Help"); ?></a>'); toolbar.draw();
//]]>
</script>

    <p>
      <label><?php __('File'); ?></label><br />
      <?php echo $this->renderElement('attachments/form'); ?>
    </p>
  </fieldset>
  </div>

  <?php echo $form->hidden('lock_version'); ?>
  <?php echo $form->submit(__('Submit',true), array('div'=>false)); ?>
  <?php echo $ajax->link(__('Preview',true), '#', array(
    'update'=>'preview',
    'url'=>'/projects/'.$main_project['Project']['identifier'].'/issues/preview/'.$issue['Issue']['id'],
    'with'=>'Form.serialize("issue-form")',
    'complete'=>"Element.scrollTo('preview')",
    'accesskey'=>'r'
  ));?>
<?php echo $form->end(); ?>
<div id="preview" class="wiki"></div>