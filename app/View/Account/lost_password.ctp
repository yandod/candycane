<h2><?php echo $this->Candy->html_title(__('Lost password')); ?></h2>

<div class="box">
    <p>
    <?php echo $this->Form->create(null, array('class' => 'tabular', 'url' => '/account/lost_password')); ?>
    <?php echo __('Email') ?>
    <?php echo $this->Form->input('mail' , array('label' => false, 'div' => false, 'size' => '40%')); ?>
    &nbsp;<span class="required">*</span>
    <?php echo $this->Form->submit(__('Submit'), array('div' => false)); ?>
    <?php echo $this->Form->end(); ?>
    </p>
</div>
