<?php if (!empty($versions_data)) : ?>
<table class="list">
	<thead>
    <th><?php echo __('Version') ?></th>
    <th><?php echo __('Date') ?></th>
    <th><?php echo __('Description') ?></th>
    <th><?php echo __('Wiki page') ?></th>
    <th style="width:15%"></th>
    <th style="width:15%"></th>
    </thead>
	<tbody>
<!-- TODO: sort -->
<?php foreach ($versions_data as $version_row): ?>
    <tr class="<?php echo $this->Candy->cycle() ?>">
    <td><?php echo $this->Html->link($version_row['Version']['name'],array(
		'controller' => 'versions',
		'action' => 'show',
		$version_row['Version']['id'])); ?></td>
    <td align="center"><?php echo $this->Candy->format_date($version_row['Version']['effective_date']) ?></td>
    <td><?php echo h($version_row['Version']['description']) ?></td>
    <td><?php if (!empty($version_row['Version']['wiki_page_title'])) {
		echo $this->Html->link(
			$version_row['Version']['wiki_page_title'],
			array(
				'controller' => 'wiki',
				'action' => 'index',
				'project_id' => $main_project['Project']['identifier'],
				'wikipage' => $this->Wiki->titleize($version_row['Version']['wiki_page_title'])
			)
		);
		} ?></td>
    <td align="center"><?php echo $this->Html->link(__('Edit'),array(
		'controller' => 'versions',
		'action' => 'edit',
		$version_row['Version']['id']
	),
	array('class' => 'icon icon-edit')); ?></td>
    <td align="center"><?php echo $this->Html->link(__('Delete'),array(
		'controller' => 'versions',
		'action' => 'destroy',
		$version_row['Version']['id']
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

<!-- TOOO: auth -->
<p><?php echo $this->Html->link(__('New version'),array(
	'controller' => 'projects',
	'action' => 'add_version',
	'project_id' => $main_project['Project']['identifier']
)); ?></p>

