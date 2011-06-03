<h2><?php __('Lost password') ?></h2>

<?php // error_messages_for 'user' ?>

<?php echo $form->create(null, array('class' => 'tabular', 'url' => "/account/lost_password/token:{$token['Token']['value']}")); ?>
<div class="box">
  <p>
    <?php __('New password') ?>
    <?php echo $form->password('new_password' , array('label' => false, 'div' => false, 'size' => '25')); ?>
    &nbsp;<span class="required">*</span>
    <em><?php $candy->lwr('Must be at least %d characters long.', 4) ?></em>
  </p>

  <p>
    <?php __('Confirmation') ?>
    <?php echo $form->password('new_password_confirmation' , array('label' => false, 'div' => false, 'size' => '25')); ?>
    <span class="required">*</span>
  </p>
  <p>
    <?php echo $form->submit(__('Save', true), array('div' => false)); ?>
  </p>
</div>
<?php echo $form->end(); ?>
