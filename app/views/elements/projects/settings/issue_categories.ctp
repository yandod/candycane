<?php if ( !empty($issue_categories_data) ) : ?>
<table class="list">
	<thead>
	<th><?php __('Issue category') ?></th>
	<th><?php __('Assigned to') ?></th>
	<th style="width:15%"></th>
	<th style="width:15%"></th>
	</thead>
	<tbody>
<?php foreach ($issue_categories_data as $issue_category_row): ?>
	<tr class="<?php echo $candy->cycle() ?>">
    <td><?php echo h($issue_category_row['IssueCategory']['name']) ?></td>
    <td><?php if ( !empty($issue_category_row['AssignedTo'])) { echo h($candy->format_username($issue_category_row['AssignedTo'])); } ?></td>
    <td align="center"><?php echo $candy->link_to_if_authorized(aa('controller','issue_categories','action','edit'),__('Edit',true),aa('controller','issue_categories','action','edit','project_id',$main_project['Project']['identifier'],'id',$issue_category_row['IssueCategory']['id']),aa('class','icon icon-edit')); ?></td>
    <td align="center"><?php echo $candy->link_to_if_authorized(aa('controller','issue_categories','action','edit'),__('Delete',true),aa('controller','issue_categories','action','destroy','project_id',$main_project['Project']['identifier'],'id',$issue_category_row['IssueCategory']['id']),aa('class','icon icon-del')); ?></td>
	</tr>
<?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
<p class="nodata"><?php __('No data to display') ?></p>
<?php endif; ?>
<p><?php echo $candy->link_to_if_authorized(aa('controller','projects','action','add_issue_category'),__('New category',true),aa('controller','projects','action','add_issue_category','project_id',$main_project['Project']['identifier'])); ?></p>
