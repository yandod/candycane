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
        'url'=>$timelog->link_to_timelog_report_url($main_project),
        'onsubmit'=>$ajax->remoteFunction(array('url'=>$timelog->link_to_timelog_report_url($main_project), 'form'=>true, 'after'=>'return false', 'update'=>'content')),
        )
      );
  foreach($criterias as $criteria) {
    echo $form->hidden('criterias', array('value'=>$criteria, 'id' => null, 'name'=>'data[TimeEntry][criterias][]'));
  }
  echo $form->hidden('project_id', array('value'=>$this->params['project_id']));
  echo $this->renderElement('timelog/date_range', array('main_project'=>$main_project));
?>
  <p><?php __('Details') ?>: <?php echo $form->input('columns', array('type'=>'select', 'div'=>false, 'label'=>false,
                                                        'options' => array('year' =>__('Year',true),
                                                                           'month'=>__('Month',true),
                                                                           'week' =>__('Week',true),
                                                                           'day'  =>__('days',true)),
                                                        'value'   => $columns,
                                                        'onchange' => "this.form.onsubmit();")); ?>

  <?php __('Add') ?>: <?php echo $form->input('criterias', array('type'=>'select', 'div'=>false, 'label'=>false,
                                                        'options' => $timelog->selectable_criterias($availableCriterias, $criterias),
                                                        'empty' => true,
                                                        'onchange' => "this.form.onsubmit();",
                                                        'style' => 'width: 200px',
                                                        'id' => null,
                                                        'name'=>'data[TimeEntry][criterias][]',
                                                        'disabled' => (count($criterias) >= 3))); ?>
     <?php echo $timelog->clear_link($main_project, $columns); ?>
  </p>
<?php echo $form->end(); ?>

<?php if(!empty($criterias)): ?>
<div class="total-hours">
<p><?php __('Total') ?>: <?php echo $candy->html_hours(sprintf(__("%.2f hour",true), $totalHours)); ?></p>
</div>

<?php if(!empty($hours)): ?>
<table class="list" id="time-report">
<thead>
<tr>
<?php foreach($criterias as $criteria): ?>
  <th><?php echo __($availableCriterias[$criteria]['label'], true) ?></th>
<?php endforeach; ?>
<?php $columns_width = (40 / (count($periods)+1)); ?>
<?php foreach($periods as $period): ?>
  <th class="period" width="<?php echo $columns_width; ?>%"><?php echo $period; ?></th>
<?php endforeach; ?>
  <th class="total" width="<?php echo $columns_width; ?>%"><?php __('Total') ?></th>
</tr>
</thead>
<tbody>
<?php echo $this->renderElement('timelog/report_criteria', array(
    'criterias' => $criterias, 
    'hours'=>$hours, 
    'level'=>0, 
    'availableCriterias'=>$availableCriterias,
    'columns'=>$columns,
    'periods'=>$periods,
    )); ?>
  <tr class="total">
  <td><?php __('Total') ?></td>
  <?php echo $timelog->empty_td(count($criterias) - 1); ?>
  <?php $total = 0; ?>
<?php
//e(pr($periods));
?>
  <?php foreach($periods as $period): ?>
    <?php $sum = $timelog->sum_hours($timelog->select_hours($hours, $columns, $period)); $total += $sum; ?>
    <td class="hours"><?php if($sum > 0) { echo $candy->html_hours(sprintf(__("%.2f",true), $sum)); } ?></td>
  <?php endforeach; ?>
  <td class="hours"><?php if($total > 0) { echo $candy->html_hours(sprintf(__("%.2f",true), $total)); } ?></td>
  </tr>
</tbody>
</table>

<p class="other-formats">
<?php __("'Also available in:'") ?>
<span><?php 
echo $html->link('CSV', array('?'=>array_merge(
    array(
      'format' => 'csv',
      'criterias'=>$criteria,
      'columns'=>$columns
    ), 
    $this->params['url']
  )), array('class' => 'csv')); 
?></span>
</p>
<?php endif; ?>
<?php endif; ?>

<?php $candy->html_title(array(__('Spent time',true), __('Report',true))); ?>

