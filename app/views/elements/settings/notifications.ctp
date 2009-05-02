<?php echo $form->create('Setting',aa('action','edit','url',aa('?','tab=notifications'))) ?>

<div class="box tabular settings">
<p><label><?php __('Emission email address') ?></label>
<?php echo $form->input('mail_from',aa('value',$Settings->mail_from,'size',60,'label',false,'div',false))?></p>

<p><label><?php __('Blind carbon copy recipients (bcc)') ?></label>
<?php echo $form->checkbox('bcc_recipients', aa('checked', ($Settings->bcc_recipients == '1'))); ?></p>

<p><label><?php __('plain text mail (no HTML)') ?></label>
<?php echo $form->checkbox('plain_text_mail', aa('checked', ($Settings->plain_text_mail == '1'))); ?></p>
</div>
<?php /*
<fieldset class="box" id="notified_events"><legend><?php __('Select actions for which email notifications should be sent.') ?></legend>
<% @notifiables.each do |notifiable| %>
  <label><%= check_box_tag 'settings[notified_events][]', notifiable, Setting.notified_events.include?(notifiable) %>
  <%= l_or_humanize(notifiable, :prefix => 'label_') %></label><br />
<% end %>
<%= hidden_field_tag 'settings[notified_events][]', '' %>
<p><%= check_all_links('notified_events') %></p>
</fieldset>
*/ ?>

<fieldset class="box"><legend><?php __('Emails footer') ?></legend>
<?php echo $form->textarea('emails_footer',aa('value',$Settings->emails_footer,'cols',60,'rows',5,'class','wiki-edit')) ?></p>
</fieldset>

<div style="float:right;">
<?php echo $html->link(__('Send a test email',true),aa('controller','admin','action','test_email')) ?>
</div>

<?php echo $form->submit(__('Save',true)) ?>
<?php echo $form->end(); ?>
<?php
//<div class="nodata">
//<%= simple_format(l(:text_email_delivery_not_configured)) %>
//</div>
?>
