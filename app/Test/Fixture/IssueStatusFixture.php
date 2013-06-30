<?php 
class IssueStatusFixture extends CakeTestFixture {
  var $name = 'IssueStatus';
  var $fields = array(
      'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
      'name' => array('type' => 'string', 'null' => false, 'length' => 30),
      'is_closed' => array('type' => 'integer', 'null' => false, 'default' => 0),
      'is_default' => array('type' => 'integer', 'null' => false, 'default' => 0),
      'position' => array('type' => 'integer', 'null' => true, 'default' => '1'),
      'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
  );
  var $records = array(
    array('name'=>"Rejected", 'is_default'=>0, 'is_closed'=>1, 'id'=>6, 'position'=>1),
    array('name'=>"New",      'is_default'=>1,  'is_closed'=>0,'id'=>1, 'position'=>2),
    array('name'=>"Assigned", 'is_default'=>0, 'is_closed'=>0,'id'=>2, 'position'=>3),
    array('name'=>"Resolved", 'is_default'=>0, 'is_closed'=>0,'id'=>3, 'position'=>4),
    array('name'=>"Feedback", 'is_default'=>0, 'is_closed'=>0,'id'=>4, 'position'=>5),
    array('name'=>"Closed",   'is_default'=>0, 'is_closed'=>1, 'id'=>5, 'position'=>6),
  );
}