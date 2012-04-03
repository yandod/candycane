<?php 
class ChangesetsIssueFixture extends CakeTestFixture {
  var $name = 'ChangesetsIssue';
  var $fields = array(
      'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'primary'),
      'changeset_id' => array('type'=>'integer', 'null' => false, 'length' => 11),
      'issue_id' => array('type'=>'integer', 'null' => false, 'length' => 11),
      'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
    );
  var $records = array(
  );
}