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
        'url'=>$this->Timelog->link_to_timelog_report_url($main_project),
        )
      );
  foreach($criterias as $criteria) {
    echo $this->Form->hidden('criterias', array('value'=>$criteria, 'id' => null, 'name'=>'data[TimeEntry][criterias][]'));
  }
  if(!empty($this->request->params['project_id'])) {
    echo $this->Form->hidden('project_id', array('value'=>$this->request->params['project_id']));
  }
  echo $this->element('timelog/date_range', array('main_project'=>$main_project));
?>
  <p><?php echo __('Details') ?>: <?php echo $this->Form->input('columns', array('type'=>'select', 'div'=>false, 'label'=>false,
                                                        'options' => array('year' =>__('Year'),
                                                                           'month'=>__('Month'),
                                                                           'week' =>__('Week'),
                                                                           'day'  =>__('days')),
                                                        'value'   => $columns,
                                                        )); ?>
	<?php 
		echo $this->Html->scriptBlock(
			$this->Js->get('#TimeEntryColumns')->event('change', 
				$this->Js->request($this->Timelog->link_to_timelog_report_url($main_project), 
				array(
					'update' => 'content',
					'evalScripts' => true,
					'data' => $this->Js->serializeForm(array(
						'inline' => true
					)),
					'dataExpression' => true
	
				)), 
				array('buffer' => false)));
	?>

  <?php echo __('Add') ?>: <?php echo $this->Form->input('criterias', array('type'=>'select', 'div'=>false, 'label'=>false,
                                                        'options' => $this->Timelog->selectable_criterias($available_criterias, $criterias),
                                                        'empty' => true,
                                                        'style' => 'width: 200px',
                                                        //'id' => null,
                                                        'name'=>'data[TimeEntry][criterias][]',
                                                        'disabled' => (count($criterias) >= 3))); ?>
	<?php 
		echo $this->Html->scriptBlock(
			$this->Js->get('#TimeEntryCriterias')->event('change', 
				$this->Js->request($this->Timelog->link_to_timelog_report_url($main_project), 
				array(
					'update' => 'content',
					'evalScripts' => true,
					'data' => $this->Js->serializeForm(array(
						'inline' => true
					)),
					'dataExpression' => true
				)), 
				array('buffer' => false)));
	?>

     <?php echo $this->Timelog->clear_link($main_project, $columns); ?>
  </p>
<?php echo $this->Form->end(); ?>

<?php if(!empty($criterias)): ?>
<div class="total-hours">
<p><?php echo __('Total') ?>: <?php echo $this->Candy->html_hours(sprintf(__("%.2f hour"), $total_hours)); ?></p>
</div>

<?php if(!empty($hours)): ?>
<table class="list" id="time-report">
<thead>
<tr>
<?php foreach($criterias as $criteria): ?>
  <th><?php echo __($available_criterias[$criteria]['label']) ?></th>
<?php endforeach; ?>
<?php $columns_width = (40 / (count($periods)+1)); ?>
<?php foreach($periods as $period): ?>
  <th class="period" width="<?php echo $columns_width; ?>%"><?php echo $period; ?></th>
<?php endforeach; ?>
  <th class="total" width="<?php echo $columns_width; ?>%"><?php echo __('Total') ?></th>
</tr>
</thead>
<tbody>
<?php echo $this->element('timelog/report_criteria', array(
    'criterias' => $criterias, 
    'hours'=>$hours, 
    'level'=>0, 
    'availableCriterias'=>$available_criterias,
    'columns'=>$columns,
    'periods'=>$periods,
    )); ?>
  <tr class="total">
  <td><?php echo __('Total') ?></td>
  <?php echo $this->Timelog->empty_td(count($criterias) - 1); ?>
  <?php $total = 0; ?>
<?php
//echo pr($periods);
?>
  <?php foreach($periods as $period): ?>
    <?php $sum = $this->Timelog->sum_hours($this->Timelog->select_hours($hours, $columns, $period)); $total += $sum; ?>
    <td class="hours"><?php if($sum > 0) { echo $this->Candy->html_hours(sprintf(__("%.2f"), $sum)); } ?></td>
  <?php endforeach; ?>
  <td class="hours"><?php if($total > 0) { echo $this->Candy->html_hours(sprintf(__("%.2f"), $total)); } ?></td>
  </tr>
</tbody>
</table>

<p class="other-formats">
<?php echo __("'Also available in:'") ?>
<span><?php 
echo $this->Html->link('CSV', array('?'=>array_merge(
    array(
      'format' => 'csv',
      'criterias'=>$criteria,
      'columns'=>$columns
    ), 
    $this->request->query
  )), array('class' => 'csv')); 
?></span>
</p>
<?php endif; ?>
<?php endif; ?>

<?php $this->Candy->html_title(array(__('Spent time'), __('Report'))); ?>

