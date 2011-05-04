<div class="contextual">
<?php if ($editable) : ?>
<?php if ($content['WikiContent']['version'] === $page['WikiContent']['version']) {
     echo $candy->link_to_if_authorized(
		array('controller' => 'wiki', 'action' => 'edit'),
		__('Edit', true),
        array('action' => 'edit',
        	'project_id' => $main_project['Project']['identifier'],
            'wikipage' => $page['WikiPage']['title']),
        	aa('class', 'icon icon-edit')
		);
   } ?>

<?php if (!$page['WikiPage']['protected']) {
     echo $candy->link_to_if_authorized(
		array('controller' => 'wiki', 'action' => 'protect'),
		__('Lock', true),
        array('action' => 'protect',
        	'project_id' => $main_project['Project']['identifier'],
        	'wikipage' => $page['WikiPage']['title'],
        	'?protected=1'),
            aa('method', 'post', 'class', 'icon icon-lock')
		);
   } ?>

<?php if ($page['WikiPage']['protected']) {
  echo $candy->link_to_if_authorized(
		array('controller' => 'wiki', 'action' => 'protect'),
		__('Unlock', true),
        array('action' => 'protect',
        	'project_id' => $main_project['Project']['identifier'],
            'wikipage' => $page['WikiPage']['title'],
            '?protected=0'),
            aa('method', 'post', 'class', 'icon icon-unlock')
		);
 } ?>

<?php if ($content['WikiContent']['version'] == $page['WikiContent']['version']) {
   echo $candy->link_to_if_authorized(
		array('controller' => 'wiki', 'action' => 'rename'),
		__('Rename', true),
        array('action' => 'rename',
        	'project_id' => $main_project['Project']['identifier'],
        	'wikipage' => $page['WikiPage']['title']),
            aa('class', 'icon icon-move')
		);
 } ?>

<?php echo $candy->link_to_if_authorized(
	array('controller' => 'wiki', 'action' => 'destroy'),
	__('Delete', true),
    array('action' => 'destroy',
    	'project_id' => $main_project['Project']['identifier'],
        'wikipage'=> $page['WikiPage']['title']),
        aa('method', 'post',
        	'confirm', __('Are you sure ?', true),
            'class', 'icon icon-del')
);
?>

<?php if ($content['WikiContent']['version'] < $page['WikiContent']['version']) {
	echo $candy->link_to_if_authorized(
		array('controller' => 'wiki', 'action' => 'edit'),
		__('Rollback to this version', true),
        array('action' => 'edit',
        	'project_id' => $main_project['Project']['identifier'],
            'wikipage' => $page['WikiPage']['title'],
            '?version='.$content['WikiContent']['version']),
            aa('class', 'icon icon-cancel')
	);
 } ?>
<?php endif; ?>

<?php echo $candy->link_to_if_authorized(
	array('controller' => 'wiki', 'action' => 'history'),
	__('History', true),
	array('action' => 'history',
    	'project_id' => $main_project['Project']['identifier'],
        'wikipage' => $page['WikiPage']['title']),
        aa('class', 'icon icon-history')
	);
?>
</div>

<?php /*$candy->breadcrumb($page);*/ /*breadcrumb(@page.ancestors.reverse.collect {|parent| link_to h(parent.pretty_title), {:page => parent.title}}) */ ?>

<?php if ($content['WikiContent']['version'] !== $page['WikiContent']['version']) : ?>
    <p>
<?php if ($content['WikiContent']['version'] > 1) {
   echo $candy->link_to_if_authorized(
		array('controller' => 'wiki', 'action' => 'index'),
				'&#171; ' . __('Previous', true),
                 array('action' => 'index',
                       'project_id' => $main_project['Project']['identifier'],
                       'wikipage' => $page['WikiPage']['title'],
                       '?version='. ($content['WikiContent']['version'] - 1)),
                 array(), false, false); e(" - "); } ?>
<?php printf('%s %s/%s ',
             __('Version'),
             $content['WikiContent']['version'],
             $page['WikiContent']['version']) ?>
<?php
if ($content['WikiContent']['version'] > 1) {
  printf('(%s)',
         $candy->link_to_if_authorized(
			array('controller' => 'wiki', 'action' => 'diff'),
			'diff',
                     array('action'     => 'diff',
                           'project_id' => $main_project['Project']['identifier'],
                           'wikipage'   => $page['WikiPage']['title'],
                           '?version='. $content['WikiContent']['version'])));
} ?> - <?php
if ($content['WikiContent']['version'] < $page['WikiContent']['version']) {
  echo $candy->link_to_if_authorized(
	array('controller' => 'wiki', 'action' => 'index'),
	__('Next', true). ' &#187;',
                array('action'     => 'index',
                      'project_id' => $main_project['Project']['identifier'],
                      'wikipage'   => $page['WikiPage']['title'],
                      '?version='. ($content['WikiContent']['version'] + 1)),
                a(), false, false); e(" - "); } ?>
<?php echo $candy->link_to_if_authorized(
	array('controller' => 'wiki', 'action' => 'index'),
	__('Current version', true),
                    array('action'     => 'index',
                          'project_id' => $main_project['Project']['identifier'],
                          'wikipage'   => $page['WikiPage']['title'])); ?>
    <br />
    <em><?php e(isset($content['Author']) ? $candy->format_username($content['Author']) : "anonyme"); ?>, <?php e($candy->format_time($content['WikiContent']['updated_on'])); ?> </em><br />
    <?php e(h($content['WikiContent']['comments'])); ?>
    </p>
    <hr />
<?php endif; ?>

<?php e($this->renderElement('wiki/content', aa('content', $content))); ?>

<?php /* $attachment->link_to_attachments($page) // attachment helper */ ?>

<?php if ($editable /* && authorize_for('wiki', 'add_attachment')*/ ) : ?>
<p><?php echo $candy->link_to_if_authorized(
	array('controller' => 'wiki', 'action' => 'add_attachment'),
	__('New file', true), 
	'', 
	aa('onclick', "Element.show('add_attachment_form'); Element.hide(this); Element.scrollTo('add_attachment_form'); return false;", 'id', 'attach_files_link')
	); ?></p>
<?php e($form->create(null,
                      array('url' => 
                            array('action' => 'add_attachment',
                                  'project_id' => $main_project['Project']['identifier'],
                                  'wikipage' => $page['WikiPage']['title']),
                            'type' => 'file',
                            'id' => "add_attachment_form",
                            'style' => "display:none;"
                            )));
/* form_tag({ :controller => 'wiki', :action => 'add_attachment', :page => @page.title }, :multipart => true, :id => "add_attachment_form", :style => "display:none;") do */
?>
  <div class="box">
  <p><?php e($this->renderElement('attachments/form')); ?></p>
  </div>
<?php e($form->submit(__('Add', true), aa('div', false))); ?>
<?php e($html->link(__('Cancel' ,true), '', aa('onclick', "Element.hide('add_attachment_form'); Element.show('attach_files_link'); return false;"))); ?>
<?php e($form->end()); ?>
<?php endif; ?>

<p class="other-formats">
<?php e(__('Also available in:', true)); ?>

<span><?php echo $candy->link_to_if_authorized(
	array('controller' => 'wiki', 'action' => 'index'),
	'HTML',
	array('action' => 'index',
    'project_id' => $main_project['Project']['identifier'],
                                'wikipage' => $page['WikiPage']['title'],
                                '?export=html&version='. $content['WikiContent']['version']),
                          aa('class', 'html')); ?></span>
<span><?php echo $candy->link_to_if_authorized(
	array('controller' => 'wiki', 'action' => 'index'),
	'TXT',
    array('action' => 'index',
    'project_id' => $main_project['Project']['identifier'],
                                'wikipage' => $page['WikiPage']['title'],
                                '?export=txt&version='. $content['WikiContent']['version']),
                          aa('class', 'text')); ?></span>
</p>

<?php $this->set('header_tags', $html->css('scm')) ?>

<?php $this->set('Sidebar', $this->renderElement('wiki/sidebar')) ?>

<?php $candy->html_title(h($wiki->pretty_title($page['WikiPage']['title']))) ?>
