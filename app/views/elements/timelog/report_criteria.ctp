<?php 
$values = $timelog->criteria_values($criterias, $hours, $level);
foreach($values as $value):
?>
<?php $hours_for_value = $timelog->select_hours($hours, $criterias[$level], $value); ?>
<?php if(empty($hours_for_value)) { continue; } ?>
<tr class="<?php echo $candy->cycle(); ?> <?php if(!(count($criterias) > $level+1)) { echo 'last-level'; }  ?>">
<?php echo $timelog->empty_td($level); ?>
<td><?php echo h($timelog->format_criteria_value($availableCriterias, $criterias[$level], $value)); ?></td>
<?php echo $timelog->empty_td(count($criterias) - $level - 1); ?>
  <?php $total = 0; ?>
  <?php foreach($periods as $period): ?>
    <?php $sum = $timelog->sum_hours($timelog->select_hours($hours_for_value, $columns, $period)); $total += $sum; ?>
    <td class="hours"><?php if($sum > 0) { echo $candy->html_hours(sprintf(__("%.2f",true), $sum)); } ?></td>
  <?php endforeach; ?>
  <td class="hours"><?php if($total > 0) { echo $candy->html_hours(sprintf(__("%.2f",true), $total)); } ?></td>
</tr>
<?php if(count($criterias) > $level+1): ?>
  <?php echo $this->renderElement('timelog/report_criteria', array(
      'criterias' => $criterias, 
      'hours'=>$hours_for_value, 
      'level'=>($level+1), 
      'availableCriterias'=>$availableCriterias,
      'columns'=>$columns,
      'periods'=>$periods,
      )); ?>
<?php endif; ?>

<?php endforeach; ?>
