<h2><?php echo ($author==null) ? __('Activity') : $candy->lwr("\"%s's activity\"", $link_to_user($author)) ?></h2>
<p class="subtitle"><?php echo __('From', true), ' ', $candy->format_date($time->niceShort($date_to - $days)), ' ', strtolower(__('To', true)), ' ', $candy->format_date($time->niceShort($date_to - 1)) ?></p>

<div id="activity">
<% @events_by_day.keys.sort.reverse.each do |day| %>
<h3><%= format_activity_day(day) %></h3>
<dl>
<% @events_by_day[day].sort {|x,y| y.event_datetime <=> x.event_datetime }.each do |e| -%>
  <dt class="<%= e.event_type %>  <%= User.current.logged? && e.respond_to?(:event_author) && User.current == e.event_author ? 'me' : nil %>">
	<%= avatar(e.event_author, :size => "24") if e.respond_to?(:event_author) %>
  <span class="time"><%= format_time(e.event_datetime, false) %></span>
  <%= content_tag('span', h(e.project), :class => 'project') if @project.nil? || @project != e.project %>
  <%= link_to format_activity_title(e.event_title), e.event_url %></dt>
  <dd><span class="description"><%= format_activity_description(e.event_description) %></span>
  <span class="author"><%= e.event_author if e.respond_to?(:event_author) %></span></dd>
<% end -%>
</dl>
<% end -%>
</div>

<%= content_tag('p', l(:label_no_data), :class => 'nodata') if @events_by_day.empty? %>

<div style="float:left;">
<%= link_to_remote(('&#171; ' + l(:label_previous)), 
                   {:update => "content", :url => params.merge(:from => @date_to - @days - 1), :complete => 'window.scrollTo(0,0)'},
                   {:href => url_for(params.merge(:from => @date_to - @days - 1)),
                    :title => "#{l(:label_date_from)} #{format_date(@date_to - 2*@days)} #{l(:label_date_to).downcase} #{format_date(@date_to - @days - 1)}"}) %>
</div>
<div style="float:right;">
<%= link_to_remote((l(:label_next) + ' &#187;'), 
                   {:update => "content", :url => params.merge(:from => @date_to + @days - 1), :complete => 'window.scrollTo(0,0)'},
                   {:href => url_for(params.merge(:from => @date_to + @days - 1)),
                    :title => "#{l(:label_date_from)} #{format_date(@date_to)} #{l(:label_date_to).downcase} #{format_date(@date_to + @days - 1)}"}) unless @date_to >= Date.today %>
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
