<?php if (!empty($main_project['Version'])) : ?>
<table class="list">
	<thead>
    <th><?php __('Version') ?></th>
    <th><?php __('Date') ?></th>
    <th><?php __('Description') ?></th>
    <th><?php __('Wiki page') ?></th>
    <th style="width:15%"></th>
    <th style="width:15%"></th>
    </thead>
	<tbody>
<!-- TODO: sort -->
<?php foreach ($main_project['Version'] as $version_row): ?>
    <tr class="<?php echo $candy->cycle() ?>">
    <td><?php echo $html->link($version_row['name'],aa('controller','versions','action','show','id',$version_row['id'])); ?></td>
    <td align="center"><?php echo $candy->format_date($version_row['effective_date']) ?></td>
    <td><?php echo h($version_row['description']) ?></td>
    <td><?php if (!empty($version_row['wiki_page_title'])): ?><?php echo $html->link($version_row['wiki_page_title'],aa('controller','wiki','page',Wiki::titleize($version_row['wiki_page_title']))); ?><?php endif; ?></td>
    <td align="center"><?php echo $html->link(__('Edit',true),aa('controller','versions','action','edit','id',$version_row['id']),aa('class','icon icon-edit')); ?></td>
    <td align="center"><?php echo $html->link(__('Delete',true),aa('controller','versions','action','destroy','id',$version_row['id']),aa('class','icon icon-del','confirm',__('Are you sure ?',true),'method','post')); ?></td>
    </tr>
<?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
<p class="nodata"><?php __('No data to display') ?></p>
<?php endif; ?>

<!-- TOOO: auth -->
<p><?php echo $html->link(__('New version',true),aa('controller','projects','action','add_version','id',$main_project['Project']['identifier'])); ?></p>

