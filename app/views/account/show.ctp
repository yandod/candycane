<div class="contextual">
<?php if ($currentuser['admin'] == 1): ?>
  <?php e($html->link(__('Edit', true), '/users/edit/'.$currentuser['id'], array('class' => 'icon icon-edit'))); ?>
<?php endif; ?>
</div>

<h2><?php echo $candy->avatar($user); ?> <?php e(h($currentuser['name'])); ?></h2>

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
  <li><?php __('Registered on'); ?>: <?php e($candy->format_date($currentuser['created_on'])); ?></li>

  <?php if (!empty($currentuser['last_login_on'])): ?>
  <li><?php __('Last connection'); ?>: <?php e($candy->format_date($currentuser['last_login_on'])); ?></li>
  <?php endif; ?>
</ul>

<?php if( !empty($user['Membership']) ): ?>
  <h3><?php __('Projects'); ?></h3>
  <ul>
  <?php foreach($user['Membership'] as $row): ?>
    <li><%= link_to(h(membership.project.name), :controller => 'projects', :action => 'show', :id => membership.project) %>
    (<%=h membership.role.name %>, <%= format_date(membership.created_on) %>)</li>
  <?php endforeach; ?>
  </ul>
<?php endif; ?>
</div>

<div class="splitcontentright">

<% unless @events_by_day.empty? %>
  <h3><%= link_to l(:label_activity), :controller => 'projects', :action => 'activity', :user_id => @user, :from => @events_by_day.keys.first %></h3>

<p>
<?php __('label_reported_issues'); ?>:
<%= Issue.count(:conditions => ["author_id=?", @user.id]) %>
</p>

<div id="activity">
  <% @events_by_day.keys.sort.reverse.each do |day| %>
  <h4><%= format_activity_day(day) %></h4>
  <dl>
    <% @events_by_day[day].sort {|x,y| y.event_datetime <=> x.event_datetime }.each do |e| -%>
    <dt class="<%= e.event_type %>">
    <span class="time"><%= format_time(e.event_datetime, false) %></span>
    <%= content_tag('span', h(e.project), :class => 'project') %>
    <%= link_to format_activity_title(e.event_title), e.event_url %></dt>
    <dd><span class="description"><%= format_activity_description(e.event_description) %></span></dd>
    <% end -%>
  </dl>
  <% end -%>
</div>

<p class="other-formats">
  <?php __('label_export_to'); ?>
  <%= link_to 'Atom', {:controller => 'projects', :action => 'activity', :user_id => @user, :format => :atom, :key => User.current.rss_key}, :class => 'feed' %>
</p>

<% content_for :header_tags do %>
  <%= auto_discovery_link_tag(:atom, :controller => 'projects', :action => 'activity', :user_id => @user, :format => :atom, :key => User.current.rss_key) %>
<% end %>
<% end %>
</div>

<?php $candy->html_title($currentuser['name'], true); ?>
