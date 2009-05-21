<h2><?php echo ($author==null) ? __('Activity') : $candy->lwr("\"%s's activity\"", $link_to_user($author)) ?></h2>
<p class="subtitle"><?php echo __('From', true), ': ', $candy->format_date($date_from), ' ', strtolower(__('To', true)), ' ', $candy->format_date($date_to) ?></p>

<div id="activity">
<?php foreach($events_by_day as $day=>$events): ?>
<h3><?php echo $candy->format_activity_day($day) ?></h3>
<dl>
<?php foreach($events as $event): ?>
  <dt class="<?php echo $event['type']; ?>  <?php echo ($currentuser['logged'] && !empty($event['author']) && $currentuser['id'] == $event['author']['id']) ? 'me' : ''; ?>">
<?php if(!empty($event['author'])) { echo $candy->avatar($event['author'], array('size'=>24)); } ?>
<?php /*
	<%= avatar(e.event_author, :size => "24") if e.respond_to?(:event_author) %>
 */ ?>
  <span class="time"><?php echo $candy->format_time($event['datetime'], false) ?></span>
<?php if(empty($main_project) || (!empty($main_project) && $main_project['Project']['id'] != $event['project']['id'])) {
  echo $html->tag('span', h($event['project']['name']), array('class' => 'project'));
} ?>
<?php echo $html->link($candy->format_activity_title($event['title']), $event['url']) ?></dt>
<dd><span class="description"><?php echo $candy->format_activity_title($event['description']) ?></span>
<span class="author"><?php if(!empty($event['author'])) { echo $candy->link_to_user($event['author']); } ?></span></dd>
<?php endforeach ?>
</dl>
<?php endforeach ?>
</div>

<?php if(empty($events_by_day)) { echo $html->tag('p', __('No data to display',true), array('class' => 'nodata')); } ?>

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
