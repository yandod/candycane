<h2><?php echo $candy->html_title(__('Information',true)); ?></h2>

<table class="list">
<tr class="odd">
  <td><?php echo __('CandyCane version',true); ?></td>
  <td><?php echo CANDYCANE_VERSION; ?></td>
</tr>
<tr class="even">
  <td><?php echo __('database driver',true) ?></td>
  <td><?php echo $db_driver; ?></td>
</tr>
</table>
