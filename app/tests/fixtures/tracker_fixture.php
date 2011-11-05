<?php 
class TrackerFixture extends CakeTestFixture {
  var $name = 'Tracker';
  var $fields = array(
    'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
    'name' => array('type' => 'string', 'null' => false, 'length' => 30),
    'is_in_chlog' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
    'position' => array('type' => 'integer', 'null' => true, 'default' => '1'),
    'is_in_roadmap' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
    'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
  );
  var $records = array(
    array('name'=>"Bug", 'id'=>1, 'is_in_chlog'=>true, 'position'=>1), 
    array('name'=>"Feature request", 'id'=>2, 'is_in_chlog'=>true, 'position'=>2),
    array('name'=>"Support request", 'id'=>3, 'is_in_chlog'=>false, 'position'=>3),
  );
}