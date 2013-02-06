<div class="contextual">
<?php if ($editable) : ?>
<?php if ($content['WikiContent']['version'] === $page['WikiContent']['version']) {
     echo $this->Candy->link_to_if_authorized(
		array('controller' => 'wiki', 'action' => 'edit'),
		__('Edit'),
        array('action' => 'edit',
        	'project_id' => $main_project['Project']['identifier'],
            'wikipage' => $page['WikiPage']['title']),
        	array('class' => 'icon icon-edit')
		);
   } ?>

<?php if (!$page['WikiPage']['protected']) {
     echo $this->Candy->link_to_if_authorized(
		array('controller' => 'wiki', 'action' => 'protect'),
		__('Lock'),
        array('action' => 'protect',
        	'project_id' => $main_project['Project']['identifier'],
        	'wikipage' => $page['WikiPage']['title'],
        	'?protected=1'),
            array(
				'method' => 'post',
				'class' => 'icon icon-lock')
		);
   } ?>

<?php if ($page['WikiPage']['protected']) {
  echo $this->Candy->link_to_if_authorized(
		array('controller' => 'wiki', 'action' => 'protect'),
		__('Unlock'),
        array('action' => 'protect',
        	'project_id' => $main_project['Project']['identifier'],
            'wikipage' => $page['WikiPage']['title'],
            '?protected=0'),
            array(
				'method' => 'post',
				'class' => 'icon icon-unlock'
			)
		);
 } ?>

<?php if ($content['WikiContent']['version'] == $page['WikiContent']['version']) {
   echo $this->Candy->link_to_if_authorized(
		array('controller' => 'wiki', 'action' => 'rename'),
		__('Rename'),
        array('action' => 'rename',
        	'project_id' => $main_project['Project']['identifier'],
        	'wikipage' => $page['WikiPage']['title']),
            array(
				'class' => 'icon icon-move'
			)
		);
 } ?>

<?php echo $this->Candy->link_to_if_authorized(
	array('controller' => 'wiki', 'action' => 'destroy'),
	__('Delete'),
    array('action' => 'destroy',
    	'project_id' => $main_project['Project']['identifier'],
        'wikipage'=> $page['WikiPage']['title']),
        array(
			'method' => 'post',
        	'confirm' => __('Are you sure ?'),
            'class' => 'icon icon-del')
);
?>

<?php if ($content['WikiContent']['version'] < $page['WikiContent']['version']) {
	echo $this->Candy->link_to_if_authorized(
		array('controller' => 'wiki', 'action' => 'edit'),
		__('Rollback to this version'),
        array('action' => 'edit',
        	'project_id' => $main_project['Project']['identifier'],
            'wikipage' => $page['WikiPage']['title'],
            '?version='.$content['WikiContent']['version']),
            array('class' => 'icon icon-cancel')
	);
 } ?>
<?php endif; ?>

<?php echo $this->Candy->link_to_if_authorized(
	array('controller' => 'wiki', 'action' => 'history'),
	__('History'),
	array('action' => 'history',
    	'project_id' => $main_project['Project']['identifier'],
        'wikipage' => $page['WikiPage']['title']),
        array('class' => 'icon icon-history')
	);
?>
</div>

<?php
echo $this->Wiki->render_wiki_breadcrumb($wiki_pages);
/*$this->Candy->breadcrumb($page);*/ /*breadcrumb(@page.ancestors.reverse.collect {|parent| link_to h(parent.pretty_title), {:page => parent.title}}) */ ?>

<?php if ($content['WikiContent']['version'] !== $page['WikiContent']['version']) : ?>
    <p>
<?php if ($content['WikiContent']['version'] > 1) {
   echo $this->Candy->link_to_if_authorized(
		array('controller' => 'wiki', 'action' => 'index'),
				'&#171; ' . __('Previous'),
                 array('action' => 'index',
                       'project_id' => $main_project['Project']['identifier'],
                       'wikipage' => $page['WikiPage']['title'],
                       '?version='. ($content['WikiContent']['version'] - 1)),
                 array(), false, false); echo " - "; } ?>
<?php printf('%s %s/%s ',
             __('Version'),
             $content['WikiContent']['version'],
             $page['WikiContent']['version']) ?>
<?php
if ($content['WikiContent']['version'] > 1) {
  printf('(%s)',
         $this->Candy->link_to_if_authorized(
			array('controller' => 'wiki', 'action' => 'diff'),
			'diff',
                     array('action'     => 'diff',
                           'project_id' => $main_project['Project']['identifier'],
                           'wikipage'   => $page['WikiPage']['title'],
                           '?version='. $content['WikiContent']['version'])));
} ?> - <?php
if ($content['WikiContent']['version'] < $page['WikiContent']['version']) {
  echo $this->Candy->link_to_if_authorized(
	array('controller' => 'wiki', 'action' => 'index'),
	__('Next'). ' &#187;',
                array('action'     => 'index',
                      'project_id' => $main_project['Project']['identifier'],
                      'wikipage'   => $page['WikiPage']['title'],
                      '?version='. ($content['WikiContent']['version'] + 1)),
                array(), false, false); echo " - "; } ?>
<?php echo $this->Candy->link_to_if_authorized(
	array('controller' => 'wiki', 'action' => 'index'),
	__('Current version'),
                    array('action'     => 'index',
                          'project_id' => $main_project['Project']['identifier'],
                          'wikipage'   => $page['WikiPage']['title'])); ?>
    <br />
    <em><?php
	echo isset($content['Author']) ? $this->Candy->format_username($content['Author']) : "anonyme"; ?>, <?php echo $this->Candy->format_time($content['WikiContent']['updated_on']); ?> </em><br />
    <?php echo h($content['WikiContent']['comments']); ?>
    </p>
    <hr />
<?php endif; ?>

<?php echo $this->element('wiki/content', array('content' => $content)); ?>

<?php /* $attachment->link_to_attachments($page) // attachment helper */ ?>

<?php
	$attach_options = array_merge(array('deletable'=>$attachments_deletable), $content);
	echo $this->element('attachments/links', array('attachments'=>$attachments, 'options'=>$attach_options), array('Number'));
?>

<?php if ($editable /* && authorize_for('wiki', 'add_attachment')*/ ) : ?>
<p><?php echo $this->Candy->link_to_if_authorized(
	array('controller' => 'wiki', 'action' => 'add_attachment'),
	__('New file'), 
	'', 
	array(
		'onclick' => "Element.show('add_attachment_form'); Element.hide(this); Element.scrollTo('add_attachment_form'); return false;",
		'id' => 'attach_files_link'
	)
	); ?></p>
<?php echo $this->Form->create(null,
                      array('url' => 
                            array('action' => 'add_attachment',
                                  'project_id' => $main_project['Project']['identifier'],
                                  'wikipage' => $page['WikiPage']['title']),
                            'type' => 'file',
                            'id' => "add_attachment_form",
                            'style' => "display:none;"
                            ));
/* form_tag({ :controller => 'wiki', :action => 'add_attachment', :page => @page.title }, :multipart => true, :id => "add_attachment_form", :style => "display:none;") do */
?>
  <div class="box">
  <p><?php echo $this->element('attachments/form'); ?></p>
  </div>
<?php echo $this->Form->submit(__('Add'), array('div' => false)); ?>
<?php echo $this->Html->link(__('Cancel' ), '', array(
	'onclick' => "Element.hide('add_attachment_form'); Element.show('attach_files_link'); return false;"
)); ?>
<?php echo $this->Form->end(); ?>
<?php endif; ?>

<p class="other-formats">
<?php echo __('Also available in:'); ?>

<span><?php echo $this->Candy->link_to_if_authorized(
	array('controller' => 'wiki', 'action' => 'index'),
	'HTML',
	array('action' => 'index',
    'project_id' => $main_project['Project']['identifier'],
                                'wikipage' => $page['WikiPage']['title'],
                                '?export=html&version='. $content['WikiContent']['version']),
                          array('class' => 'html')); ?></span>
<span><?php echo $this->Candy->link_to_if_authorized(
	array('controller' => 'wiki', 'action' => 'index'),
	'TXT',
    array('action' => 'index',
    'project_id' => $main_project['Project']['identifier'],
                                'wikipage' => $page['WikiPage']['title'],
                                '?export=txt&version='. $content['WikiContent']['version']),
                          array('class' => 'text')); ?></span>
</p>

<?php $this->set('header_tags', $this->Html->css('scm')) ?>

<?php $this->set('Sidebar', $this->element('wiki/sidebar')) ?>

<?php $this->Candy->html_title(h($this->Wiki->pretty_title($page['WikiPage']['title']))) ?>
