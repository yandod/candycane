<?php if ($this->data['Version']['completed']): ?>
  <p><?php echo $time->nice($this->data['Version']['effective_date']) ?></p>
<?php elseif ($this->data['Version']['effective_date']): ?>
  <p><strong><?php echo $candy->due_date_distance_in_words($this->data['Version']['effective_date']) ?></strong> (<?php echo $time->nice($this->data['Version']['effective_date']) ?>)</p>
<?php endif ?>

<p><?php echo h($this->data['Version']['description']) ?></p>

<?php if (count($this->data['FixedIssue']) > 0): ?>
    <%= progress_bar([version.closed_pourcent, version.completed_pourcent], :width => '40em', :legend => ('%0.0f%' % version.completed_pourcent)) %>
    <p class="progress-info">
        <%= link_to(version.closed_issues_count, :controller => 'issues', :action => 'index', :project_id => version.project, :status_id => 'c', :fixed_version_id => version, :set_filter => 1) %>
        <%= lwr(:label_closed_issues, version.closed_issues_count) %>
        (<%= '%0.0f' % (version.closed_issues_count.to_f / version.fixed_issues.count * 100) %>%)
        &#160;
        <%= link_to(version.open_issues_count, :controller => 'issues', :action => 'index', :project_id => version.project, :status_id => 'o', :fixed_version_id => version, :set_filter => 1) %>
        <%= lwr(:label_open_issues, version.open_issues_count)%>
        (<%= '%0.0f' % (version.open_issues_count.to_f / version.fixed_issues.count * 100) %>%)
    </p>
<?php else: ?>
    <p><em><?php echo __('No issues for this version') ?></em></p>
<?php endif ?>

