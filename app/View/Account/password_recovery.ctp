<h2><?php echo $this->Candy->html_title(__('Lost password')) ?></h2>

<?php // error_messages_for 'user' ?>

<?php echo $this->Form->create(null, array('class' => 'tabular', 'url' => "/account/lost_password/token:{$token['Token']['value']}")); ?>
<div class="box">
    <table>
        <tr>
            <td>
                <p>
                    <?php echo __('New password') ?>
                    <?php echo $this->Form->password('new_password' , array('label' => false, 'div' => false, 'size' => '25')); ?>
                    &nbsp;<span class="required">*</span>
                    <em><?php $this->Candy->lwr('Must be at least %d characters long.', 4) ?></em>
                </p>
            </td>
        </tr>

        <tr>
            <td>
                <p>
                    <?php echo __('Confirmation') ?>
                    <?php echo $this->Form->password('new_password_confirmation' , array('label' => false, 'div' => false, 'size' => '25')); ?>
                    <span class="required">*</span>
                </p>
            </td>
        </tr>

        <tr>
            <td>
                <p>
                    <?php echo $this->Form->submit(__('Save'), array('div' => false)); ?>
                </p>
            </td>
        </tr>
    </table>
</div>
<?php echo $this->Form->end(); ?>
