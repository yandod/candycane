<h2><?php echo __('Change password') ?></h2>

<?php echo $this->renderElement('error_explanation'); ?>

<?php echo $this->Form->create('User',aa('url',aa('controller','my','action','password'),'class','tabular')); ?>
<div class="box">
<p><label for="password"><?php echo __('Password') ?> <span class="required">*</span></label>
<?php echo $this->Form->password('password',aa('size',25,'value','')) ?></p>
<p><label for="new_password"><?php echo __('New password') ?> <span class="required">*</span></label>
<?php echo $this->Form->password('new_password',aa('size',25,'value','')) ?><br />
<em><?php $this->Candy->lwr('Must be at least %d characters long.',4) ?></em></p>

<p><label for="new_password_confirmation"><?php echo __('Confirmation') ?> <span class="required">*</span></label>
<?php echo $this->Form->password('new_password_confirmation',aa('size',25,'value','')) ?></p>
</div>
<?php echo $this->Form->submit(__('Apply')) ?>
<?php echo $this->Form->end() ?>

<?php $this->set('Sidebar',$this->renderElement('my/sidebar')) ?>
