<?php if (isset($trackers)): ?>
	<p>
		<?php echo $this->Form->label('tracker_id', __('Tracker').'<span class="required"> *</span>'); ?>
		<?php echo $this->Form->input('tracker_id', array('div'=>false, 'label'=>false)); ?></p>
	</p>
	<?php 
	// TODO: observe field
	//echo $this->Js->observeField('IssueTrackerId', array(
	//	'url' => '/projects/' . $mainProject["Project"]["identifier"] . '/issues/add', 
	//	'update' => 'content',
	//	'allowCache' => false,
	//	'with' => 'Form.serialize(\'IssueAddForm\')'
	//)); ?>
	<hr />
<?php endif; ?>

<div id="issue_descr_fields" <?php if ($this->request->action == 'show') echo 'style="display: none;"'; ?>>
	<p>
		<?php echo $this->Form->label('subject', __('Subject').'<span class="required"> *</span>'); ?>
		<?php echo $this->Form->input('subject', array('div' => false, 'label' => false, 'size' => 80)); ?></p>
	</p>
	<p>
		<?php echo $this->Form->label('description', __('Description')); ?>
		<?php echo $this->Form->input('description', array('type' => 'text', 'cols' => "60", 'div' => false, 'label' => false, 'class' => "wiki-edit", 'id' => 'description')); ?></p>
	</p>
</div>

<div class="splitcontentleft">
	<p>
		<?php echo $this->Form->label('status_id', __('Status').'<span class="required"> *</span>'); ?>
		<?php echo $this->Form->input('status_id', array('div' => false, 'label' => false)); ?></p>
	</p>
	<p>
		<?php echo $this->Form->label('priority_id', __('Priority').'<span class="required"> *</span>'); ?>
		<?php echo $this->Form->input('priority_id', array('div' => false, 'label' => false)); ?>
	</p>
	<p>
		<?php echo $this->Form->label('assigned_to_id', __('Assigned to')); ?>
		<?php echo $this->Form->input('assigned_to_id', array('type' => 'select', 'div' => false, 'label' => false, 'empty' => true, 'options' => $assignable_users)); ?>
	</p>
	<p>
		<?php echo $this->Form->label('category_id', __('Category')); ?>
		<?php echo $this->Form->input('category_id', array('type' => 'select', 'div' => false, 'label' => false, 'empty' => true, 'options' => $issue_categories)); ?>
		<?php
		if ($this->Candy->authorize_for(array('controller' => 'projects', 'action' => 'add_issue_category'))) {
			$add_issue_category_url = $this->Html->url(array(
				'controller' => 'projects',
				'action' => 'add_issue_category',
				'project_id' => $this->request->params['project_id']));
			echo $this->Html->link(__('New category'),
				array(
					'action' => 'add',
					'project_id' => $this->request->params['project_id']),
				array(
					'class' => "small",
					'onclick' => "promptToRemote('".__('New category')."','data[IssueCategory][name]', '{$add_issue_category_url}'); return false;",
					'tabindex' => "199"));
		}
		?>
	</p>
	<p>
		<?php echo $this->Form->label('fixed_version_id', __('Target version')); ?>
		<?php echo $this->Form->input('fixed_version_id', array('type' => 'select', 'div' => false, 'label' => false, 'empty' => true, 'options' => $fixed_versions)); ?>
	</p>
</div>

<div class="splitcontentright">
	<p>
		<?php echo $this->Form->label('start_date', __('start_date')); ?>
		<?php echo $this->Form->input('start_date', array('div' => false, 'label' => false, 'size' => 10, 'type' => 'text')); ?>
		<?php echo $this->Candy->calendar_for('IssueStartDate'); ?>
	</p>
	<p>
		<?php echo $this->Form->label('due_date', __('due_date')); ?>
		<?php echo $this->Form->input('due_date', array('div' => false, 'label' => false, 'size' => 10, 'type' => 'text')); ?>
		<?php echo $this->Candy->calendar_for('IssueDueDate'); ?>
	</p>
	<p>
		<?php echo $this->Form->label('estimated_hours', __('estimated_hours')); ?>
		<?php echo $this->Form->input('estimated_hours', array('div' => false, 'label' => false, 'size' => 10, 'type' => 'text')); __('Hours');?>
	</p>
	<p>
		<?php echo $this->Form->label('done_ratio', __('done_ratio').'%'); ?>
		<?php echo $this->Form->input('done_ratio', array('type' => 'select', 'div' => false, 'label' => false, 'options' => array(
			0   => '0 %',
			10  => '10 %',
			20  => '20 %',
			30  => '30 %',
			40  => '40 %',
			50  => '50 %',
			60  => '60 %',
			70  => '70 %',
			80  => '80 %',
			90  => '90 %',
			100 => '100 %'))); ?>
	</p>
</div>
<div style="clear:both;"> </div>

<div class="splitcontentleft">
	<?php $i = 0; ?>
	<?php $split_on = intval(count($custom_field_values) / 2); ?>
	<?php foreach ($custom_field_values as $value): ?>
		<p><?php echo $this->CustomField->custom_field_tag_with_label('issue', $value); ?></p>
		<?php if($i == $split_on): ?>
			</div><div class="splitcontentright">
		<?php endif; ?>
		<?php $i++; ?>
	<?php endforeach; ?>
</div>
<div style="clear:both;"> </div>

<?php if (empty($this->request->data['Issue']['id'])): ?>
	<p>
		<label><?php echo __('File'); ?></label>
		<?php echo $this->element('attachments/form'); ?>
	</p>
<?php endif; ?>

<?php if (empty($this->request->data['Issue']['id']) && $this->Candy->authorize_for('add_issue_watchers') && isset($members)): ?>
	<p>
		<label><?php echo __('Watchers'); ?></label>
		<?php
		$this->Form->Html->loadConfig('checkbox.php', APP . 'Config' . DS);
		echo $this->Form->input(
			'watcher_user_ids',
			array(
				'type' => 'select',
				'multiple'=> 'checkbox',
				'div' => false,
				'label' => false,
				'options' => $members
			)
		);
		$this->Form->Html->loadConfig('checkbox_origin.php', APP . 'Config' . DS);
		?>
	</p>
<?php endif; ?>

<script src="/js/jstoolbar/jstoolbar.js?1236399204" type="text/javascript"></script>
<script src="/js/jstoolbar/textile.js?1236399204" type="text/javascript"></script>
<script src="/js/jstoolbar/lang/jstoolbar-ja.js?1236399204" type="text/javascript"></script>
<script type="text/javascript">
//<![CDATA[
var toolbar = new jsToolBar($('description')); toolbar.setHelpLink('<?php echo __("Text formatting");?>: <a href="/help/wiki_syntax.html?1236399200" onclick="window.open(&quot;/help/wiki_syntax.html?1236399200&quot;, &quot;&quot;, &quot;resizable=yes, location=no, width=300, height=640, menubar=no, status=no, scrollbars=yes&quot;); return false;"><?php echo __("Help"); ?></a>'); toolbar.draw();
//]]>
</script>
