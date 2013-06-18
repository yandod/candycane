<?php if (isset($trackers)): ?>
	<p>
		<?php echo $this->Form->label('tracker_id', __('Tracker').'<span class="required"> *</span>'); ?>
		<?php echo $this->Form->input('tracker_id', array('div'=>false, 'label'=>false)); ?></p>
	</p>
	<?php 
	echo $this->Js->get('#IssueTrackerId')->event('change', $this->Js->request(
		'/projects/' . $main_project['Project']['identifier'] . '/issues/update_form',
		array( 
			'update' => '#attributes', 
			'async' => true, 
			'dataExpression' => true, 
			'method' => 'post', 
			'data' => $this->Js->serializeForm(array('isForm' => false, 'inline' => true)) 
		)
	));
	echo $this->Js->writeBuffer();
	?>
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

<div id="attributes" class="attributes">
	<?php echo $this->element('issues/attribute') ?>
</div>

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
<?php echo $this->Html->script(array('jstoolbar/jstoolbar','jstoolbar/textile','jstoolbar/lang/jstoolbar-ja')); ?>

<script type="text/javascript">
//<![CDATA[
var toolbar = new jsToolBar($('description')); toolbar.setHelpLink('<?php echo __("Text formatting");?>: <a href="/help/wiki_syntax.html?1236399200" onclick="window.open(&quot;/help/wiki_syntax.html?1236399200&quot;, &quot;&quot;, &quot;resizable=yes, location=no, width=300, height=640, menubar=no, status=no, scrollbars=yes&quot;); return false;"><?php echo __("Help"); ?></a>'); toolbar.draw();
//]]>
</script>
