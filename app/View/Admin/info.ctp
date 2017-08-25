<?php
if( $currentuser[ 'admin' ] )
{
	$this->set( 'Sidebar', $this->element( 'admin/sidebar' ) );
}
?>

<h2><?php echo $this->Candy->html_title(__('Informations')); ?></h2>

<table class="list">
<tr class="odd">
  <td><?php echo __('CandyCane version'); ?></td>
  <td><?php echo CANDYCANE_VERSION; ?></td>
</tr>
<tr class="even">
  <td><?php echo __('CakePHP version') ?></td>
  <td><?php echo Configure::version(); ?></td>
</tr>
</table>

<h2><?php echo $this->Candy->html_title(__('Environment')); ?></h2>

<table class="list">
<tr class="odd">
  <td><?php echo __('PHP version'); ?></td>
  <td><?php echo PHP_VERSION; ?></td>
</tr>
<tr class="even">
  <td><?php echo __('Database driver') ?></td>
  <td><?php echo $db_driver; ?></td>
</tr>
<tr class="odd">
  <td><?php echo __('Database driver description'); ?></td>
  <td><?php echo $db_driver_description; ?></td>
</tr>
<tr class="even">
  <td><?php echo __('Database version'); ?></td>
  <td><?php echo $db_version; ?></td>
</tr>
<tr class="odd">
  <td><?php echo __('Database name'); ?></td>
  <td><?php echo $db_name; ?></td>
</tr>
</table>
