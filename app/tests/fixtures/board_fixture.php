<?php 
class BoardFixture extends CakeTestFixture {
  var $name = 'Board';
  var $fields = array(
      'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'primary'),
      'project_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 11),
      'name' => array('type'=>'string', 'null' => false, 'default' => '', 'length' => 255),
      'description' => array('type'=>'string', 'null' => true, 'length' => 255),
      'position' => array('type'=>'integer', 'default' => 1, 'length' => 11),
      'topics_count' => array('type'=>'integer', 'null' => false, 'default' => 0, 'length' => 11),
      'messages_count' => array('type'=>'integer', 'null' => false, 'default' => 0, 'length' => 11),
      'last_message_id' => array('type'=>'integer', 'null' => false, 'default' => 0, 'length' => 11),
      'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
    );
  var $records = array(
    array('name'=>'Help', 'project_id'=>1, 'topics_count'=>2, 'id'=>1, 'description'=>'Help board', 'position'=>1, 'last_message_id'=>5, 'messages_count'=>5),
    array('name'=>'Discussion','project_id'=>1,'topics_count'=>0,'id'=>2,'description'=>'Discussion board','position'=>2,'last_message_id'=>null,'messages_count'=>0)
  );
}