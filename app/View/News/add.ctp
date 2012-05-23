<h2><?php echo __('Add news') ; ?></h2>

<!-- TODO: プロジェクトIDをいれる -->
<?php
echo $this->Form->create('News', array('url' => "/projects/{$project['Project']['identifier']}/news/add", 'id' => 'NewsAddForm'));
	echo $this->element('news/_form');
	echo $this->Form->submit(__('Create'), array('div' => false));
	echo $ajax->link(
		__('Preview'),
		array(
			'action' => 'preview',
//			'project_id' => $this->request->params['project_id']
		),
		array(
			'update' => 'preview',
			'complete' => "Element.scrollTo('preview')",
			'with' => "Form.serialize('NewsAddForm')"
		)
	);
echo $this->Form->end();
?>
<div id="preview" class="wiki"></div>
