<?php
class Event extends AppModel {
  var $name = "Event";
  var $useTable = false;

  function event_date($data=false) {
    return date('Y-m-d', strtotime($this->event_datetime($data)));
  }
  function event_url($data=false, $options = array()) {
    $data = $this->__get_data($data);
    return array_merge($data['url'], $options);
  }
  function event_datetime($data=false) {
    $data = $this->__get_data($data);
    return $data['datetime'];
  }
  function event_title($data=false) {
    $data = $this->__get_data($data);
    return $data['title'];
  }
  function event_description($data=false) {
    $data = $this->__get_data($data);
    return $data['description'];
  }
  function event_author($data=false) {
    $data = $this->__get_data($data);
    return $data['author'];
  }
  function event_type($data=false) {
    $data = $this->__get_data($data);
    return $data['type'];
  }
  
  function group_by($events, $element) {
    $results = array();
    foreach($events as $event) {
      $results[$this->$element($event)][] = $event;
    }
    return $results;
  }
  
  function __get_data($data) {
    if(empty($data)) $data = $this->data;
    if(array_key_exists($this->name, $data)) {
      $data = $data[$this->name];
    }
    return $data;
  }
}
