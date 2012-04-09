<?php echo $this->Form->create('Setting',aa('action','edit','url',aa('?','tab=notifications'))) ?>

<div class="box tabular settings">
<p><label><?php echo __('Emission email address') ?></label>
<?php echo $this->Form->input('mail_from',aa('value',$Settings->mail_from,'size',60,'label',false,'div',false))?></p>

<p><label><?php echo __('Blind carbon copy recipients (bcc)') ?></label>
<?php echo $this->Form->checkbox('bcc_recipients', aa('checked', ($Settings->bcc_recipients == '1'))); ?></p>

<p><label><?php echo __('plain text mail (no HTML)') ?></label>
<?php echo $this->Form->checkbox('plain_text_mail', aa('checked', ($Settings->plain_text_mail == '1'))); ?></p>
</div>
<?php /*
<fieldset class="box" id="notified_events"><legend><?php echo __('Select actions for which email notifications should be sent.') ?></legend>
<% @notifiables.each do |notifiable| %>
  <label><%= check_box_tag 'settings[notified_events][]', notifiable, Setting.notified_events.include?(notifiable) %>
  <%= l_or_humanize(notifiable, :prefix => 'label_') %></label><br />
<% end %>
<%= hidden_field_tag 'settings[notified_events][]', '' %>
<p><%= check_all_links('notified_events') %></p>
</fieldset>
*/ ?>

<fieldset class="box"><legend><?php echo __('Emails footer') ?></legend>
<?php echo $this->Form->textarea('emails_footer',aa('value',$Settings->emails_footer,'cols',60,'rows',5,'class','wiki-edit')) ?></p>
</fieldset>

<div style="float:right;">
<?php echo $this->Html->link(__('Send a test email'),aa('controller','admin','action','test_email')) ?>
</div>

<?php echo $this->Form->submit(__('Save')) ?>
<?php echo $this->Form->end(); ?>
<?php
//<div class="nodata">
//<%= simple_format(l(:text_email_delivery_not_configured)) %>
//</div>
?>
