<h2><?php __('Add news') ; ?></h2>

<!-- TODO: プロジェクトIDをいれる -->
<?php echo $form->create('News', array('url' => "/projects/{$project['Project']['identifier']}/news/add")) ; ?>
<?php echo $this->renderElement('news/_form') ; ?>
<?php echo $form->submit( __('Create',true), aa('div', false) ) ; ?>
<?php echo __('Preview',true); ?>
<?php echo $form->end(); ?>
<div id="preview" class="wiki"></div>
