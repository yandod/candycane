<h2><?php __('label_password_lost'); ?></h2>

<div class="box">
    <p>
    <?php echo $form->create(null, array('class' => 'tabular', 'url' => '/account/lost_password')); ?>
    <?php __('mail') ?>
    <?php echo $form->input('mail' , array('label' => false, 'div' => false, 'size' => '40%')); ?>
    &nbsp;<span class="required">*</span>
    <?php echo $form->submit(__('button_submit', true), array('div' => false)); ?>
    <?php echo $form->end(); ?>
    </p>
</div>
