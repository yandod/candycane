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
<?php 
  $prev_days = 2*$days-1;
  $get_params = array_merge($this->params['url'], $this->params['named']);
  unset($get_params['url']);
  echo $appAjax->link(('&#171; '.__('Previous',true)),
                    array('project_id'=>$this->params['project_id'], '?'=>array_merge($get_params, array('from' => date('Y-m-d', strtotime("-{$days} day", $date_to) - 1)))),
                    array(
                      'update' => "content", 
                      'complete' => 'window.scrollTo(0,0)', 
                      'title' => __('From',true).' '.$candy->format_date(strtotime("-{$prev_days} day", $date_to)).' '.strtolower(__('To',true)).' '.$candy->format_date(strtotime("-{$days} day", $date_to) - 1)
                    ),
                    null, false);
?>
</div>
<div style="float:right;">
<?php 
  if(date("Ymd") > date("Ymd",$date_to)) {
    echo $appAjax->link((__('Next',true).' &#187;'),
                    array('project_id'=>$this->params['project_id'], '?'=>array_merge($get_params, array('from' => date('Y-m-d', strtotime("$days day", $date_to) - 1)))),
                    array(
                      'update' => "content", 
                      'complete' => 'window.scrollTo(0,0)', 
                      'title' => __('From',true).' '.$candy->format_date($date_to).' '.strtolower(__('To',true)).' '.$candy->format_date(strtotime("$days day", $date_to) - 1)
                    ),
                    null, false);
  }
?>
</div>
&nbsp;
<p class="other-formats">
    <?php __("'Also available in:'"); ?>
    <?php echo $html->link('Atom', array('project_id'=>$this->params['project_id'], '?'=>array_merge($this->params['url'], array('key'=>$rss_token, 'format'=>'atom', 'from'=>null, 'url'=>null))), array('class' => 'feed')); ?>
</p>
<?php $this->renderElement('projects/rss'); ?>

<?php $this->set('Sidebar', $this->renderElement('projects/sidebar/activity')); ?>

<?php $candy->html_title(__('Activity', true), $author['Project']['name']) ?>
