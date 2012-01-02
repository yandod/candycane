<h2><?php __('Plugins'); ?></h2>

<?php if (! empty($plugins)): ?>
<table class="list plugins">
		<tr>
			<th><?php echo __('Description',true) ?></th>
			<th><?php echo __('Author',true) ?></th>
			<th><?php echo __('Available',true) ?></th>
			<th><?php echo __('Installed',true) ?></th>
			<th><?php echo __('Configure',true) ?></th>
		</tr>
    <?php foreach($plugins as $plugin): ?>
        <tr class="<?php echo $candy->cycle('odd', 'even') ?>">
        <td><span class="name"><?php echo h($plugin['name']) ?></span>
			<?php echo $html->tag('span',$plugin['description'],array('class'=>'description'))?>
			<?php
			if ( empty($plugin['url'])) {
				echo __('Local Plugin');
			} else {
				echo $html->tag('span',$html->link($plugin['url']),array('class'=>'url'));
			}
			?>
		</td>
        <td class="author"><?php
			if (empty($plugin['author'])) {
				echo __('Unknown');
			} else {
				echo $html->link($plugin['author'],$plugin['author_url']);
			}
		?></td>
        <td class="version"><?php echo h($plugin['version'])?></td>
		<td class="installed"><?php echo h($plugin['installed'])?></td>
        <td class="configure"><?php
			if ($plugin['installed']) {
				if ($plugin['version'] > $plugin['installed']) {
					echo __('Upgrade',true);
				} elseif (empty($plugin['url'])) {
					echo __('Local Plugin');
				} else {
					echo $html->link(__('Uninstall',true),array(
						'controller' => 'admin',
						'action' => 'uninstallPlugin',
						'id' => $plugin['id']
					));
				}
			} else {
				echo $html->link(__('Install',true),array(
					'controller' => 'admin',
					'action' => 'installPlugin',
					'id' => $plugin['id']
					));
			}
		?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
<p class="nodata"><?php __('No data to display'); ?></p>
<?php endif; ?>
