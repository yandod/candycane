<div class="contextual">
<?php if ($editable) : ?>
<?php if ($content['version'] === $page['Content']['version']) { echo $candy->link_to_if_authorized(__('button_edit', true), aa('action', 'edit', 'page', $page['Page']['title']), aa('class', 'icon icon-edit' /*'accesskey', $candy->accesskey('edit')*/)); } ?>
<?php if (!$page['Page']['protected']) { echo $candy->link_to_if_authorized(__('button_lock', true), aa('action', 'protect', 'page', $page['Page']['title'], 'protected', 1), aa('method', 'post', 'class', 'icon icon-lock')); } ?>
<?php if (!$page['Page']['protected']) { echo $candy->link_to_if_authorized(__('button_unlock', true), aa('action', 'protect', 'page', $page['Page']['title'], 'protected', 0), aa('method', 'post', 'class', 'icon icon-unlock')); } ?>
<?php if ($content['version'] == $page['Content']['version']) { echo $candy->link_to_if_authorized(__('button_rename', true), aa('action', 'rename', 'page', $page['Page']['title']), 'class', 'icon icon-move'); } ?>
<?php echo $candy->link_to_if_authorized(__('button_delete', true), aa('action', 'destroy', 'page', $page['Page']['title']), aa('method', 'post', 'confirm', __('text_are_you_sure', true), 'class', 'icon icon-del')); ?>
<?php if ($content['version'] < $page['Content']['version']) { $candy->link_to_if_authorized(__('button_rollback', true), aa('action', 'edit', 'page', $page['Page']['title'], 'version',$content['version']), 'class', 'icon icon-cancel'); } ?>
<?php endif; ?>
<?php $candy->link_to_if_authorized(__('label_history', true), aa('action', 'history', 'page', $page['Page']['title']), aa('class', 'icon icon-history')) ?>
</div>

<?php $candy->breadcrumb($page); /*breadcrumb(@page.ancestors.reverse.collect {|parent| link_to h(parent.pretty_title), {:page => parent.title}}) */ ?>

<?php if ($content['version'] !== $page['Content']['version']) : ?>
    <p>
    <?php if ($content['version'] > 1) { echo $html->link('&#171; ' . __('Previous', true), aa('controller', 'wiki', 'action', 'index', 'project_id', $project_id, 'wikipage', $page['Page']['title'], 'version', $content['version'] - 1), array(), false, false); echo " - "; } ?>
    <?php printf('%s %s/%s', __('Versions'), $content['version'], $page['Content']['version']) ?>
    <?php if ($content['version'] > 1) { printf('(%s)', $html->link('diff', aa('controller', 'wiki', 'action', 'diff', 'wikipage', $page['Page']['title'], 'version', $content['version']))); } ?> - 
    <?php if ($content['version'] < $page['Content']['version']) { echo $html->link(__('Next', true). ' &#187;', aa('action', 'index', 'wikipage', $page['Page']['title'], 'version', $content['version'] + 1), aa(), false, false) . " - "; } ?>
    <?php echo $html->link(__('Current version', true), aa('action', 'index', 'wikipage', $page['Page']['title'])); ?>
    <br />
    <em><?php echo isset($author['User']) ? $author['User']['name'] : "anonyme" ?>, <?php $candy->format_time($content['updated_on']) ?> </em><br />
    <?php echo h($content['comments']) ?>
    </p>
    <hr />
<?php endif; ?>

<?php echo $this->renderElement('wiki/content', aa('content',$content)); ?>

<?php /* $attachment->link_to_attachments($page) // attachment helper */ ?>

<?php if ($editable /* && authorize_for('wiki', 'add_attachment')*/ ) : ?>
<p><?php echo $html->link(__('New file', true), '', aa('onclick', "Element.show('add_attachment_form'); Element.hide(this); Element.scrollTo('add_attachment_form'); return false;", 'id', 'attach_files_link')); ?></p>
<?php echo $form->create(null,
                         array('url' => array('controller' => 'wiki', 'action' => 'add_attachment', 'page' => $page['Page']['title']),
                               'type' => 'file',
                               'id' => "add_attachment_form",
                               'style' => "display:none;"
                               ));
/* form_tag({ :controller => 'wiki', :action => 'add_attachment', :page => @page.title }, :multipart => true, :id => "add_attachment_form", :style => "display:none;") do */
?>
  <div class="box">
  <p><?php echo $this->renderElement('attachments/form'); ?></p>
  </div>
<?php $form->submit(__('Add', true)); ?>
<?php echo $html->link(__('Cancel' ,true), '', aa('onclick', "Element.hide('add_attachment_form'); Element.show('attach_files_link'); return false;")) ?>
<?php echo $form->end(); ?>
<?php endif; ?>

<p class="other-formats">
<?php echo __("'Also available in:'", true) ?>
<span><?php echo $html->link('HTML', array('action' => 'index', 'project_id' => $project_id, 'wikipage' => $page['Page']['title'], '?export=html&version='. $content['version']), array('class' => 'html')) ?></span>
<span><?php echo $html->link('TXT', array('project_id' => $project_id, 'wikipage' => $page['Page']['title'], '?export=txt&version='. $content['version']), array('class' => 'text')) ?></span>
</p>

<?php $this->set('header_tags', ""/*stylesheet_link_tag 'scm'*/) ?>

<?php $this->set('Sidebar', $this->renderElement('wiki/sidebar')) ?>

<?php $candy->html_title($page['Page']['pretty_title']) ?>
