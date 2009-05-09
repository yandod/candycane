<?php
  if(!isset($main_project)) $main_project = array();
  if(!isset($issue))        $issue = array();
?>
<div class="contextual">
  <?php
  if(!empty($main_project)) {
    echo $candy->link_to_if_authorized('button_log_time', __('Log time',true), $timelog->link_to_timelog_edit_url($main_project, $issue), array('class' => 'icon icon-time')); 
  }
  ?>
</div>

<?php echo $timelog->render_timelog_breadcrumb($main_project, $issue); ?>

<h2><?php __('Spent time') ?></h2>

<?php
echo $form->create('TimeEntry', array(
        'url'=>$timelog->link_to_timelog_detail_url($main_project),
        'onsubmit'=>$ajax->remoteFunction(array('url'=>$timelog->link_to_timelog_detail_url($main_project), 'form'=>true, 'after'=>'return false', 'update'=>'content')),
        )
      );
  echo $form->hidden('project_id', array('value'=>$this->params['project_id']));
  if(!empty($issue)) {
    echo $form->hidden('issue_id', array('value'=>$this->params['url']['issue_id']));
  }
  echo $this->renderElement('timelog/date_range', array('main_project'=>$main_project));
echo $form->end();
?>

<div class="total-hours">
<p><?php __('Total') ?>: <?php echo $candy->html_hours(sprintf(__('%.2f hour',true), $totalHours)); ?></p>
</div>

<?php if(!empty($entries)) : ?>
<?php echo $this->renderElement('timelog/list', array('entries' => $entries, 'main_project'=>$main_project, 'issue'=>$issue)); ?>
<p class="pagination"><?php echo $candy->pagination_links_full(); ?></p>
<p class="other-formats">
<?php __("'Also available in:'") ?>
<span>
<?php 
  if(!empty($this->params['url']['issue_id'])) {
    echo $html->link('Atom', array('?'=>array('issue_id' => $this->params['url']['issue_id'], 'format' => 'atom', 'key' => $rssToken)), array('class' => 'feed'));
  } else {
    echo $html->link('Atom', array('?'=>array('format' => 'atom', 'key' => $rssToken)), array('class' => 'feed'));
  }
?>
</span>
<span><?php echo $html->link('CSV', array('?'=>array_merge(array('format' => 'csv'), $this->params['url'])), array('class' => 'csv')); ?></span>
</p>
<?php endif; ?>

<?php
  if(empty($main_project)) {
    $candy->html_title(array(__('Spent time',true), __('Details',true)));
  } else {
    $candy->html_title(array($main_project['Project']['name'], __('Spent time',true), __('Details',true)));
  }
?>

<?php 
  if(!empty($this->params['url']['issue_id'])) {
    $html->meta('atom', array('project_id'=>$main_project['Project']['identifier'], '?'=>array('issue_id' => $this->params['url']['issue_id'], 'format'=>'atom', 'key'=>$rssToken)), array('title'=>__('Spent time',true), 'rel'=>'alternate'), false);
  } elseif(!empty($main_project)) {
    $html->meta('atom', array('project_id'=>$main_project['Project']['identifier'], '?'=>array('format'=>'atom', 'key'=>$rssToken)), array('title'=>__('Spent time',true), 'rel'=>'alternate'), false);
  } else {
    $html->meta('atom', array('?'=>array('format'=>'atom', 'key'=>$rssToken)), array('title'=>__('Spent time',true)), false);
  }
?>
