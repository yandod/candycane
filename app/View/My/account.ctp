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
    <p>
      <label for="UserFirstname"><?php echo __('Firstname') ?> <span class="required">*</span></label>
      <?php echo $this->Form->input('firstname',array('div' => false,'label' => false,'size' => 30,'error' => false)); ?>
    </p>
    <p>
      <label for="UserLastname"><?php echo __('Lastname') ?> <span class="required">*</span></label>
      <?php echo $this->Form->input('lastname',array('div' => false,'label' => false,'size' => 30,'error' => false)); ?>
    </p>
    <p>
      <label for="UserEmail"><?php echo __('Email') ?> <span class="required">*</span></label>
      <?php echo $this->Form->input('mail',array('div' => false,'label' => false,'size' => 30,'error' => false)); ?>
    </p>
    <p>
      <label for="UserLanguage"><?php echo __('Language'); ?></label>
      <?php echo $this->Form->select(
		'language',
		$this->Candy->lang_options_for_select(),
		array(
			'type' => 'select',
			'error' => false,
			'value' => $currentuser['language']
		)); ?>
    </p>
  </div>

  <?php echo $this->Form->submit(__('Save')); ?>
</div>

<div class="splitcontentright">
  <h3><?php echo __('Email notifications') ?></h3>
  <div class="box">
  <?php echo $this->Form->select(
	'notification_option',
	$notification_options,
	array(
		'value' => $notification_option,
		'onchange' => 'if ($("UserNotificationOption").value == "selected") {Element.show("notified-projects")} else {Element.hide("notified-projects")}'
	)) ?>
  <!-- <% content_tag 'div', :id => 'notified-projects', :style => (@notification_option == 'selected' ? '' : 'display:none;') do %>-->
  <?php $opt = array(
	  'id' => 'notified-projects',
	  'style' => $notification_option == 'selected' ? '' : 'display:none'
   ); ?>
  <?php echo $this->Html->tag('div',null,$opt) ?>
  <p>
    <?php foreach($currentuser['memberships'] as $row ): ?>
    <?php
      $opt = array('value' => $row['project_id']);
      if (in_array($row['project_id'],$this->request->data['User']['notified_project_ids'])) $opt['checked'] = 'checked';
    ?>
    <label><?php echo $this->Form->checkbox('notified_project_ids][',$opt) ?> <?php echo h($row['Project']['name']) ?></label><br />
    <?php endforeach; ?>
  </p>

  <p><em><?php echo __("\"For unselected projects, you will only receive notifications about things you watch or you're involved in (eg. issues you're the author or assignee).\""); ?></em></p>
  </div>

  <p><label><?php echo $this->Form->checkbox('UserPreference.pref.no_self_notified',array(
	  'value' => 1,
	  'checked' => (isset($this->request->data['UserPreference']['pref']['no_self_notified']) && $this->request->data['UserPreference']['pref']['no_self_notified']))) ?> <?php echo (__("\"I don't want to be notified of changes that I make myself\""))?></label></p>
</div>

<h3><?php echo __('Preferences') ?></h3>
<div class="box tabular">
<p><label><?php echo __('Hide my email address') ?></label><?php echo $this->Form->checkbox('UserPreference.hide_mail',array('value' => '1','checked' => $this->request->data['UserPreference']['hide_mail'])) ?></p>
<!-- <p><label><?php echo __('Time zone') ?></label></p> -->
<p><label><?php echo __('Display comments') ?></label><?php 
	echo $this->Form->select(
		'UserPreference.pref.comments_sorting',
		array(
			'asc' => __('In chronological order'),
			'desc' => __('In reverse chronological order')
		)) ?></p>
</div>
<?php echo "</div>" ?>
<?php echo $this->Form->end() ?>
<?php $this->set('Sidebar',$this->element('my/sidebar')) ?>

