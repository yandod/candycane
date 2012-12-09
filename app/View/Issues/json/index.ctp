<?php

$json = array();
foreach ($issue_list as $issue) {
  $data = array();

  $data['id']       = (int)$issue['Issue']['id'];
  $data['project']  = array('id' => (int)$issue['Project']['id'],  'name' => h($issue['Project']['name']));
  $data['tracker']  = array('id' => (int)$issue['Tracker']['id'],  'name' => h($issue['Tracker']['name']));
  $data['status']   = array('id' => (int)$issue['Status']['id'],   'name' => h($issue['Status']['name']));
  $data['priority'] = array('id' => (int)$issue['Priority']['id'], 'name' => h($issue['Priority']['name']));
  $data['author']   = array('id' => (int)$issue['Author']['id'], 'name' => $this->Candy->format_username($issue['Author']));

  if (strlen($issue['Issue']['assigned_to_id'])) {
    $data['assigned_to'] = array('id' => (int)$issue['AssignedTo']['id'], 'name' => $this->Candy->format_username($issue['AssignedTo']));
  }

  $data['subject']         = h($issue['Issue']['subject']);
  $data['description']     = h($issue['Issue']['description']);
  $data['start_date']      = $issue['Issue']['start_date'];
  $data['due_date']        = $issue['Issue']['due_date'];
  $data['done_ratio']      = $issue['Issue']['done_ratio'];
  $data['estimated_hours'] = $issue['Issue']['estimated_hours'];
  $data['created_on']      = $issue['Issue']['created_on'];
  $data['updated_on']      = $issue['Issue']['updated_on'];

  $json[] = $data;
}

echo json_encode(array('issues' => $json));
