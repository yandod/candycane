<div class="contextual">
<?php if ($editable) : ?>
   <?php if ($content['Content']['version'] === $page['Content']['version']) { e($html->link(__('Edit', true), array('action' => 'edit', 'project_id' => $main_project['Project']['identifier'], 'wikipage' => $page['Page']['title']), aa('class', 'icon icon-edit' /*'accesskey', $candy->accesskey('edit')*/))); } ?>
<?php if (!$page['Page']['protected']) { e($html->link(__('Lock', true), array('action' => 'protect', 'project_id' => $main_project['Project']['identifier'], 'wikipage' => $page['Page']['title'], '?protected=1'), aa('method', 'post', 'class', 'icon icon-lock'))); } ?>
<?php if ($page['Page']['protected']) { e($html->link(__('Unlock', true), array('action' => 'protect', 'project_id' => $main_project['Project']['identifier'], 'wikipage' => $page['Page']['title'], '?protected=0'), aa('method', 'post', 'class', 'icon icon-unlock'))); } ?>
<?php if ($content['Content']['version'] == $page['Content']['version']) { e($html->link(__('Rename', true), array('action' => 'rename', 'project_id' => $main_project['Project']['identifier'], 'wikipage' => $page['Page']['title']), aa('class', 'icon icon-move'))); } ?>
<?php e($html->link(__('Delete', true), array('action' => 'destroy', 'project_id' => $main_project['Project']['identifier'], 'wikipage', $page['Page']['title']), aa('method', 'post', 'confirm', __('Are you sure ?', true), 'class', 'icon icon-del'))); ?>
<?php if ($content['Content']['version'] < $page['Content']['version']) { $html->link(__('Rollback to this version', true), aa('action', 'edit', 'page', $page['Page']['title'], 'version',$content['Content']['version']), aa('class', 'icon icon-cancel')); } ?>
<?php endif; ?>
<?php $html->link(__('label_history', true), aa('action', 'history', 'page', $page['Page']['title']), aa('class', 'icon icon-history')) ?>
</div>

<?php $candy->breadcrumb($page); /*breadcrumb(@page.ancestors.reverse.collect {|parent| link_to h(parent.pretty_title), {:page => parent.title}}) */ ?>

<?php if ($content['Content']['version'] !== $page['Content']['version']) : ?>
    <p>
<?php if ($content['Content']['version'] > 1) { e($html->link('&#171; ' . __('Previous', true), aa('controller', 'wiki', 'action', 'index', 'project_id', $main_project['Project']['identifier'], 'wikipage', $page['Page']['title'], 'version', $content['Content']['version'] - 1), array(), false, false)); e(" - "); } ?>
    <?php printf('%s %s/%s', __('Versions'), $content['Content']['version'], $page['Content']['version']) ?>
    <?php if ($content['Content']['version'] > 1) { printf('(%s)', $html->link('diff', aa('controller', 'wiki', 'action', 'diff', 'wikipage', $page['Page']['title'], 'version', $content['Content']['version']))); } ?> - 
    <?php if ($content['Content']['version'] < $page['Content']['version']) { e($html->link(__('Next', true). ' &#187;', aa('action', 'index', 'wikipage', $page['Page']['title'], 'version', $content['Content']['version'] + 1), aa(), false, false), " - "); } ?>
    <?php e($html->link(__('Current version', true), aa('action', 'index', 'wikipage', $page['Page']['title']))); ?>
    <br />
    <em><?php e(isset($author['User']) ? $author['User']['name'] : "anonyme"); ?>, <?php e($candy->format_time($content['Content']['updated_on'])); ?> </em><br />
    <?php e(h($content['Content']['comments'])); ?>
    </p>
    <hr />
<?php endif; ?>

<?php e($this->renderElement('wiki/content', aa('content', $content))); ?>

<?php /* $attachment->link_to_attachments($page) // attachment helper */ ?>

<?php if ($editable /* && authorize_for('wiki', 'add_attachment')*/ ) : ?>
<p><?php e($html->link(__('New file', true), '', aa('onclick', "Element.show('add_attachment_form'); Element.hide(this); Element.scrollTo('add_attachment_form'); return false;", 'id', 'attach_files_link'))); ?></p>
<?php e($form->create(null,
                      array('url' => array('controller' => 'wiki', 'action' => 'add_attachment', 'page' => $page['Page']['title']),
                            'type' => 'file',
                            'id' => "add_attachment_form",
                            'style' => "display:none;"
                            )));
/* form_tag({ :controller => 'wiki', :action => 'add_attachment', :page => @page.title }, :multipart => true, :id => "add_attachment_form", :style => "display:none;") do */
?>
  <div class="box">
  <p><?php e($this->renderElement('attachments/form')); ?></p>
  </div>
<?php e($form->submit(__('Add', true))); ?>
<?php e($html->link(__('Cancel' ,true), '', aa('onclick', "Element.hide('add_attachment_form'); Element.show('attach_files_link'); return false;"))); ?>
<?php e($form->end()); ?>
<?php endif; ?>

<p class="other-formats">
<?php e(__("'Also available in:'", true)); ?>
<span><?php e($html->link('HTML', array('action' => 'index', 'project_id' => $main_project['Project']['identifier'], 'wikipage' => $page['Page']['title'], '?export=html&version='. $content['Content']['version']), aa('class', 'html'))); ?></span>
<span><?php e($html->link('TXT', array('project_id' => $main_project['Project']['identifier'], 'wikipage' => $page['Page']['title'], '?export=txt&version='. $content['Content']['version']), aa('class', 'text'))); ?></span>
</p>

<?php $this->set('header_tags', $html->css('scm')) ?>

<?php $this->set('Sidebar', $this->renderElement('wiki/sidebar')) ?>

<?php $candy->html_title(h($wiki->pretty_title($page['Page']['title']))) ?>
