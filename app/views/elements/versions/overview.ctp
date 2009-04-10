<?php if ($this->data['Version']['completed']): ?>
  <p><?php echo $time->nice($this->data['Version']['effective_date']) ?></p>
<?php elseif ($this->data['Version']['effective_date']): ?>
  <p><strong><?php echo $candy->due_date_distance_in_words($this->data['Version']['effective_date']) ?></strong> (<?php echo $time->nice($this->data['Version']['effective_date']) ?>)</p>
<?php endif ?>

<p><?php echo h($this->data['Version']['description']) ?></p>

<?php if ($fixed_issue_count > 0): ?>
    <%= progress_bar([version.closed_pourcent, version.completed_pourcent], :width => '40em', :legend => ('%0.0f%' % version.completed_pourcent)) %>
    <p class="progress-info">
        <?php echo $html->link($this->data['Version']['closed_issues_count'], array('issues/index?project_id='.$this->data['Version']['project_id'].'&status_id=c&fixed_version_id='.$this->data['Version']['id'].'&set_filter=1')) ?>
<?php /*
        :controller => 'issues', :action => 'index', :project_id => version.project, :status_id => 'c', :fixed_version_id => version, :set_filter => 1) ?>
 */?>
        <?php echo $candy->lwr('closed', $this->data['Version']['closed_issues_count']) ?>
        (<?php echo sprintf('%0.0f', floatval($this->data['Version']['closed_issues_count']) / $fixed_issue_count * 100) ?>%)
        &#160;
<?php /*
        <%= link_to(version.open_issues_count, :controller => 'issues', :action => 'index', :project_id => version.project, :status_id => 'o', :fixed_version_id => version, :set_filter => 1) %>
 */ ?>
        <?php echo $html->link($this->data['Version']['open_issues_count'], array('issues/index?project_id='.$this->data['Version']['project_id'].'&status_id=o&fixed_version_id='.$this->data['Version']['id'].'&set_filter=1')) ?>
        <?php echo $candy->lwr('open', $this->data['Version']['open_issues_count']) ?>
        (<?php echo sprintf('%0.0f', floatval($this->data['Version']['open_issues_count']) / $fixed_issue_count * 100) ?>%)
    </p>
<?php else: ?>
    <p><em><?php echo __('No issues for this version') ?></em></p>
<?php endif ?>

