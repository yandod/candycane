<h2><?php __('label_information_plural'); ?></h2>

<p>
<strong><%= Redmine::Info.versioned_name %></strong> (<?php echo $db_driver; ?>)
</p>

<table class="list">
<tr class="odd">
  <td><?php __('text_default_administrator_account_changed'); ?></td>
  <td><?php // image_tag (@flags[:default_admin_changed] ? 'true.png' : 'false.png'), :style => "vertical-align:bottom;" ?></td>
</tr>
<tr class="even">
  <td><?php __('text_file_repository_writable') ?> (<?php // Attachment.storage_path ?>)</td>
  <td><?php // image_tag (@flags[:file_repository_writable] ? 'true.png' : 'false.png'), :style => "vertical-align:bottom;" ?></td>
</tr>
<tr class="even">
  <td><?php __('text_plugin_assets_writable'); ?> (<?php //Engines.public_directory ?>)</td>
  <td><?php //image_tag (@flags[:plugin_assets_writable] ? 'true.png' : 'false.png'), :style => "vertical-align:bottom;" ?></td>
</tr>
<tr class="odd">
  <td><?php __('text_rmagick_available'); ?></td>
  <td><?php //image_tag (@flags[:rmagick_available] ? 'true.png' : 'false.png'), :style => "vertical-align:bottom;" ?></td>
</tr>
</table>

<?php $candy->html_title(__('label_information_plural', true)); ?>
