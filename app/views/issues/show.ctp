<div class="contextual">
  <?php echo $candy->link_to_if_authorized(null, __('Update', true), array('controller' => 'issues', 'action' => 'edit', 'id' => $issue['Issue']['id']), array('onclick' => 'showAndScrollTo("update", "notes"); return false;', 'class' => 'icon icon-edit', 'accesskey' => 'accesskey(:edit)')); ?>
  <?php echo $candy->link_to_if_authorized(null, __('Log time', true), array('controller' => 'timelog', 'action' => 'edit', '?'=>array('issue_id' => $issue['Issue']['id'])), array('class' => 'icon icon-time')) ?>
  <?php echo $watchers->watcher_link($issue, $currentuser); ?>
  <?php echo $candy->link_to_if_authorized(aa('controller','issues', 'action','new'), __('Copy', true), array('controller' => 'issues', 'action' => 'add', 'project_id' => $main_project['Project']['identifier'], '?'=>array('copy_from'=>$issue['Issue']['id'])), array('class' => 'icon icon-copy')) ?>
  <?php echo $candy->link_to_if_authorized(null, __('Move', true), array('controller' => 'issues', 'action' => 'move', 'id' => $issue['Issue']['id']), array('class' => 'icon icon-move')); ?>
  <?php echo $candy->link_to_if_authorized(null, __('Delete', true), array('controller' => 'issues', 'action' => 'destroy', 'id' => $issue['Issue']['id']), array('class' => 'icon icon-del'), __('Are you sure ?',true)); ?>
</div>

<h2><?php echo h($issue['Tracker']['name']) ?> #<?php echo h($issue['Issue']['id']) ?></h2>

<div class="<?php echo h($issues->css_issue_classes($issue)) ?>">
        <?php echo $candy->avatar(array('User' => $issue['Author']), array('size' => 64)) ?>
        <h3><?php echo h($issue['Issue']['subject']) ?></h3>
        <p class="author">
        <?php echo $candy->authoring($issue['Issue']['created_on'], $issue['Author']) ?>.
        <?php if ($issue['Issue']['created_on'] !=  $issue['Issue']['updated_on']) $candy->lwr('Updated %s ago', $candy->distance_of_time_in_words(time(), $issue['Issue']['updated_on'])) ?>
        </p>

<table width="100%">
<tr>
    <td style="width:15%" class="status"><b><?php __('Status') ?>:</b></td><td style="width:35%" class="status"><?php echo h($issue['Status']['name']) ?></td>
    <td style="width:15%" class="start-date"><b><?php __('Start') ?>:</b></td><td style="width:35%"><?php echo h($candy->format_date($issue['Issue']['start_date'])) ?></td>
</tr>
<tr>
    <td class="priority"><b><?php __('Priority') ?>:</b></td><td class="priority"><?php echo h($issue['Priority']['name']) ?></td>
    <td class="due-date"><b><?php __('Due date') ?>:</b></td><td class="due-date"><?php echo h($candy->format_date($issue['Issue']['due_date'])) ?></td>
</tr>
<tr>
    <td class="assigned-to"><b><?php __('Assigned to') ?>:</b></td><td><?php echo $candy->avatar(array('User' => $issue['AssignedTo']), array('size' => 14)) ?><?php echo strlen($issue['Issue']['assigned_to_id']) ? $candy->link_to_user($issue['AssignedTo']) : "-" ?></td>
    <td class="progress"><b><?php __('done_ratio') ?> %:</b></td><td class="progress"><?php echo $candy->progress_bar($issue['Issue']['done_ratio'], array('width'=>'80px', 'legend'=>$issue['Issue']['done_ratio'].'%')); ?></td>
</tr>
<tr>
    <td class="category"><b><?php __('Category') ?>:</b></td><td><?php echo h(strlen($issue['Issue']['category_id']) ? $issue['Category']['name'] : "-") ?></td>
    <?php if($candy->authorize_for(':view_time_entries')): ?>
    <td class="spent-time"><b><?php __('Spent time') ?>:</b></td>
    <td class="spent-hours"><?php echo ($issues->spent_hours($issue) > 0) ? $html->link(sprintf(__('%.2f hour',true), $issues->spent_hours($issue)), '/projects/'.$main_project['Project']['identifier'].'/timelog/details?issue_id='.$issue['Issue']['id'], array('class'=>'icon icon-time')) : "-"; ?></td>
    <?php endif; ?>
</tr>
<tr>
    <td class="fixed-version"><b><?php __('Target version') ?>:</b></td><td><?php echo strlen($issue['Issue']['fixed_version_id']) ? $candy->link_to_version($issue['FixedVersion']) : "-" ?></td>
    <?php if(!empty($issue['Issue']['estimated_hours'])): ?>
    <td class="estimated-hours"><b><?php __('Estimated time') ?>:</b></td><td><?php $candy->lwr('%.2f hour', $issue['Issue']['estimated_hours']) ?></td>
    <?php endif; ?>
</tr>
<tr>
<?php $n = 0; ?>
<?php if(!empty($issue['CustomValue'])): ?>
  <?php foreach($issue['CustomValue'] as $value): ?>
    <td valign="top">
      <b><?php echo h($value['CustomField']['name']); ?>:</b></td><td valign="top"><?php echo h($customField->value($value)); ?></td>
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
<!-- TODO : call_hook(:view_issues_show_details_bottom, :issue => @issue) -->
</table>
<hr />

<div class="contextual">
  <?php if($candy->authorize_for(aa('controller','issues', 'action','reply')) && !empty($issue['Issue']['description'])) echo $ajax->link(__('Quote', true), array('controller' => 'issues', 'action' => 'reply', 'id' => $issue['Issue']['id']), array('class' => 'icon icon-comment')); ?>
</div>

<p><strong><?php __('Description') ?></strong></p>
<div class="wiki">
  <?php echo $candy->textilizable($issue['Issue']['description']); ?>
</div>

<?php
  // Copy from AttachmentHelper, because can not call element from Helper.
  if(array_key_exists('Author', $issue)) {
    $attach_options = array_merge(array('deletable'=>$attachmentsDeletable), $issue);
    echo $this->renderElement('attachments/links', array('attachments'=>$attachments, 'options'=>$attach_options), array('Number'));
  }
?>
<?php if($candy->authorize_for(':issue_relations') || !empty($issue['Relations'])) : /* TODO relation */ ?>
<hr />
<div id="relations">
  <?php echo $this->renderElement('issues/relations', compact('issue', 'issueRelations')); ?>
</div>
<?php endif; ?>

<?php if($candy->authorize_for(':add_issue_watchers') || !empty($issue['Watcher']) && $candy->authorize_for(':view_issue_watchers')): ?>
<hr />
<div id="watchers">
  <?php echo $this->renderElement('watchers/watchers', array(
    'list'=>!empty($issue['Watcher'])?$issue['Watcher']:array(), 
    'object_type'=>'issue', 
    'watched'=>$issue['Issue']['id'], 
    'addIssueWatchersAllowed'=>$candy->authorize_for(':add_issue_watchers')
    ), 'Watchers'); ?>
</div>
<?php endif; ?>

</div>

<?php if(!empty($issue['Changeset']) && $candy->authorize_for(':view_changesets')): ?>
<div id="issue-changesets">
<h3><?php __('Associated revisions') ?></h3>
<!--<%= render :partial => 'changesets', :locals => { :changesets => @issue.changesets} %>-->
</div>
<?php endif; ?>

<?php if(!empty($journalList)): ?>
<div id="history">
<h3><?php __('History') ?></h3>
  <?php echo $this->renderElement('issues/history', array(
    'journalList'=>$journalList, 
    'issue'=>$issue
    )); ?>
</div>
<?php endif; ?>
<div style="clear: both;"></div>

<?php if($candy->authorize_for(array('controller'=>'issues', 'action'=>'edit'))): ?>
  <div id="update" style="display:none;">
  <h3><?php __('Update');?></h3>
    <?php echo $this->renderElement('issues/edit', compact(
      'statuses', 'priorities', 'assignableUsers', 'issueCategories', 
      'fixedVersions', 'customFieldValues')); ?>
  </div>
<?php endif; ?>

<p class="other-formats">
<?php __("'Also available in:'") ?>
<span><?php echo $html->link('Atom', array('action'=>'show', 'id'=>$issue['Issue']['id'], 'format'=>'atom', 'key'=>$rssToken), array('class'=>'feed'));?></span>
<span><?php echo $html->link('PDF', array('action'=>'show', 'id'=>$issue['Issue']['id'], 'format'=>'pdf'), array('class'=>'pdf')); ?></span>
</p>

    <?php $candy->html_title($issue['Tracker']['name'] . ' #' . $issue['Issue']['id'], ' ' . $issue['Issue']['subject']) ?>

<?php $this->set('Sidebar', $this->renderElement('issues/sidebar')) ?>
<?php $html->meta('atom', array('action'=>'show', 'id'=>$issue['Issue']['id'], 'format'=>'atom', 'key'=>$rssToken), array('title'=>$issue['Project']['name'].' - '.$issue['Tracker']['name'].' ##'.$issue['Issue']['id'].': '.$issue['Issue']['subject'], 'rel'=>'alternate'), false); ?>
<?php $html->css('scm.css', null, array('media'=>'screen'), false); ?>
