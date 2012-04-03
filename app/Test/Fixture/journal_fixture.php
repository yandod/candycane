<?php 
class JournalFixture extends CakeTestFixture {
  var $name = 'Journal';
  var $fields = array(
      'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'primary'),
      'journalized_id' => array('type'=>'integer', 'null' => false, 'default' => '0', 'length' => 11),
      'journalized_type' => array('type'=>'string', 'null' => false, 'default' => '', 'length' => 30),
      'user_id' => array('type'=>'integer', 'null' => false, 'default' => '0', 'length' => 11),
      'notes' => array('type'=>'text', 'null' => true),
      'created_on' => array('type'=>'datetime', 'null' => false),
      'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
    );
  var $records = array(
  /*
    array('created_on'=>date('Y-m-d H:i:s', strtotime("-2 day")), 'notes'=>"Journal notes", 'id'=>1,
          'journalized_type'=>"Issue", 'user_id'=>1, 'journalized_id'=>1),
    array('created_on'=>date('Y-m-d H:i:s', strtotime("-1 day")), 'notes'=>"Some notes with Redmine links'=>#2, r2.", 'id'=>2,
          'journalized_type'=>"Issue", 'user_id'=>2, 'journalized_id'=>1)
          */
    array('created_on'=>"-2 days", 'notes'=>"Journal notes", 'id'=>1,
          'journalized_type'=>"Issue", 'user_id'=>1, 'journalized_id'=>1),
    array('created_on'=>"-1 days", 'notes'=>"Some notes with Redmine links'=>#2, r2.", 'id'=>2,
          'journalized_type'=>"Issue", 'user_id'=>2, 'journalized_id'=>1)
  );
  
  function init() {
    foreach($this->records as $index => $record) {
      $this->records[$index]['created_on'] = date('Y-m-d H:m:s', strtotime($record['created_on']));
    }
    
    return parent::init();
  }
}