<?php

$json = array(
  'id'              => (int)$issue['Issue']['id'],
  'project'         => array('id' => (int)$issue['Project']['id'],  'name' => h($issue['Project']['name'])),
  'tracker'         => array('id' => (int)$issue['Tracker']['id'],  'name' => h($issue['Tracker']['name'])),
  'status'          => array('id' => (int)$issue['Status']['id'],   'name' => h($issue['Status']['name'])),
  'priority'        => array('id' => (int)$issue['Priority']['id'], 'name' => h($issue['Priority']['name'])),
  'author'          => array('id' => (int)$issue['Author']['id'], 'name' => $this->Candy->format_username($issue['Author'])),
  'subject'         => h($issue['Issue']['subject']),
  'description'     => h($issue['Issue']['description']),
  'start_date'      => $issue['Issue']['start_date'],
  'due_date'        => $issue['Issue']['due_date'],
  'done_ratio'      => $issue['Issue']['done_ratio'],
  'estimated_hours' => $issue['Issue']['estimated_hours'],
  'created_on'      => $issue['Issue']['created_on'],
  'updated_on'      => $issue['Issue']['updated_on'],
);

echo json_encode(array('issue' => $json));
