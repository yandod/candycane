<?php
if( $currentuser[ 'admin' ] )
{
	$this->set( 'Sidebar', $this->element( 'admin/sidebar' ) );
}
?>

<h2><?php echo $this->Candy->html_title(__('Plugins')); ?></h2>

<?php if (! empty($plugins)): ?>
<table class="list plugins">
		<tr>
			<th><?php echo __('Description') ?></th>
			<th><?php echo __('Author') ?></th>
			<th><?php echo __('Available') ?></th>
			<th><?php echo __('Installed') ?></th>
			<th><?php echo __('Configure') ?></th>
		</tr>
    <?php foreach($plugins as $plugin): ?>
        <tr class="<?php echo $this->Candy->cycle('odd', 'even') ?>">
        <td><span class="name"><?php echo h($plugin['name']) ?></span>
			<?php echo $this->Html->tag('span',$plugin['description'],array('class'=>'description'))?>
			<?php
			if ( empty($plugin['url'])) {
				echo __('Local Plugin');
				echo '&nbsp;&nbsp;';
				echo $this->Html->link('test page of '.$plugin['id'],array(
					'controller' => 'home',
					'action' => 'index',
					'plugin' => $plugin['id']
				));
			} else {
				echo $this->Html->tag('span',$this->Html->link($plugin['url']),array('class'=>'url'));
			}
			?>
		</td>
        <td class="author"><?php
			if (empty($plugin['author'])) {
				echo __('Unknown');
			} else {
				echo $this->Html->link($plugin['author'],$plugin['author_url']);
			}
		?></td>
        <td class="version"><?php echo h($plugin['version'])?></td>
		<td class="installed"><?php echo h($plugin['installed'])?></td>
        <td class="configure"><?php
			if ($plugin['installed']) {
				if ($plugin['version'] > $plugin['installed']) {
					echo $this->Html->link(__('Upgrade'),array(
						'controller' => 'admin',
						'action' => 'upgradePlugin',
						$plugin['id']
					));
				} elseif (empty($plugin['url'])) {
					echo __('Local Plugin');
				} else {
					echo $this->Html->link(__('Uninstall'),array(
						'controller' => 'admin',
						'action' => 'uninstallPlugin',
						$plugin['id']
					));
				}
			} else {
				echo $this->Html->link(__('Install'),array(
					'controller' => 'admin',
					'action' => 'installPlugin',
					$plugin['id']
					));
			}
		?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
<p class="nodata"><?php echo __('No data to display'); ?></p>
<?php endif; ?>
<p class="nodata">
	<?php echo __('Do you want to create your own plugin? Run command bellow to create your plugin.'); ?><br/>
	<strong>php lib/Cake/Console/cake.php cc_plugin</strong>
</p>
