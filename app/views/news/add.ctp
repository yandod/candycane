<h2><?php __('Add news') ; ?></h2>

<!-- TODO: プロジェクトIDをいれる -->
<?php
echo $form->create('News', array('url' => "/projects/{$project['Project']['identifier']}/news/add", 'id' => 'NewsAddForm'));
	echo $this->renderElement('news/_form');
	echo $form->submit(__('Create', true), array('div' => false));
	echo $ajax->link(
		__('Preview', true),
		array(
			'action' => 'preview',
//			'project_id' => $this->params['project_id']
		),
		array(
			'update' => 'preview',
			'complete' => "Element.scrollTo('preview')",
			'with' => "Form.serialize('NewsAddForm')"
		)
	);
echo $form->end();
?>
<div id="preview" class="wiki"></div>
