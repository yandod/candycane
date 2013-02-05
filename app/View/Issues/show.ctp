<div class="contextual">
<?php
echo $this->Candy->link_to_if_authorized(
	null,
	__('Update'),
	array(
		'controller' => 'issues',
		'action' => 'edit',
		'id' => $issue['Issue']['id']
	), 
	array(
		'onclick' => 'showAndScrollTo("update", "notes"); return false;',
		'class' => 'icon icon-edit',
		'accesskey' => 'accesskey(:edit)'
	)
); ?>
<?php 
echo $this->Candy->link_to_if_authorized(
	array(
		'controller' => 'timelog',
		'action' => 'edit',		
	),
	__('Log time'),
	array(
		'controller' => 'timelog',
		'action' => 'edit',
		'?' => array(
			'issue_id' =>  $issue['Issue']['id']
		)
	),
	array(
		'class' => 'icon icon-time'
	)
) ?>
  <?php echo $this->Watchers->watcher_link($issue, $currentuser); ?>
  <?php echo $this->Candy->link_to_if_authorized(array('controller' => 'issues', 'action' => 'new'), __('Copy'), array('controller' => 'issues', 'action' => 'add', 'project_id' => $main_project['Project']['identifier'], '?'=>array('copy_from'=>$issue['Issue']['id'])), array('class' => 'icon icon-copy')) ?>
  <?php echo $this->Candy->link_to_if_authorized(null, __('Move'), array('controller' => 'issues', 'action' => 'move', $issue['Issue']['id']), array('class' => 'icon icon-move')); ?>
  <?php echo $this->Candy->link_to_if_authorized(null, __('Delete'), array('controller' => 'issues', 'action' => 'destroy', $issue['Issue']['id']), array('class' => 'icon icon-del', 'confirm' => __('Are you sure ?'), 'method' => 'post')); ?>
</div>

<h2><?php echo h($issue['Tracker']['name']) ?> #<?php echo h($issue['Issue']['id']) ?></h2>

<div class="<?php echo h($this->Issues->css_issue_classes($issue)) ?>">
        <?php echo $this->Candy->avatar(array('User' => $issue['Author']), array('size' => 64)) ?>
        <h3><?php echo h($issue['Issue']['subject']) ?></h3>
        <p class="author">
        <?php echo $this->Candy->authoring($issue['Issue']['created_on'], $issue['Author']) ?>.
        <?php if ($issue['Issue']['created_on'] !=  $issue['Issue']['updated_on']) $this->Candy->lwr('Updated %s ago', $this->Candy->distance_of_time_in_words(time(), $issue['Issue']['updated_on'])) ?>
        </p>

<table width="100%">
<tr>
    <td style="width:15%" class="status"><b><?php echo __('Status') ?>:</b></td><td style="width:35%" class="status"><?php echo h($issue['Status']['name']) ?></td>
    <td style="width:15%" class="start-date"><b><?php echo __('Start') ?>:</b></td><td style="width:35%"><?php echo h($this->Candy->format_date($issue['Issue']['start_date'])) ?></td>
</tr>
<tr>
    <td class="priority"><b><?php echo __('Priority') ?>:</b></td><td class="priority"><?php echo h($issue['Priority']['name']) ?></td>
    <td class="due-date"><b><?php echo __('Due date') ?>:</b></td><td class="due-date"><?php echo h($this->Candy->format_date($issue['Issue']['due_date'])) ?></td>
</tr>
<tr>
    <td class="assigned-to"><b><?php echo __('Assigned to') ?>:</b></td><td><?php echo $this->Candy->avatar(array('User' => $issue['AssignedTo']), array('size' => 14)) ?><?php echo strlen($issue['Issue']['assigned_to_id']) ? $this->Candy->link_to_user($issue['AssignedTo']) : "-" ?></td>
    <td class="progress"><b><?php echo __('Done') ?> %:</b></td><td class="progress"><?php echo $this->Candy->progress_bar($issue['Issue']['done_ratio'], array('width'=>'80px', 'legend'=>$issue['Issue']['done_ratio'].'%')); ?></td>
</tr>
<tr>
    <td class="category"><b><?php echo __('Category') ?>:</b></td><td><?php echo h(strlen($issue['Issue']['category_id']) ? $issue['Category']['name'] : "-") ?></td>
    <?php if($this->Candy->authorize_for(':view_time_entries')): ?>
    <td class="spent-time"><b><?php echo __('Spent time') ?>:</b></td>
    <td class="spent-hours"><?php echo ($this->Issues->spent_hours($issue) > 0) ? $this->Html->link(sprintf(__('%.2f hour'), $this->Issues->spent_hours($issue)), '/projects/'.$main_project['Project']['identifier'].'/timelog/details?issue_id='.$issue['Issue']['id'], array('class'=>'icon icon-time')) : "-"; ?></td>
    <?php endif; ?>
</tr>
<tr>
    <td class="fixed-version"><b><?php echo __('Target version') ?>:</b></td><td><?php echo strlen($issue['Issue']['fixed_version_id']) ? $this->Candy->link_to_version($issue['FixedVersion']) : "-" ?></td>
    <?php if(!empty($issue['Issue']['estimated_hours'])): ?>
    <td class="estimated-hours"><b><?php echo __('Estimated time') ?>:</b></td><td><?php $this->Candy->lwr('%.2f hour', $issue['Issue']['estimated_hours']) ?></td>
    <?php endif; ?>
</tr>
<tr>
<?php $n = 0; ?>
<?php if(!empty($issue['CustomValue'])): ?>
  <?php foreach($issue['CustomValue'] as $value): ?>
    <td valign="top">
      <b><?php echo h($value['CustomField']['name']); ?>:</b></td><td valign="top"><?php echo h($this->CustomField->value($value)); ?></td>
<?php 
    $n = $n + 1;
    if ($n > 1) :
      $n = 0;
?>
        </tr><tr>
    <?php endif; ?>
 <?php endforeach; ?>
<?php endif; ?>
</tr>
<?php echo $this->element('issues/show_details_bottom',array('issue' => $issue));?>
</table>
<hr />

<div class="contextual">
<?php
if (
    $this->Candy->authorize_for(
	    array(
		    'controller' => 'issues',
			'action' => 'reply'
		)
	) && 
	!empty($issue['Issue']['description'])
) {
	echo $this->Js->link(
		__('Quote'),
		array(
			'controller' => 'issues',
			'action' => 'reply',
			$issue['Issue']['id']
		),
		array(
			'class' => 'icon icon-comment',
			'escape' => false,
			'buffer' => false,
			'evalScripts' => true,
			'update' => 'issue-reply'
		)
	);
}
?>
</div>
<div id="issue-reply"></div>
<p><strong><?php echo __('Description') ?></strong></p>
<div class="wiki">
  <?php echo $this->Candy->textilizable($issue['Issue']['description']); ?>
</div>

<?php
  // Copy from AttachmentHelper, because can not call element from Helper.
  if(array_key_exists('Author', $issue)) {
    $attach_options = array_merge(array('deletable'=>$attachments_deletable), $issue);
    echo $this->element('attachments/links', array('attachments'=>$attachments, 'options'=>$attach_options), array('Number'));
  }
?>
<hr />
<div id="relations">
  <?php echo $this->element('issues/relations', compact('issue', 'issueRelations')); ?>
</div>

<?php if($this->Candy->authorize_for('add_issue_watchers') || !empty($issue['Watcher']) && $this->Candy->authorize_for('view_issue_watchers')): ?>
<hr />
<div id="watchers">
  <?php echo $this->element('watchers/watchers', array(
    'list'=>!empty($issue['Watcher'])?$issue['Watcher']:array(), 
    'object_type'=>'issue', 
    'watched'=>$issue['Issue']['id'], 
    'addIssueWatchersAllowed'=>$this->Candy->authorize_for(':add_issue_watchers')
    )); ?>
</div>
<?php endif; ?>

</div>

<?php if(!empty($issue['Changeset']) && $this->Candy->authorize_for(':view_changesets')): ?>
<div id="issue-changesets">
<h3><?php echo __('Associated revisions') ?></h3>
<!--<%= render :partial => 'changesets', :locals => { :changesets => @issue.changesets} %>-->
</div>
<?php endif; ?>

<?php if(!empty($journal_list)): ?>
<div id="history">
<h3><?php echo __('History') ?></h3>
  <?php echo $this->element('issues/history', array(
    'journalList'=>$journal_list, 
    'issue'=>$issue
    )); ?>
</div>
<?php endif; ?>
<div style="clear: both;"></div>

<?php if($this->Candy->authorize_for(array('controller'=>'issues', 'action'=>'edit'))): ?>
  <div id="update" style="display:none;">
  <h3><?php echo __('Update');?></h3>
    <?php echo $this->element('issues/edit', compact(
      'statuses', 'priorities', 'assignableUsers', 'issueCategories', 
      'fixedVersions', 'customFieldValues')); ?>
  </div>
<?php endif; ?>

<p class="other-formats">
<?php echo __("'Also available in:'") ?>
<span><?php echo $this->Html->link('Atom', array('action'=>'show', $issue['Issue']['id'], 'format'=>'atom', 'key'=>$rss_token), array('class'=>'feed'));?></span>
<span><?php echo $this->Html->link('PDF', array('action'=>'show', $issue['Issue']['id'], 'format'=>'pdf'), array('class'=>'pdf')); ?></span>
</p>

    <?php $this->Candy->html_title($issue['Tracker']['name'] . ' #' . $issue['Issue']['id']. ' ' . $issue['Issue']['subject']) ?>

<?php $this->set('Sidebar', $this->element('issues/sidebar')) ?>
<?php $this->Html->meta(
	array(
		'property' => 'og:site_name',
		'content' => Configure::read('app_title')
	),
	null,
	array('inline' => false)
); ?>
<?php $this->Html->meta(
	array(
		'property' => 'og:title',
		'content' => $issue['Tracker']['name'] . '#' . $issue['Issue']['id'] . ' ' . $issue['Issue']['subject']
	),
	null,
	array('inline' => false)
); ?>
<?php $this->Html->meta(
	array(
		'property' => 'og:description',
		'content' => $issue['Issue']['description']
	),
	null,
	array('inline' => false)
); ?>
<?php $this->Html->meta(
	array(
		'property' => 'og:image',
		'content' => 'http://github.com/yandod/candycane/raw/migrate-cake2/app/Plugin/CcInstall/webroot/img/bear.png'
	),
	null,
	array('inline' => false)
); ?>
<?php $this->Html->meta(
	'atom',
	array(
		'action' => 'show',
		'id' => $issue['Issue']['id'],
		'format' => 'atom',
		'key' => $rss_token
	),
	array(
		'title' => $issue['Project']['name'].' - '.$issue['Tracker']['name'].' ##'.$issue['Issue']['id'].': '.$issue['Issue']['subject'],
		'rel' => 'alternate',
		'inline' => false
	)
); ?>
<?php $this->Html->css('scm.css', null, array('media'=>'screen'), false); ?>
