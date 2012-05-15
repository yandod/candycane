<h2><?php echo $this->Candy->html_title(__('Information')); ?></h2>

<?php $this->set('Sidebar', $this->element('admin/sidebar')) ?>

<table class="list">
<tr class="odd">
  <td><?php echo __('CandyCane version'); ?></td>
  <td><?php echo CANDYCANE_VERSION; ?></td>
</tr>
<tr class="even">
  <td><?php echo __('database driver') ?></td>
  <td><?php echo $db_driver; ?></td>
</tr>
</table>
