<?php if ( !empty($issue_categories_data) ) : ?>
<table class="list">
	<thead>
	<th><?php echo __('Issue category') ?></th>
	<th><?php echo __('Assigned to') ?></th>
	<th style="width:15%"></th>
	<th style="width:15%"></th>
	</thead>
	<tbody>
<?php foreach ($issue_categories_data as $issue_category_row): ?>
	<tr class="<?php echo $this->Candy->cycle() ?>">
    <td><?php echo h($issue_category_row['IssueCategory']['name']) ?></td>
    <td><?php if ( !empty($issue_category_row['AssignedTo'])) { echo h($this->Candy->format_username($issue_category_row['AssignedTo'])); } ?></td>
    <td align="center"><?php echo $this->Candy->link_to_if_authorized(array(
		'controller' => 'issue_categories',
		'action' => 'edit'
	),
	__('Edit'),
	array(
		'controller' => 'issue_categories',
		'action' => 'edit',
		'project_id' => $main_project['Project']['identifier'],
		'id' => $issue_category_row['IssueCategory']['id']
	),
	array(
		'class' => 'icon icon-edit'
	)); ?></td>
    <td align="center"><?php echo $this->Candy->link_to_if_authorized(array(
		'controller' => 'issue_categories',
		'action' => 'edit'
	),
	__('Delete'),
	array(
		'controller' => 'issue_categories',
		'action' => 'destroy',
		'project_id' => $main_project['Project']['identifier'],
		'id' => $issue_category_row['IssueCategory']['id']
	),
	array(
		'class' => 'icon icon-del',
		'confirm' => __('Are you sure ?'),
		'method' => 'post'
	)
	); ?></td>
	</tr>
<?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
<p class="nodata"><?php echo __('No data to display') ?></p>
<?php endif; ?>
<p><?php echo $this->Candy->link_to_if_authorized(array(
	'controller' => 'projects',
	'action' => 'add_issue_category'
),
__('New category'),
array(
	'controller' => 'projects',
	'action' => 'add_issue_category',
	'project_id' => $main_project['Project']['identifier']
)); ?></p>
