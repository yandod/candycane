<?php
# csv header fields
$headers = array(
  __('Date'),
  __('User'),
  __('Activity'),
  __('Project'),
  __('Issue'),
  __('Tracker'),
  __('Subject'),
  __('Hours'),
  __('Comments')
);
# Export custom fields
if (isset($customFields) && !empty($customFields)) {
	$headers = array_merge($headers, Set::extract('{n}.CustomField.name', $customFields));
}
$this->csv->addRow($headers);

# csv lines
foreach($entries as $entry) {
  $fields = array(
    $this->Candy->format_date($entry['TimeEntry']['spent_on']),
    $this->Candy->format_username($entry['User']),
    $entry['Activity']['name'],
    $entry['Project']['name'],
    $entry['TimeEntry']['issue_id'],
    (!empty($entry['Issue']) ? $trackers[$entry['Issue']['tracker_id']] : ''),
    (!empty($entry['Issue']) ? $entry['Issue']['subject'] : ''),
    $entry['TimeEntry']['hours'],
    $entry['TimeEntry']['comments']
  );
  if (isset($customFields) && !empty($customFields)) {
	foreach(Set::extract('{n}.CustomField.id', $customFields) as $field_id) {
	  $fields[] = $this->CustomField->field_value($field_id, $entry['CustomValue']);
	}
  }
  $this->csv->addRow($fields);
}

echo $this->csv->render('timelog.csv', __('ISO-8859-1'), 'UTF-8');
?>