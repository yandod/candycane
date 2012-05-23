<h2><?php echo __('Lost password') ?></h2>

<?php // error_messages_for 'user' ?>

<?php echo $this->Form->create(null, array('class' => 'tabular', 'url' => "/account/lost_password/token:{$token['Token']['value']}")); ?>
<div class="box">
  <p>
    <?php echo __('New password') ?>
    <?php echo $this->Form->password('new_password' , array('label' => false, 'div' => false, 'size' => '25')); ?>
    &nbsp;<span class="required">*</span>
    <em><?php $this->Candy->lwr('Must be at least %d characters long.', 4) ?></em>
  </p>

  <p>
    <?php echo __('Confirmation') ?>
    <?php echo $this->Form->password('new_password_confirmation' , array('label' => false, 'div' => false, 'size' => '25')); ?>
    <span class="required">*</span>
  </p>
  <p>
    <?php echo $this->Form->submit(__('Save'), array('div' => false)); ?>
  </p>
</div>
<?php echo $this->Form->end(); ?>
