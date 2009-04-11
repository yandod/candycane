<h2><?php echo ($author==null) ? __('Activity') : $candy->lwr("\"%s's activity\"", $link_to_user($author)) ?></h2>
<p class="subtitle"><?php echo __('From', true), ' ', $time->niceShort($date_from), ' ', strtolower(__('To', true)), ' ', $time->niceShort($date_to) ?></p>

<div id="activity">
<?php foreach($events_by_day as $day=>$events): ?>
<h3><?php echo $time->format('Y-m-d', $day) ?></h3>
<dl>
<?php foreach($events as $event_time=>$event): ?>
<?php foreach($event as $e): ?>
  <dt class="<%= e.event_type %>  <%= User.current.logged? && e.respond_to?(:event_author) && User.current == e.event_author ? 'me' : nil %>">
<?php echo $candy->avatar($e['Author'], array('size'=>24)) ?>
<?php /*
	<%= avatar(e.event_author, :size => "24") if e.respond_to?(:event_author) %>
 */ ?>
  <span class="time"><?php echo $time->niceShort($event_time) ?></span>
<?php /*
  <%= content_tag('span', h(e.project), :class => 'project') if @project.nil? || @project != e.project %>
 */ ?>
<?php echo $html->link($e['Issue']['subject'], $e['Issue']['id']) ?></dt>
<dd><span class="description"><?php echo $e['Issue']['description'] ?></span>
<span class="author"><?php echo $candy->link_to_user($e['Author']) ?></span></dd>
<?php /*
  <%= link_to format_activity_title(e.event_title), e.event_url %></dt>
  <dd><span class="description"><%= format_activity_description(e.event_description) %></span>
  <span class="author"><%= e.event_author if e.respond_to?(:event_author) %></span></dd>
 */ ?>
<?php endforeach ?>
<?php endforeach ?>
</dl>
<?php endforeach ?>
</div>

<?php /*
<%= content_tag('p', l(:label_no_data), :class => 'nodata') if @events_by_day.empty? %>
 */ ?>

<div style="float:left;">
<?php /*
<%= link_to_remote(('&#171; ' + l(:label_previous)), 
                   {:update => "content", :url => params.merge(:from => @date_to - @days - 1), :complete => 'window.scrollTo(0,0)'},
                   {:href => url_for(params.merge(:from => @date_to - @days - 1)),
                    :title => "#{l(:label_date_from)} #{format_date(@date_to - 2*@days)} #{l(:label_date_to).downcase} #{format_date(@date_to - @days - 1)}"}) %>
 */ ?>
</div>
<div style="float:right;">
<?php /*
<%= link_to_remote((l(:label_next) + ' &#187;'), 
                   {:update => "content", :url => params.merge(:from => @date_to + @days - 1), :complete => 'window.scrollTo(0,0)'},
                   {:href => url_for(params.merge(:from => @date_to + @days - 1)),
                    :title => "#{l(:label_date_from)} #{format_date(@date_to)} #{l(:label_date_to).downcase} #{format_date(@date_to + @days - 1)}"}) unless @date_to >= Date.today %>
 */ ?>
</div>
&nbsp;
<p class="other-formats">
    <?php __("'Also available in:'") ?>
    <?php echo $html->link('Atom', array('action'=>'activity', 'format'=>'atom', 'from'=>null, 'key'=>isset($currentuser['User']) ? $currentuser['User']['rss_key'] : ''
    ), array('class' => 'feed')) ?>
</p>

<?php $this->set('Sidebar', $this->renderElement('projects/sidebar/activity')) ?>
<?php $this->set('header_tags', $this->renderElement('projects/rss')) ?>

<?php $candy->html_title(__('Activity', true), $author['Project']['name']) ?>
