<?php echo $form->create('Setting',aa('action','edit','url',aa('?','tab=authentication'))) ?>
<div class="box tabular settings">
<p><label><?php __('Authentication required') ?></label>
<?php echo $form->checkbox('login_required', aa('checked', ($Settings->login_required == '1'))); ?></p>

<p><label><?php __('Autologin') ?></label>
<?php
$autologin_values = array(
  0 => __('disabled',true),
  1 => '1'.__('days',true),
  7 => '7'.__('days',true),
  30 => '30'.__('days',true),
  365 => '365'.__('days',true)
);
?><?php echo $form->select('autologin',$autologin_values,$Settings->autologin,array(),null) ?></p>

<p><label><?php __('Self-registration') ?></label>
<?php $self_registration_values = array(
  0 => __('disabled',true),
  1 => __('account activation by email',true),
  2 => __('manual account activation',true),
  3 => __('automatic account activation',true)
); ?><?php echo $form->select('self_registration',$self_registration_values,$Settings->self_registration,array(),null) ?></p>

<p><label><?php __('Lost password') ?></label>
<?php echo $form->checkbox('lost_password', aa('checked', ($Settings->lost_password == '1'))); ?></p>
</div>

<div style="float:right;">
    <?php echo $html->link(__('LDAP authentication',true),aa('controller','auth_sources','action','list')) ?>
</div>

<?php echo $form->submit(__('Save',true)) ?>
<?php echo $form->end(); ?>
