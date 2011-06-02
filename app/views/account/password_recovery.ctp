<h2><?php __('label_password_lost') ?></h2>

<?php // error_messages_for 'user' ?>

<?php echo $form->create(null, array('class' => 'tabular', 'url' => "/account/lost_password/token:{$token['Token']['value']}")); ?>
<div class="box">
  <p>
    <?php __('new_password') ?>
    <?php echo $form->password('new_password' , array('label' => false, 'div' => false, 'size' => '25')); ?>
    &nbsp;<span class="required">*</span>
    <em><?php __('text_caracters_minimum', 4) ?></em>
  </p>

  <p>
    <?php __('field_password_confirmation') ?>
    <?php echo $form->password('new_password_confirmation' , array('label' => false, 'div' => false, 'size' => '25')); ?>
    <span class="required">*</span>
  </p>
  <p>
    <?php echo $form->submit(__('button_save', true), array('div' => false)); ?>
  </p>
</div>
<?php echo $form->end(); ?>
