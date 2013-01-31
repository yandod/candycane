<?php 
$values = $this->Timelog->criteria_values($criterias, $hours, $level);
foreach($values as $value):
?>
<?php $hours_for_value = $this->Timelog->select_hours($hours, $criterias[$level], $value); ?>
<?php if(empty($hours_for_value)) { continue; } ?>
<tr class="<?php echo $this->Candy->cycle(); ?> <?php if(!(count($criterias) > $level+1)) { echo 'last-level'; }  ?>">
<?php echo $this->Timelog->empty_td($level); ?>
<td><?php echo h($this->Timelog->format_criteria_value($availableCriterias, $criterias[$level], $value)); ?></td>
<?php echo $this->Timelog->empty_td(count($criterias) - $level - 1); ?>
  <?php $total = 0; ?>
  <?php foreach($periods as $period): ?>
    <?php $sum = $this->Timelog->sum_hours($this->Timelog->select_hours($hours_for_value, $columns, $period)); $total += $sum; ?>
    <td class="hours"><?php if($sum > 0) { echo $this->Candy->html_hours(sprintf(__("%.2f"), $sum)); } ?></td>
  <?php endforeach; ?>
  <td class="hours"><?php if($total > 0) { echo $this->Candy->html_hours(sprintf(__("%.2f"), $total)); } ?></td>
</tr>
<?php if(count($criterias) > $level+1): ?>
  <?php echo $this->element('timelog/report_criteria', array(
      'criterias' => $criterias, 
      'hours'=>$hours_for_value, 
      'level'=>($level+1), 
      'availableCriterias'=>$availableCriterias,
      'columns'=>$columns,
      'periods'=>$periods,
      )); ?>
<?php endif; ?>

<?php endforeach; ?>
