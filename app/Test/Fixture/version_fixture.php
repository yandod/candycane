<?php 
class VersionFixture extends CakeTestFixture {
  var $name = 'Version';
  var $fields = array(
    'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
    'project_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
    'name' => array('type' => 'string', 'null' => false),
    'description' => array('type' => 'string', 'null' => true),
    'effective_date' => array('type' => 'date', 'null' => true, 'default' => NULL),
    'created_on' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
    'updated_on' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
    'wiki_page_title' => array('type' => 'string', 'null' => true, 'default' => NULL),
    'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'versions_project_id' => array('column' => 'project_id', 'unique' => 0))
  );
  var $records = array(
    array('created_on'=>"2006-07-19 21:00:07 +02:00", 'name'=>"0.1", 'project_id'=>1,
          'updated_on'=>"2006-07-19 21:00:07 +02:00", 'id'=>1, 'description'=>"Beta", 'effective_date'=>"2006-07-01"),
//    array('created_on'=>"2006-07-19 21:00:33 +02:00", 'name'=>"1.0", 'project_id'=>1,
//         'updated_on'=>"2006-07-19 21:00:33 +02:00", 'id'=>2, 'description'=>"Stable release", 'effective_date'=>date('Y-m-d H:i:s', strtotime("+20 day"))),
    array('created_on'=>"2006-07-19 21:00:33 +02:00", 'name'=>"1.0", 'project_id'=>1,
         'updated_on'=>"2006-07-19 21:00:33 +02:00", 'id'=>2, 'description'=>"Stable release", 'effective_date'=>"+20 day"),
    array('created_on'=>"2006-07-19 21:00:33 +02:00", 'name'=>"2.0", 'project_id'=>1,
          'updated_on'=>"2006-07-19 21:00:33 +02:00", 'id'=>3, 'description'=>"Future version", 'effective_date'=>null)
  );
}