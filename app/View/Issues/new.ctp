<h2><?php $this->Candy->html_title(); __('New issue'); ?></h2>
<?php echo $this->Form->create('Issue', array(
	'url' => '/projects/' . $main_project['Project']['identifier'] . '/issues/add',
	'class' => "tabular",
	'enctype' => "multipart/form-data",
	'id' => 'IssueAddForm')); ?>

	<?php echo $this->element('error_explanation'); ?>
	<div class="box">
		<?php echo $this->element('issues/form', compact(
			'trackers', 'statuses', 'priorities', 'assignableUsers', 'issueCategories', 
				'fixedVersions', 'customFieldValues', 'members')); ?>
	</div>
	<?php echo $this->Form->submit(__('Create'), array('div' => false)); ?>
	<?php echo $this->Form->submit(__('Create and continue'), array('div' => false, 'name' => 'continue')); ?>
	<?php echo $this->Js->link(__('Preview'),
		array(
			'action' => 'preview',
			'project_id' => $this->request->params['project_id']),
		array(
			'update' => 'preview',
			'complete' => "Element.scrollTo('preview')",
			'buffer' => false,
			'dataExpression' => true,
			'data' => "Form.serialize('IssueAddForm')")); ?>

	<script type="text/javascript">
	//<![CDATA[
	Form.Element.focus('IssueSubject');
	//]]>
	</script>
<?php echo $this->Form->end(); ?>
<div id="preview" class="wiki"></div>

    </div>
</div>
