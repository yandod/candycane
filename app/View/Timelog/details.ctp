<?php
  if(!isset($main_project)) $main_project = array();
  if(!isset($issue))        $issue = array();
?>
<div class="contextual">
  <?php
  if(!empty($main_project)) {
    echo $this->Candy->link_to_if_authorized(null, __('Log time'), $this->Timelog->link_to_timelog_edit_url($main_project, $issue), array('class' => 'icon icon-time')); 
  }
  ?>
</div>

<?php echo $this->Timelog->render_timelog_breadcrumb($main_project, $issue); ?>

<h2><?php echo __('Spent time') ?></h2>

<?php
echo $this->Form->create('TimeEntry', array(
        'url'=>$this->Timelog->link_to_timelog_detail_url($main_project),
//        'onsubmit'=>$ajax->remoteFunction(
//			array(
//				'url' => $this->Timelog->link_to_timelog_detail_url($main_project),
//				'form' => true,
//				'after' => 'return false',
//				'update' => 'content'
//			)
//		),
	)
);
  if(!empty($this->request->params['project_id'])) {
    echo $this->Form->hidden('project_id', array('value'=>$this->request->params['project_id']));
  }
  if(!empty($issue)) {
    echo $this->Form->hidden('issue_id', array('value'=>$this->request->query['issue_id']));
  }
  echo $this->element('timelog/date_range', array('main_project'=>$main_project));
echo $this->Form->end();
?>

<div class="total-hours">
<p><?php echo __('Total') ?>: <?php echo $this->Candy->html_hours(sprintf(__('%.2f hour'), $total_hours)); ?></p>
</div>

<?php if(!empty($entries)) : ?>
<?php echo $this->element('timelog/list', array('entries' => $entries, 'main_project'=>$main_project, 'issue'=>$issue)); ?>
<p class="pagination"><?php echo $this->Candy->pagination_links_full(); ?></p>
<p class="other-formats">
<?php echo __("'Also available in:'") ?>
<span>
<?php 
  if(!empty($this->request->query['issue_id'])) {
    echo $this->Html->link('Atom', array('?'=>array('issue_id' => $this->request->query['issue_id'], 'format' => 'atom', 'key' => $rss_token)), array('class' => 'feed'));
  } else {
    echo $this->Html->link('Atom', array('?'=>array('format' => 'atom', 'key' => $rss_token)), array('class' => 'feed'));
  }
?>
</span>
<span><?php echo $this->Html->link('CSV', array('?'=>array_merge(array('format' => 'csv'), $this->request->query)), array('class' => 'csv')); ?></span>
</p>
<?php endif; ?>

<?php
  if(empty($main_project)) {
    $this->Candy->html_title(array(__('Spent time'), __('Details')));
  } else {
    $this->Candy->html_title(array($main_project['Project']['name'], __('Spent time'), __('Details')));
  }
?>

<?php 
  if(!empty($this->request->query['issue_id'])) {
    $this->Html->meta('atom', array('project_id'=>$main_project['Project']['identifier'], '?'=>array('issue_id' => $this->request->query['issue_id'], 'format'=>'atom', 'key'=>$rss_token)), array('title'=>__('Spent time'), 'rel'=>'alternate'), false);
  } elseif(!empty($main_project)) {
    $this->Html->meta('atom', array('project_id'=>$main_project['Project']['identifier'], '?'=>array('format'=>'atom', 'key'=>$rss_token)), array('title'=>__('Spent time'), 'rel'=>'alternate'), false);
  } else {
    $this->Html->meta('atom', array('?'=>array('format'=>'atom', 'key'=>$rss_token)), array('title'=>__('Spent time')), false);
  }
?>
