<?php
if( $currentuser[ 'admin' ] )
{
	$this->set( 'Sidebar', $this->element( 'admin/sidebar' ) );
}
?>

<h2><?php echo __('Workflow'); ?></h2>

<?php if (empty($roles) && empry($trackers)): ?>
<p class="nodata"><?php echo __('No data to display'); ?></p>
<?php else: ?>
<table class="list">
<thead>
    <tr>
      <th></th>
      <?php foreach($roles as $role): ?>
      <th>
        <?php
          if ($role['Role']['builtin'] == true) {
            $tag = 'em';
          } else {
            $tag = 'span';
          }
          echo $this->Html->tag($tag, $role['Role']['name']);
        ?>
      </th>
      <?php endforeach; ?>
    </tr>
</thead>
<tbody>
<?php foreach ($trackers as $tracker): ?>
<tr class="<?php echo $this->Candy->cycle('odd', 'even'); ?>">
<td><?php echo h($tracker['Tracker']['name']); ?></td>
  <?php foreach ($roles as $role): ?>
    <td align="center">
      <?php
        $tracker_id = $tracker['Tracker']['id'];
        $role_id = $role['Role']['id'];

        if ($counts[$tracker_id][$role_id] > 0) {
          $link = $counts[$tracker_id][$role_id];
        } else {
          $link = $this->Html->image('false.png');
        }

        echo $this->Html->link($link,
                 'edit?role_id=' . $role['Role']['id'] . '&tracker_id=' . $tracker['Tracker']['id'],
                 array('title' => __('Edit'), 'escape' => false));
      ?>
    </td>
    <?php endforeach; ?>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php endif; ?>
