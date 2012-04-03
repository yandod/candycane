<h2><?php echo h($this->Wiki->pretty_title($page['WikiPage']['title'])); ?></h2>
<?php e($this->Form->create('WikiContent',
                      aa('url',
                         array('controller' => 'wiki',
                               'action' => 'edit',
                               'project_id' => $main_project['Project']['identifier'],
                               'wikipage' => $page['WikiPage']['title'],
                               /* formヘルパが更新時にidを上書きするのを禁止するためのtrick */
                               'id' => null)))); ?>
<?php echo $this->Form->hidden('version'); ?>

<?php echo $this->element('error_explanation'); ?>

<p><?php e($this->Form->input('WikiContent.text',
                        array('type' => 'textarea',
                              'cols' => 100,
                              'rows' => 25,
                              'class' => 'wiki-edit',
                              'label' => false,
                              'div' => false,
                              /*'accesskey' => accesskey(:edit)*/ ))) ?></p>
<p><label><?php echo __("Comment"); ?></label><br />
<?php e($this->Form->input('WikiContent.comments',
                     array('type' => 'text',
                           'size' => 120,
                           'label' => false,
                           'div' => false))); ?></p>
<p><?php echo $this->Form->submit(__("Save",
                         aa('div', false))); ?>
<?php echo $ajax->link(__('Preview',
                    array('controller' => 'wiki',
                          'action' => 'preview',
                          'project_id' => $main_project['Project']['identifier'],
                          'wikipage' => $page['WikiPage']['title']),
                    array('method' => 'post',
                          'update' => 'preview',
                          'with' => "Form.serialize('WikiContentEditForm')",
                          'complete' => "Element.scrollTo('preview')")));
/*}, :accesskey => accesskey(:preview)*/ ?></p>
<?php /*wikitoolbar_for 'content_text'*/ ?>
<?php $this->Form->end() ?>

<div id="preview" class="wiki"></div>

<?php $this->set('header_tags', $this->Html->css('scm')) ?>

<?php $this->Candy->html_title(h($this->Wiki->pretty_title($page['WikiPage']['title']))); ?>
