<?php
class Event extends AppModel {
  var $name = "Event";
  var $useTable = false;

  function event_date($data=false) {
    return $this->event_datetime($data);
  }
  function event_url($data=false, $options = array()) {
    if(empty($data)) $data = $Model->data;
    return array_merge($data[$this->name]['url'], $options);
  }
  function event_datetime($data=false) {
    if(empty($data)) $data = $Model->data;
    return $data[$this->name]['datetime'];
  }
  function event_title($data=false) {
    if(empty($data)) $data = $Model->data;
    return $data[$this->name]['title'];
  }
  function event_description($data=false) {
    if(empty($data)) $data = $Model->data;
    return $data[$this->name]['description'];
  }
  function event_author($data=false) {
    if(empty($data)) $data = $Model->data;
    return $data[$this->name]['author'];
  }
  function event_type($data=false) {
    if(empty($data)) $data = $Model->data;
    return $data[$this->name]['type'];
  }
}
