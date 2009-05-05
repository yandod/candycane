<h2><?php __('Rename'); ?>: <?php e($original_title); ?></h2>

<?php echo $this->renderElement('error_explanation'); ?>

<?php /* labelled_tabular_form_for :wiki_page, @page, :url => { :action => 'rename' } do |f| */ ?>
<?php e($form->create('WikiPage',
                      aa('url',
                         array('controller' => 'wiki',
                               'action' => 'rename',
                               'project_id' => $main_project['Project']['identifier'],
                               'wikipage' => $original_title,
                               /* formヘルパが更新時にidを上書きするのを禁止するためのtrick */
                               'id' => null),
                         'class', 'tabular'))); ?>
<div class="box">
<p><?php e($form->input('WikiPage.title',
                        array('type' => 'text',
                              'required' => true,
                              'size' => 100,
                              'label' => __('Title', true),
                              'div' => false))); ?></p>
<p><?php e($form->input('WikiPage.redirect_existing_links',
                        array('type' => 'checkbox',
                              'label' => __('Redirect existing links', true),
                              'div' => false))); ?></p>
<p><?php e($form->input('WikiPage.parent_title',
                        array('type' => 'text',
                              'size' => 100,
                              'label' => __('Parent page', true),
                              'div' => false))); ?></p>
</div>
<?php e($form->submit(__('Rename', true))); ?>
<?php $form->end() ?>
