<?php 
class IssueStatusFixture extends CakeTestFixture {
  var $name = 'IssueStatus';
  var $fields = array(
      'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
      'name' => array('type' => 'string', 'null' => false, 'length' => 30),
      'is_closed' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
      'is_default' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
      'position' => array('type' => 'integer', 'null' => true, 'default' => '1'),
      'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
  );
  var $records = array(
    array('name'=>"Rejected", 'is_default'=>false, 'is_closed'=>true, 'id'=>6),
    array('name'=>"New",      'is_default'=>true,  'is_closed'=>false,'id'=>1),
    array('name'=>"Assigned", 'is_default'=>false, 'is_closed'=>false,'id'=>2),
    array('name'=>"Resolved", 'is_default'=>false, 'is_closed'=>false,'id'=>3),
    array('name'=>"Feedback", 'is_default'=>false, 'is_closed'=>false,'id'=>4),
    array('name'=>"Closed",   'is_default'=>false, 'is_closed'=>true, 'id'=>5),
  );
}
?>