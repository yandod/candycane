<h2><?php echo ($author==null) ? __('Activity') : $this->Candy->lwr("\"%s's activity\"", $link_to_user($author)) ?></h2>
<p class="subtitle"><?php echo __('From'), ': ', $this->Candy->format_date($date_from), ' ', strtolower(__('To')), ' ', $this->Candy->format_date($date_to) ?></p>

<div id="activity">
<?php foreach($events_by_day as $day=>$events): ?>
<h3><?php echo $this->Candy->format_activity_day($day) ?></h3>
<dl>
<?php foreach($events as $event): ?>
  <dt class="<?php echo $event['type']; ?>  <?php echo ($currentuser['logged'] && !empty($event['author']) && $currentuser['id'] == $event['author']['id']) ? 'me' : ''; ?>">
<?php if(!empty($event['author'])) { echo $this->Candy->avatar($event['author'], array('size'=>24)); } ?>
<?php /*
	<%= avatar(e.event_author, :size => "24") if e.respond_to?(:event_author) %>
 */ ?>
  <span class="time"><?php echo $this->Candy->format_time($event['datetime'], false) ?></span>
<?php if(empty($main_project) || (!empty($main_project) && $main_project['Project']['id'] != $event['project']['id'])) {
  echo $this->Html->tag('span', h($event['project']['name']), array('class' => 'project'));
} ?>
<?php echo $this->Html->link($this->Candy->format_activity_title($event['title']), $event['url']) ?></dt>
<dd><span class="description"><?php echo $this->Candy->format_activity_title($event['description']) ?></span>
<span class="author"><?php if(!empty($event['author'])) { echo $this->Candy->link_to_user($event['author']); } ?></span></dd>
<?php endforeach ?>
</dl>
<?php endforeach ?>
</div>

<?php if(empty($events_by_day)) { echo $this->Html->tag('p', __('No data to display'), array('class' => 'nodata')); } ?>

<div style="float:left;">
<?php 
	$prev_days = 2*$days-1;
	$get_params = array_merge(
		$this->request->query,
		$this->request->named
	);
  unset($get_params['url']);
  echo $this->AppAjax->link(('&#171; '.__('Previous')),
                    array('project_id'=>$this->request->params['project_id'], '?'=>array_merge($get_params, array('from' => date('Y-m-d', strtotime("-{$days} day", $date_to) - 1)))),
                    array(
                      'update' => "content", 
                      'complete' => 'window.scrollTo(0,0)', 
                      'title' => __('From').' '.$this->Candy->format_date(strtotime("-{$prev_days} day", $date_to)).' '.strtolower(__('To')).' '.$this->Candy->format_date(strtotime("-{$days} day", $date_to) - 1),
					  'escape' => false
                    ),
                    null, false);
?>
</div>
<div style="float:right;">
<?php 
  if(date("Ymd") > date("Ymd",$date_to)) {
    echo $this->AppAjax->link((__('Next').' &#187;'),
                    array('project_id'=>$this->request->params['project_id'], '?'=>array_merge($get_params, array('from' => date('Y-m-d', strtotime("$days day", $date_to) - 1)))),
                    array(
                      'update' => "content", 
                      'complete' => 'window.scrollTo(0,0)', 
                      'title' => __('From').' '.$this->Candy->format_date($date_to).' '.strtolower(__('To')).' '.$this->Candy->format_date(strtotime("$days day", $date_to) - 1),
					  'escape' => false
                    ),
                    null, false);
  }
?>
</div>
&nbsp;
<p class="other-formats">
    <?php echo __("Also available in:"); ?>
    <?php echo $this->Html->link(
		'Atom',
		array(
			'project_id' => $this->request->params['project_id'],
			'?' => array_merge(
				$this->request->query,
				array(
					'key' => $rss_token,
					'format' => 'atom',
					'from' => null,
					'url' => null
				)
			)
		),
		array(
			'class' => 'feed',
		)
	); ?>
</p>
<?php $this->element('projects/rss'); ?>

<?php $this->set('Sidebar', $this->element('projects/sidebar/activity')); ?>

<?php $this->Candy->html_title(__('Activity'), $author['Project']['name']) ?>
