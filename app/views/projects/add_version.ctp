<h2><?php __('New version') ?></h2>

<?php echo $form->create('Version', array('url'=>array('controller'=>'projects'), 'action'=>'add_version')) ?>

<div class="box">
<p><?php echo $form->input('project_id', array('type'=>'hidden', 'value'=>$this->data['Project']['id'])) ?></p>
<p><?php echo $form->input('name') ?></p>
<p><?php echo $form->input('description') ?></p>
<p><?php echo $form->input('wiki_page_title', array('label'=>__('Wiki page', true))) ?></p>
<p><?php echo $form->input('effective_date', array('label'=>__('Date', true))) ?></p>
</div>

<?php echo $form->submit(__('Create', true)) ?>

<?php echo $form->end() ?>


