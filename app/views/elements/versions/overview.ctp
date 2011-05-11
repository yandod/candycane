<?php if ($version['completed']): ?>
  <p><?php echo $time->nice($version['effective_date']) ?></p>
<?php elseif ($version['effective_date']): ?>
  <p><strong><?php echo $candy->due_date_distance_in_words($version['effective_date']) ?></strong> (<?php echo $time->nice($version['effective_date']) ?>)</p>
<?php endif ?>

<p><?php echo h($version['description']) ?></p>

<?php if ($fixed_issue_count > 0): ?>
    <?php echo $candy->progress_bar(array($version['closed_pourcent'], $version['completed_pourcent']),array('width'=>'40em','legend'=>sprintf('%0.0f%%',$version['completed_pourcent']))) ?>
    <p class="progress-info">
        <?php echo $html->link(
			$version['closed_issues_count'], 
			array(
				'controller'=>'issues', 
				'action'=>'index', 
				'project_id'=>$main_project['Project']['identifier'], 
				'?' => array(
					'status_id'=>'c', 
					'fixed_version_id'=>$version['id'], 
					'set_filter'=>1
				)
			)
		) ?>
        <?php echo $candy->lwr('closed', $version['closed_issues_count']) ?>
        (<?php echo sprintf('%0.0f', floatval($version['closed_issues_count']) / $fixed_issue_count * 100) ?>%)
        &#160;
        <?php echo $html->link(
			$version['open_issues_count'], 
			array(
				'controller'=>'issues', 
				'action'=>'index', 
				'project_id'=>$main_project['Project']['identifier'], 
				'?' => array(
					'status_id'=>'o', 
					'fixed_version_id'=>$version['id'], 
					'set_filter'=>1
				)
			)
		) ?>
        <?php echo $candy->lwr('open', $version['open_issues_count']) ?>
        (<?php echo sprintf('%0.0f', floatval($version['open_issues_count']) / $fixed_issue_count * 100) ?>%)
    </p>
<?php else: ?>
    <p><em><?php echo __('No issues for this version') ?></em></p>
<?php endif ?>

