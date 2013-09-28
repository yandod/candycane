<div class="contextual">
<?php if ($currentuser['admin'] == 1): ?>
  <?php echo $this->Html->link(__('Edit'), '/users/edit/'.$user['User']['id'], array('class' => 'icon icon-edit')); ?>
<?php endif; ?>
</div>

<h2><?php echo $this->Candy->avatar($user,array('size' => 72)); ?> <?php echo h($this->Candy->format_username($user['User'])); ?></h2>

<div class="splitcontentleft">
<ul>
  <?php if (!$user['UserPreference']['hide_mail']): ?>
  <li><?php echo __('Email'); ?>: <?php echo $this->Text->autoLinkEmails(h($user['User']['mail']));?></li>
  <?php endif; ?>
<?php /*
      
  <% for custom_value in @custom_values %>
    <% if !custom_value.value.empty? %>
      <li><%= custom_value.custom_field.name%>: <%=h show_value(custom_value) %></li>
    <% end %>
  <% end %>
*/
?>
  <li><?php echo __('Registered on'); ?>: <?php echo $this->Candy->format_date($user['User']['created_on']); ?></li>

  <?php if (!empty($user['last_login_on'])): ?>
  <li><?php echo __('Last connection'); ?>: <?php echo $this->Candy->format_date($user['User']['last_login_on']); ?></li>
  <?php endif; ?>
</ul>
<?php if( !empty($user['Membership']) ): ?>
  <h3><?php echo __('Projects'); ?></h3>
  <ul>
  <?php foreach($user['Membership'] as $row): ?>
    <li><?php echo $this->Html->link($row['Project']['name'],array(
		'controller' => 'projects',
		'action' => 'show',
		'project_id' => $row['Project']['identifier']
	)); ?>
    (<?php echo h($row['Role']['name']) ?>, <?php echo $this->Candy->format_date($row['created_on']) ?>)</li>
  <?php endforeach; ?>
  </ul>
<?php endif; ?>
</div>

<div class="splitcontentright">

<?php if ( !empty($events_by_day_data) ): ?>
  <h3><?php echo $this->Html->link(__('Activity'),array(
	  'controller' => 'projects',
	  'action' => 'activity',
	  '?' => array(
		  'user_id' => $user['User']['id'],
		  'from' => array_shift(array_keys($events_by_day_data))
		)
	)) ?></h3>

<p>
<?php echo __('Reported issues'); ?>:
<?php echo $issue_count ?>
</p>

<div id="activity">
  <?php foreach(array_reverse($events_by_day_data) as $day => $row): ?>
  <h4><?php echo $this->Candy->format_activity_day($day) ?></h4>
  <dl>
<?php
#    <% @events_by_day[day].sort {|x,y| y.event_datetime <=> x.event_datetime }.each do |e| -%>?><dd>
  <?php foreach($row as $event): ?>
    <dt class="<?php echo $event['type'] ?>">
    <span class="time"><?php echo $this->Candy->format_time($event['datetime'], false) ?></span>
    <?php echo $this->Html->tag('span', h($event['project']['name']), array('class' => 'project')); ?>
    <?php echo $this->Html->link($this->Candy->format_activity_title($event['title']), $event['url']) ?>
    <dd><span class="description"><?php echo $this->Candy->format_activity_title($event['description']) ?></span></dd>
    <?php endforeach; ?>
  </dl>
  <?php endforeach; ?>
</div>

<p class="other-formats">
  <?php echo __("Also available in:"); ?>
  <?php if (isset($currentuser['RssToken'])): ?>
  <?php echo $this->Html->link('Atom', array('controller'=>'projects','action','activity','user_id'=>$user['User']['id'], '?'=>array_merge($this->request->query, array('key'=>$currentuser['RssToken']['value'], 'format'=>'atom', 'from'=>null))), array('class' => 'feed')); ?>
  <?php endif; ?>
</p>

<?php endif; ?>
</div>

<?php $this->Candy->html_title($this->Candy->format_username($user['User']), true); ?>
