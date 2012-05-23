<h2><?php echo __('Rename'); ?>: <?php echo $original_title; ?></h2>

<?php echo $this->element('error_explanation'); ?>

<?php /* labelled_tabular_form_for :wiki_page, @page, :url => { :action => 'rename' } do |f| */ ?>
<?php e($this->Form->create('WikiPage',
                      array('url' =>
                         array('controller' => 'wiki',
                               'action' => 'rename',
                               'project_id' => $main_project['Project']['identifier'],
                               'wikipage' => $original_title,
                               /* formヘルパが更新時にidを上書きするのを禁止するためのtrick */
                               'id' => null),
                         'class' => 'tabular'))); ?>
<div class="box">
<p><?php e($this->Form->input('WikiPage.title',
                        array('type' => 'text',
                              'required' => true,
                              'size' => 100,
                              'label' => __('Title'),
                              'div' => false))); ?></p>
<p><?php e($this->Form->input('WikiPage.redirect_existing_links',
                        array('type' => 'checkbox',
                              'label' => __('Redirect existing links'),
                              'div' => false))); ?></p>
<p><?php e($this->Form->input('WikiPage.parent_title',
                        array('type' => 'text',
                              'size' => 100,
                              'label' => __('Parent page'),
                              'div' => false))); ?></p>
</div>
<?php echo $this->Form->submit(__('Rename')); ?>
<?php $this->Form->end() ?>
