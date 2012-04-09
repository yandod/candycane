<?php echo $this->Form->create('Setting',aa('action','edit','url',aa('?','tab=authentication'))) ?>
<div class="box tabular settings">
<p><label><?php echo __('Authentication required') ?></label>
<?php echo $this->Form->checkbox('login_required', aa('checked', ($Settings->login_required == '1'))); ?></p>

<p><label><?php echo __('Autologin') ?></label>
<?php
$autologin_values = array(
  0 => __('disabled'),
  1 => '1'.__('days'),
  7 => '7'.__('days'),
  30 => '30'.__('days'),
  365 => '365'.__('days')
);
?><?php echo $this->Form->select('autologin',$autologin_values,$Settings->autologin,array(),null) ?></p>

<p><label><?php echo __('Self-registration') ?></label>
<?php $self_registration_values = array(
  0 => __('disabled'),
  1 => __('account activation by email'),
  2 => __('manual account activation'),
  3 => __('automatic account activation')
); ?><?php echo $this->Form->select('self_registration',$self_registration_values,$Settings->self_registration,array(),null) ?></p>

<p><label><?php echo __('Lost password') ?></label>
<?php echo $this->Form->checkbox('lost_password', aa('checked', ($Settings->lost_password == '1'))); ?></p>
</div>

<div style="float:right;">
    <?php echo $this->Html->link(__('LDAP authentication'),aa('controller','auth_sources','action','list')) ?>
</div>

<?php echo $this->Form->submit(__('Save')) ?>
<?php echo $this->Form->end(); ?>
