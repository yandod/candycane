<?php
# csv header fields
$headers = array(
  __('Date',true),
  __('User',true),
  __('Activity',true),
  __('Project',true),
  __('Issue',true),
  __('Tracker',true),
  __('Subject',true),
  __('Hours',true),
  __('Comments',true)
);
# Export custom fields
$headers = array_merge($headers, Set::extract('{n}.CustomField.name', $customFields));
$csv->addRow($headers);

# csv lines
foreach($entries as $entry) {
  $fields = array(
    $candy->format_date($entry['TimeEntry']['spent_on']),
    $candy->format_username($entry['User']),
    $entry['Activity']['name'],
    $entry['Project']['name'],
    $entry['TimeEntry']['issue_id'],
    (!empty($entry['Issue']) ? $trackers[$entry['Issue']['tracker_id']] : ''),
    (!empty($entry['Issue']) ? $entry['Issue']['subject'] : ''),
    $entry['TimeEntry']['hours'],
    $entry['TimeEntry']['comments']
  );
  foreach(Set::extract('{n}.CustomField.id', $customFields) as $field_id) {
    $fields[] = $customField->field_value($field_id, $entry['CustomValue']);
  }
  $csv->addRow($fields);
}

echo $csv->render('timelog.csv', __('ISO-8859-1',true), 'UTF-8');
?>