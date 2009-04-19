<div class="contextual">
  <?php if($buttonUpdateAllowed) echo $html->link(__('Update', true), array('controller' => 'issues', 'action' => 'edit', 'id' => $issue['Issue']['id']), array('onclick' => 'showAndScrollTo("update", "notes"); return false;', 'class' => 'icon icon-edit', 'accesskey' => 'accesskey(:edit)')); ?>
  <?php if($buttonLogTimeAllowed) echo $html->link(__('Log time', true), array('controller' => 'timelog', 'action' => 'edit', 'id' => $issue['Issue']['id']), array('class' => 'icon icon-time')) ?>
  <!--<%= watcher_tag(@issue, User.current) %>-->
  <?php if($buttonCopyAllowed) echo $html->link(__('Copy', true), '/projects/'.$main_project['Project']['identifier'].'/issues/add/copy_from:'.$issue['Issue']['id'], array('class' => 'icon icon-copy')) ?>
  <?php if($buttonMoveAllowed) echo $html->link(__('Move', true), array('controller' => 'issues', 'action' => 'move', 'id' => $issue['Issue']['id']), array('class' => 'icon icon-move')); ?>
  <?php if($buttonDeleteAllowed) echo $html->link(__('Delete', true), array('controller' => 'issues', 'action' => 'destroy', 'id' => $issue['Issue']['id']), array('class' => 'icon icon-del'), __('Are you sure ?',true)); ?>
</div>

<h2><?php echo h($issue['Tracker']['name']) ?> #<?php echo h($issue['Issue']['id']) ?></h2>

<div class="<?php echo h($issues->css_issue_classes($issue)) ?>">
        <?php echo $candy->avatar(array('User' => $issue['Author']), array('size' => 64)) ?>
        <h3><?php echo h($issue['Issue']['subject']) ?></h3>
        <p class="author">
        <?php echo $candy->authoring($issue['Issue']['created_on'], $issue['Author']) ?>.
        <?php if ($issue['Issue']['created_on'] !=  $issue['Issue']['updated_on']) echo $candy->lwr('Updated %s ago', $candy->distance_of_time_in_words(time(), $issue['Issue']['updated_on'])) ?>
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
    <td class="assigned-to"><b><?php __('Assigned to') ?>:</b></td><td><?php echo $candy->avatar(array('User' => $issue['Author']), array('size' => 14)) ?><?php echo strlen($issue['Issue']['assigned_to_id']) ? 'link_to_user(@issue.assigned_to)' : "-" ?></td>
    <td class="progress"><b><?php __('done_ratio') ?>:</b></td><td class="progress"><?php echo $candy->progress_bar($issue['Issue']['done_ratio'], array('width'=>'80px', 'legend'=>$issue['Issue']['done_ratio'].'%')); ?></td>
</tr>
<tr>
    <td class="category"><b><?php __('Category') ?>:</b></td><td><?php echo h(strlen($issue['Issue']['category_id']) ? $issue['Category']['name'] : "-") ?></td>
    <?php if($viewTimeEntriesAllowed): ?>
    <td class="spent-time"><b><?php __('Spent time') ?>:</b></td>
    <td class="spent-hours"><?php echo ($issues->spent_hours($issue) > 0) ? $html->link(sprintf(__('%.2f hour',true), $issues->spent_hours($issue)), '/projects/'.$main_project['Project']['identifier'].'/timelog/details/'.$issue['Issue']['id'], array('class'=>'icon icon-time')) : "-"; ?></td>
    <?php endif; ?>
</tr>
<tr>
    <td class="fixed-version"><b><?php __('Target version') ?>:</b></td><td><?php echo h(strlen($issue['Issue']['fixed_version_id']) ? $issue['FixedVersion']['name'] : "-") ?></td>
    <?php if(!empty($issue['Issue']['estimated_hours'])): ?>
    <td class="estimated-hours"><b><?php __('Estimated time') ?>:</b></td><td><?php echo sprintf(__('%.2f hour',true), $issue['Issue']['estimated_hours']) ?></td>
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
<%= call_hook(:view_issues_show_details_bottom, :issue => @issue) %>
</table>
<hr />

<div class="contextual">
  <?php if($buttonQuoteAllowed && !empty($issue['Issue']['description'])) echo $ajax->link(__('Quote', true), array('controller' => 'issues', 'action' => 'reply', 'id' => $issue['Issue']['id']), array('class' => 'icon icon-comment')); ?>
</div>

<p><strong><?php __('Description') ?></strong></p>
<div class="wiki">
  <?php e(nl2br(h($issue['Issue']['description']))); ?>
</div>

<%= link_to_attachments @issue %>

<?php if($issueRelationsAllowed || !empty($issue['Relations'])) : /* TODO relation */ ?>
<hr />
<div id="relations">
<!--<%= render :partial => 'relations' %>-->
</div>
<?php endif; ?>

<?php if($addIssueWatchersAllowed || !empty($issue['Watcher']) && $viewIssueWatchersAllowed): ?>
<hr />
<div id="watchers">
  <?php echo $this->renderElement('watchers/watchers', array(
    'list'=>$issue['Watcher'], 
    'object_type'=>'issue', 
    'watched'=>$issue['Issue']['id'], 
    'addIssueWatchersAllowed'=>$addIssueWatchersAllowed
    ), 'Watchers'); ?>
</div>
<?php endif; ?>

</div>

<!--<% if @issue.changesets.any? && User.current.allowed_to?(:view_changesets, @project) %>-->
<div id="issue-changesets">
<h3><?php __('Associated revisions') ?></h3>
<!--<%= render :partial => 'changesets', :locals => { :changesets => @issue.changesets} %>-->
</div>
<!--<% end %>-->

<!--<% if @journals.any? %>-->
<div id="history">
<h3><?php __('History') ?></h3>
<!--<%= render :partial => 'history', :locals => { :journals => @journals } %>-->
</div>
<!--<% end %>-->
<div style="clear: both;"></div>

<!--<% if authorize_for('issues', 'edit') %>-->
  <div id="update" style="display:none;">
  <h3><!--<%= l(:button_update) %>--></h3>
  <!--<%= render :partial => 'edit' %>-->
  </div>
<!--<% end %>-->

<p class="other-formats">
<?php __("'Also available in:'") ?>
<span><!--<%= link_to 'Atom', {:format => 'atom', :key => User.current.rss_key}, :class => 'feed' %>--></span>
<span><!--<%= link_to 'PDF', {:format => 'pdf'}, :class => 'pdf' %>--></span>
</p>

    <?php $candy->html_title($issue['Tracker']['name'] . ' #' . $issue['Issue']['id'], ' ' . $issue['Issue']['subject']) ?>

<?php $this->set('Sidebar', $this->renderElement('issues/sidebar')) ?>

<!--<% content_for :header_tags do %>-->
    <!--<%= auto_discovery_link_tag(:atom, {:format => 'atom', :key => User.current.rss_key}, :title => "#{@issue.project} - #{@issue.tracker} ##{@issue.id}: #{@issue.subject}") %>-->
    <!--<%= stylesheet_link_tag 'scm' %>-->
<!--<% end %>-->
