<div class="contextual">
<?php if ($currentuser['admin'] == 1): ?>
  <?php e($html->link(__('Edit', true), '/users/edit/'.$user['User']['id'], array('class' => 'icon icon-edit'))); ?>
<?php endif; ?>
</div>

<h2><?php echo $candy->avatar($user,array('size' => 72)); ?> <?php e(h($candy->format_username($user['User']))); ?></h2>

<div class="splitcontentleft">
<ul>
  <?php if (!$user['UserPreference']['hide_mail']): ?>
  <li><?php __('Email'); ?>: <?php echo $text->autoLinkEmails(h($user['User']['mail']));?></li>
  <?php endif; ?>
<?php /*
      
  <% for custom_value in @custom_values %>
    <% if !custom_value.value.empty? %>
      <li><%= custom_value.custom_field.name%>: <%=h show_value(custom_value) %></li>
    <% end %>
  <% end %>
*/
?>
  <li><?php __('Registered on'); ?>: <?php e($candy->format_date($user['User']['created_on'])); ?></li>

  <?php if (!empty($user['last_login_on'])): ?>
  <li><?php __('Last connection'); ?>: <?php e($candy->format_date($user['User']['last_login_on'])); ?></li>
  <?php endif; ?>
</ul>
<?php if( !empty($user['Membership']) ): ?>
  <h3><?php __('Projects'); ?></h3>
  <ul>
  <?php foreach($user['Membership'] as $row): ?>
    <li><?php echo $html->link($row['Project']['name'],aa('controller','projects','action','show','project_id',$row['Project']['identifier'])); ?>
    (<?php echo h($row['Role']['name']) ?>, <?php echo $candy->format_date($row['created_on']) ?>)</li>
  <?php endforeach; ?>
  </ul>
<?php endif; ?>
</div>

<div class="splitcontentright">

<?php if ( !empty($events_by_day_data) ): ?>
  <h3><?php echo $html->link(__('Activity',true),aa('controller','projects','action','activity','?',aa('user_id',$user['User']['id'],'from',array_shift(array_keys($events_by_day_data))))) ?></h3>

<p>
<?php __('Reported issues'); ?>:
<?php echo $issue_count ?>
</p>

<div id="activity">
  <?php foreach(array_reverse($events_by_day_data) as $day => $row): ?>
  <h4><?php echo $candy->format_activity_day($day) ?></h4>
  <dl>
<?php
#    <% @events_by_day[day].sort {|x,y| y.event_datetime <=> x.event_datetime }.each do |e| -%>?><dd>
  <?php foreach($row as $event): ?>
    <dt class="<?php echo $event['type'] ?>">
    <span class="time"><?php echo $candy->format_time($event['datetime'], false) ?></span>
    <?php echo $html->tag('span', h($event['project']['name']), array('class' => 'project')); ?>
    <?php echo $html->link($candy->format_activity_title($event['title']), $event['url']) ?>
    <dd><span class="description"><?php echo $candy->format_activity_title($event['description']) ?></span></dd>
    <?php endforeach; ?>
  </dl>
  <?php endforeach; ?>
</div>

<p class="other-formats">
  <?php __("Also available in:"); ?>
  <?php echo $html->link('Atom', array('controller'=>'projects','action','activity','user_id'=>$user['User']['id'], '?'=>array_merge($this->params['url'], array('key'=>$currentuser['RssToken']['value'], 'format'=>'atom', 'from'=>null, 'url'=>null))), array('class' => 'feed')); ?>
</p>

<?php endif; ?>
</div>

<?php $candy->html_title($candy->format_username($user['User']), true); ?>
