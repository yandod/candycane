<div class="contextual">
<?php if ( empty($currentuser['auth_source_id'])) echo $html->link(__('Change password',true),aa('action','password')) ?>
</div>

<h2><?php echo $candy->html_title(__('My account',true)) ?></h2>
<?php echo $this->renderElement('error_explanation'); ?>


<?php e($form->create('User',array('url'=>aa('controller','my','action','account')))); ?>
<div class="splitcontentleft">
  <h3><?php e(__('Information')); ?></h3>
  <div class="box tabular">
    <p>
      <label for="UserFirstname"><?php __('Firstname') ?> <span class="required">*</span></label>
      <?php e($form->input('firstname',aa('div',false,'label',false,'size',30,'error',false))); ?>
    </p>
    <p>
      <label for="UserLastname"><?php __('Lastname') ?> <span class="required">*</span></label>
      <?php e($form->input('lastname',aa('div',false,'label',false,'size',30,'error',false))); ?>
    </p>
    <p>
      <label for="UserEmail"><?php __('Email') ?> <span class="required">*</span></label>
      <?php e($form->input('mail',aa('div',false,'label',false,'size',30,'error',false))); ?>
    </p>
    <p>
      <label for="UserLanguage"><?php __('Language'); ?></label>
      <?php echo $form->select('language', $candy->lang_options_for_select(),$currentuser['language'],array('type' => 'select', 'error' => false),false); ?>
    </p>
  </div>

  <?php e($form->submit(__('Save',true))); ?>
</div>

<div class="splitcontentright">
  <h3><?php __('Email notifications') ?></h3>
  <div class="box">
  <?php echo $form->select('notification_option',$notification_options,$notification_option,aa('onchange','if ($("UserNotificationOption").value == "selected") {Element.show("notified-projects")} else {Element.hide("notified-projects")}'),false) ?>
  <!-- <% content_tag 'div', :id => 'notified-projects', :style => (@notification_option == 'selected' ? '' : 'display:none;') do %>-->
  <?php $opt = aa('id','notified-projects','style', $notification_option == 'selected' ? '' : 'display:none'); ?>
  <?php echo $html->tag('div',null,$opt) ?>
  <p>
    <?php foreach($currentuser['memberships'] as $row ): ?>
    <?php
      $opt = aa('value',$row['project_id'],'label',false,'hidden',false);
      if (in_array($row['project_id'],$this->data['User']['notified_project_ids'])) $opt['checked'] = 'checked';
    ?>
    <label><?php echo $form->checkbox('notified_project_ids][',$opt) ?> <?php echo h($row['Project']['name']) ?></label><br />
    <?php endforeach; ?>
  </p>

  <p><em><?php __("\"For unselected projects, you will only receive notifications about things you watch or you're involved in (eg. issues you're the author or assignee).\""); ?></em></p>
  </div>

  <p><label><?php echo $form->checkbox('UserPreference.no_self_notified',aa('value',1)) ?> <?php echo (__("\"I don't want to be notified of changes that I make myself\"",true))?></label></p>
</div>

<h3><?php __('Preferences') ?></h3>
<div class="box tabular">
<p><label><?php __('Hide my email address') ?></label><?php echo $form->checkbox('UserPreference.hide_mail',aa('value','1','checked',$this->data['UserPreference']['hide_mail'])) ?></p>
<!-- <p><label><?php __('Time zone') ?></label></p> -->
<p><label><?php __('Display comments') ?></label><?php echo $form->select('UserPreference.comments_sorting',aa('asc',__('In chronological order',true),'desc',__('In reverse chronological order',true)),null,null,false) ?></p>
</div>
<?php echo "</div>" ?>
<?php echo $form->end() ?>
<?php $this->set('Sidebar',$this->renderElement('my/sidebar')) ?>

