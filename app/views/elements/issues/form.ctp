    <?php if(isset($trackers)): ?>
    <p>
      <?php echo $form->label('tracker_id', __('Tracker', true).'<span class="required"> *</span>'); ?>
      <?php echo $form->input('tracker_id', array('div'=>false, 'label'=>false)); ?></p>
    </p>
    <?php echo $ajax->observeField('IssueTrackerId', array(
        'url'=>'/projects/'.$mainProject["Project"]["identifier"].'/issues/add', 
        'update'=>'content',
        'allowCache'=>false,
        'with'=>'Form.serialize(\'IssueAddForm\')'
        )); ?>
    <hr />
    <?php endif; ?>
    <div id="issue_descr_fields" <?php if($this->action=='show') echo 'style="display: none;"'; ?>>
      <p>
        <?php echo $form->label('subject', __('Subject', true).'<span class="required"> *</span>'); ?>
        <?php echo $form->input('subject', array('div'=>false, 'label'=>false, 'size'=>80)); ?></p>
      </p>
      <p>
        <?php echo $form->label('description', __('Description', true)); ?>
        <?php echo $form->input('description', array('type'=>'text', 'cols'=>"60", 'div'=>false, 'label'=>false, 'class'=>"wiki-edit", 'id'=>'description')); ?></p>
      </p>
    </div>
    <div class="splitcontentleft">
      <p>
        <?php echo $form->label('status_id', __('Status', true).'<span class="required"> *</span>'); ?>
        <?php echo $form->input('status_id', array('div'=>false, 'label'=>false)); ?></p>
      </p>
      <p>
        <?php echo $form->label('priority_id', __('Priority', true).'<span class="required"> *</span>'); ?>
        <?php echo $form->input('priority_id', array('div'=>false, 'label'=>false)); ?>
      </p>
      <p>
        <?php echo $form->label('assigned_to_id', __('Assigned to', true)); ?>
        <?php echo $form->input('assigned_to_id', array('type'=>'select', 'div'=>false, 'label'=>false, 'empty'=>true, 'options'=>$assignableUsers)); ?>
      </p>
      <p>
        <?php echo $form->label('category_id', __('Category', true)); ?>
        <?php echo $form->input('category_id', array('type'=>'select', 'div'=>false, 'label'=>false, 'empty'=>true, 'options'=>$issueCategories)); ?>
        <?php
          if ($candy->authorize_for(array('controller'=>'projects', 'action'=>'add_issue_category'))) {
            $add_issue_category_url = $html->url(array('controller'=>'projects', 'action'=>'add_issue_category', 'project_id'=>$this->params['project_id']));
            echo $html->link(__('New category',true), array('action'=>'add', 'project_id'=>$this->params['project_id']), array('class'=>"small", 'onclick'=>"promptToRemote('".__('New category',true)."', 'data[IssueCategory][name]', '{$add_issue_category_url}'); return false;", 'tabindex'=>"199") );
          }
        ?>
      </p>
      <p>
        <?php echo $form->label('fixed_version_id', __('Target version', true)); ?>
        <?php echo $form->input('fixed_version_id', array('type'=>'select', 'div'=>false, 'label'=>false, 'empty'=>true, 'options'=>$fixedVersions)); ?>
      </p>
    </div>
    <div class="splitcontentright">
      <p>
        <?php echo $form->label('start_date', __('start_date', true)); ?>
        <?php echo $form->input('start_date', array('div'=>false, 'label'=>false, 'size'=>10, 'type'=>'text')); ?>
        <?php echo $candy->calendar_for('IssueStartDate'); ?>
      </p>
      <p>
        <?php echo $form->label('due_date', __('due_date', true)); ?>
        <?php echo $form->input('due_date', array('div'=>false, 'label'=>false, 'size'=>10, 'type'=>'text')); ?>
        <?php echo $candy->calendar_for('IssueDueDate'); ?>
      </p>
      <p>
        <?php echo $form->label('estimated_hours', __('estimated_hours', true)); ?>
        <?php echo $form->input('estimated_hours', array('div'=>false, 'label'=>false, 'size'=>10, 'type'=>'text'));__('Hours');?>
      </p>
      <p>
        <?php echo $form->label('done_ratio', __('done_ratio', true).'%'); ?>
        <?php echo $form->input('done_ratio', array('type'=>'select', 'div'=>false, 'label'=>false, 'options'=>array(0=>'0 %', 10=>'10 %', 20=>'20 %', 30=>'30 %', 40=>'40 %', 50=>'50 %', 60=>'60 %', 70=>'70 %', 80=>'80 %', 90=>'90 %', 100=>'100 %'))); ?>
      </p>
    </div>
    <div style="clear:both;"> </div>
    <div class="splitcontentleft">
      <?php $i = 0; ?>
      <?php $split_on = intval(count($customFieldValues) / 2); ?>
      <?php foreach($customFieldValues as $value): ?>
        <p><?php echo $customField->custom_field_tag_with_label($form, 'issue', $value); ?></p>
        <?php if($i == $split_on): ?>
          </div><div class="splitcontentright">
        <?php endif; ?>
        <?php $i++; ?>
      <?php endforeach; ?>
    </div>
    <div style="clear:both;"> </div>
    <?php if(empty($this->data['Issue']['id'])): ?>
      <p>
        <label><?php __('File'); ?></label>
        <?php echo $this->renderElement('attachments/form'); ?>
      </p>
    <?php endif; ?>
    <?php if(empty($this->data['Issue']['id']) && $candy->authorize_for('add_issue_watchers') && isset($members)): ?>
      <p>
        <label><?php __('Watchers'); ?></label>
        <?php
          $_tag = $form->Html->tags['tag'];
          $_label = $form->Html->tags['label'];
          $_checkboxmultiple = $form->Html->tags['checkboxmultiple'];
          $form->Html->tags['tag'] = '%3$s';
          $form->Html->tags['label'] = '%3$s</label>';
          $form->Html->tags['checkboxmultiple'] = '<label class="floating">'.$form->Html->tags['checkboxmultiple'];
          echo $form->input('watcher_user_ids', array('type'=>'select', 'multiple'=>'checkbox', 'div'=>false, 'label'=>false, 'options'=>$members));
          $form->Html->tags['tag'] = $_tag;
          $form->Html->tags['label'] = $_label;
          $form->Html->tags['checkboxmultiple'] = $_checkboxmultiple;
        ?>
      </p>
    <?php endif; ?>
    <script src="/js/jstoolbar/jstoolbar.js?1236399204" type="text/javascript"></script><script src="/js/jstoolbar/textile.js?1236399204" type="text/javascript"></script><script src="/js/jstoolbar/lang/jstoolbar-ja.js?1236399204" type="text/javascript"></script><script type="text/javascript">
//<![CDATA[
var toolbar = new jsToolBar($('description')); toolbar.setHelpLink('<?php __("Text formatting");?>: <a href="/help/wiki_syntax.html?1236399200" onclick="window.open(&quot;/help/wiki_syntax.html?1236399200&quot;, &quot;&quot;, &quot;resizable=yes, location=no, width=300, height=640, menubar=no, status=no, scrollbars=yes&quot;); return false;"><?php __("Help"); ?></a>'); toolbar.draw();
//]]>
</script>
