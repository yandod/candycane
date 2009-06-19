<h2><?php __('Change password') ?></h2>

<?php echo $this->renderElement('error_explanation'); ?>

<?php e($form->create('User',aa('url',aa('controller','my','action','password'),'class','tabular'))); ?>
<div class="box">
<p><label for="password"><?php __('Password') ?> <span class="required">*</span></label>
<?php echo $form->password('password',aa('size',25,'value','')) ?></p>
<p><label for="new_password"><?php __('New password') ?> <span class="required">*</span></label>
<?php echo $form->password('new_password',aa('size',25,'value','')) ?><br />
<em><?php $candy->lwr('Must be at least %d characters long.',4) ?></em></p>

<p><label for="new_password_confirmation"><?php __('Confirmation') ?> <span class="required">*</span></label>
<?php echo $form->password('new_password_confirmation',aa('size',25,'value','')) ?></p>
</div>
<?php echo $form->submit(__('Apply',true)) ?>
<?php echo $form->end() ?>

<?php $this->set('Sidebar',$this->renderElement('my/sidebar')) ?>
