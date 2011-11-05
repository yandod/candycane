<?php 
class JournalDetailFixture extends CakeTestFixture {
  var $name = 'JournalDetail';
  var $fields = array(
      'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'primary'),
      'journal_id' => array('type'=>'integer', 'null' => false, 'default' => '0', 'length' => 11),
      'property' => array('type'=>'string', 'null' => false, 'default' => '', 'length' => 30),
      'prop_key' => array('type'=>'string', 'null' => false, 'default' => '', 'length' => 30),
      'old_value' => array('type'=>'string', 'null' => true, 'length' => 255),
      'value' => array('type'=>'string', 'null' => true, 'length' => 255),
      'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
    );
  var $records = array(
    array('old_value'=>"1", 'property'=>"attr",'id'=>1,'value'=>"2", 'prop_key'=>"status_id",'journal_id'=>1),
    array('old_value'=>"40",'property'=>"attr",'id'=>2,'value'=>"30",'prop_key'=>"done_ratio",'journal_id'=>1),
  );
}