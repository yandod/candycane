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
