<?php

$json = array();

$json['id']              = (int)$issue['Issue']['id'];
$json['project']         = array('id' => (int)$issue['Project']['id'],  'name' => h($issue['Project']['name']));
$json['tracker']         = array('id' => (int)$issue['Tracker']['id'],  'name' => h($issue['Tracker']['name']));
$json['status']          = array('id' => (int)$issue['Status']['id'],   'name' => h($issue['Status']['name']));
$json['priority']        = array('id' => (int)$issue['Priority']['id'], 'name' => h($issue['Priority']['name']));
$json['author']          = array('id' => (int)$issue['Author']['id'],   'name' => $this->Candy->format_username($issue['Author']));
if (strlen($issue['Issue']['assigned_to_id'])) {
  $json['assigned_to'] = array('id' => (int)$issue['AssignedTo']['id'], 'name' => $this->Candy->format_username($issue['AssignedTo']));
}
$json['subject']         = h($issue['Issue']['subject']);
$json['description']     = h($issue['Issue']['description']);
$json['start_date']      = $issue['Issue']['start_date'];
$json['due_date']        = $issue['Issue']['due_date'];
$json['done_ratio']      = $issue['Issue']['done_ratio'];
$json['estimated_hours'] = $issue['Issue']['estimated_hours'];
$json['created_on']      = $issue['Issue']['created_on'];
$json['updated_on']      = $issue['Issue']['updated_on'];

echo json_encode(array('issue' => $json));
