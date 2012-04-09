<?php $diff = $this->UnifiedDiff->getUnifiedDiff($diff, array('type' => $diff_type)); ?>
<?php foreach ($diff->data as $table_file): ?>
<div class="autoscroll">
<?php if ($diff_type == 'sbs'): ?>
<table class="filecontent CodeRay">
<thead>
<tr><th colspan="4" class="filename"><?php echo $table_file->file_name; ?></th></tr>
</thead>
<tbody>
<?php 
  $prev_line_left = null;
  $prev_line_right = null;
  $keys = array_keys($table_file->data);
  sort($keys);
  foreach ($keys as $key):
?>
<?php if ($prev_line_left && $prev_line_right && ($table_file->data[$key]->nb_line_left != $prev_line_left+1) && ($table_file->data[$key]->nb_line_right != $prev_line_right+1)): ?>
<tr class="spacing">
<th class="line-num">...</th><td></td><th class="line-num">...</th><td></td>
<?php endif; ?>
<tr>
  <th class="line-num"><?php echo $table_file->data[$key]->nb_line_left; ?></th>
  <td class="line-code <?php echo $table_file->data[$key]->type_diff_left; ?>">
    <pre><?php echo mb_convert_encoding($table_file->data[$key]->line_left, "UTF-8", "auto"); ?></pre>
  </td>
  <th class="line-num"><?php echo $table_file->data[$key]->nb_line_right; ?></th>
  <td class="line-code <?php echo $table_file->data[$key]->type_diff_right; ?>">
    <pre><?php echo mb_convert_encoding($table_file->data[$key]->line_right, "UTF-8", "auto"); ?></pre>
  </td>
</tr>
<?php
  $prev_line_left = $table_file->data[$key]->nb_line_left; 
  $prev_line_right = $table_file->data[$key]->nb_line_right;
?>
<?php endforeach; ?>
</tbody>
</table>

<?php else: ?>
<table class="filecontent CodeRay">
<thead>
<tr><th colspan="3" class="filename"><?php echo $table_file->file_name; ?></th></tr>
</thead>
<tbody>
<?php 
  $prev_line_left = null;
  $prev_line_right = null;
  $keys = array_keys($table_file->data);
  sort($keys);
  foreach ($keys as $line=>$key):
?>
<?php if ($prev_line_left && $prev_line_right && ($table_file->data[$key]->nb_line_left != $prev_line_left+1) && ($table_file->data[$key]->nb_line_right != $prev_line_right+1)): ?>
<tr class="spacing">
<th class="line-num">...</th><th class="line-num">...</th><td></td>
</tr>
<?php endif; ?>
<tr>
  <th class="line-num"><?php echo $table_file->data[$key]->nb_line_left; ?></th>
  <th class="line-num"><?php echo $table_file->data[$key]->nb_line_right; ?></th>
  <?php if (empty($table_file->data[$key]->line_left)): ?>
  <td class="line-code <?php echo $table_file->data[$key]->type_diff_right; ?>">
    <pre><?php echo mb_convert_encoding($table_file->data[$key]->line_right, "UTF-8", "auto"); ?></pre>
  </td>
  <?php else: ?>
  <td class="line-code <?php echo $table_file->data[$key]->type_diff_left; ?>">
    <pre><?php echo mb_convert_encoding($table_file->data[$key]->line_left, "UTF-8", "auto"); ?></pre>
  </td>
  <?php endif; ?>
</tr>
<?php
  if ($table_file->data[$key]->nb_line_left > 0) {
    $prev_line_left = $table_file->data[$key]->nb_line_left;
  }
  if ($table_file->data[$key]->nb_line_right > 0) {
    $prev_line_right = $table_file->data[$key]->nb_line_right;
  }
?>
<?php endforeach; ?>
</tbody>
</table>
<?php endif; ?>

</div>
<?php endforeach; ?>

<?php 
if ($diff->truncated) {
  __('... This diff was truncated because it exceeds the maximum size that can be displayed.');
}
?>
