<div class="contextual">
<?php if ( empty($currentuser['auth_source_id'])) {
	echo $this->Html->link(
		__('Change password'),
		array(
			'action' => 'password'
		)
	);
} ?>
</div>

<h2><?php echo $this->Candy->html_title(__('My account')) ?></h2>
<?php echo $this->element('error_explanation'); ?>


<?php echo $this->Form->create('User',array('url'=>array('controller' => 'my','action' => 'account'))); ?>
<div class="splitcontentleft">
  <h3><?php echo __('Information'); ?></h3>
  <div class="box tabular">
    <?php echo $this->element('my/informations') ?>
  </div>

  <?php echo $this->Form->submit(__('Save')); ?>
</div>

<div class="splitcontentright">
  <h3><?php echo __('Email notifications') ?></h3>
  <div class="box">
    <?php echo $this->element('my/mail_notifications') ?>
  </div>

  <h3><?php echo __('Preferences') ?></h3>
  <div class="box tabular">
    <?php echo $this->element('my/preferences') ?>
  </div>
<?php echo "</div>" ?>

<?php echo $this->Form->end() ?>
<?php $this->set('Sidebar',$this->element('my/sidebar')) ?>

