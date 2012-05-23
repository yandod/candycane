<?php
# Column headers
$headers = array();
foreach($criterias as $criteria) {
  $headers[] = __($availableCriterias[$criteria]['label']);
}
$headers = array_merge($headers, $periods);
$headers[] = __('Total');
$csv->addRow($headers);

# Content
$this->Timelog->report_criteria_to_csv($csv, $availableCriterias, $criterias, $periods, $hours, $columns);

# Total row
$row = array(__('Total'));
for($i = 0; $i < (count($criterias) - 1); $i++) {
  $row[] = '';
}
$total = 0;
foreach($periods as $period) {
  $sum = $this->Timelog->sum_hours($this->Timelog->select_hours($hours, $columns, $period));
  $total += $sum;
  $row[] = ($sum > 0 ? sprintf("%.2f", $sum) : '');
}
$row[] = sprintf("%.2f", $total);
$csv->addRow($row);

echo $csv->render('timelog.csv', __('ISO-8859-1'), 'UTF-8');
?>