<span id="attachments_fields">
<?php echo $form->file('file', array('size'=>30)) ?>
<?php echo $form->input('description', array('size'=>60)) ?>
<em><?php __('Optional description') ?></em>
</span>
<br />
<small><?php echo $html->link(__('Add another file', true), '#', array('onclick' => 'addFileField(); return false;')) ?>
(<?php __('Maximum size') ?>: <%= number_to_human_size(Setting.attachment_max_size.to_i.kilobytes) %>)
</small>
