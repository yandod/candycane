<?php
# Column headers
$headers = array();
foreach($criterias as $criteria) {
  $headers[] = __($availableCriterias[$criteria]['label'], true);
}
$headers = array_merge($headers, $periods);
$headers[] = __('Total',true);
$csv->addRow($headers);

# Content
$timelog->report_criteria_to_csv($csv, $availableCriterias, $criterias, $periods, $hours, $columns);

# Total row
$row = array(__('Total',true));
for($i = 0; $i < (count($criterias) - 1); $i++) {
  $row[] = '';
}
$total = 0;
foreach($periods as $period) {
  $sum = $timelog->sum_hours($timelog->select_hours($hours, $columns, $period));
  $total += $sum;
  $row[] = ($sum > 0 ? sprintf("%.2f", $sum) : '');
}
$row[] = sprintf("%.2f", $total);
$csv->addRow($row);

echo $csv->render('timelog.csv', __('ISO-8859-1',true), 'UTF-8');
?>