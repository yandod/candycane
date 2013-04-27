<?php
class MemberFixture extends CakeTestFixture {
  var $name = 'Member';
  var $fields = array(
      'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
      'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
      'project_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
      'role_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
      'created_on' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
      'mail_notification' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
      'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
  );
  var $records = array(
    array('created_on'=>"2006-07-19 19:35:33 +02:00", 'project_id'=>1, 'role_id'=>1, 'id'=>1, 'user_id'=>2),
    array('created_on'=>"2006-07-19 19:35:36 +02:00", 'project_id'=>1, 'role_id'=>2, 'id'=>2, 'user_id'=>3),
    array('created_on'=>"2006-07-19 19:35:36 +02:00", 'project_id'=>2, 'role_id'=>2, 'id'=>3, 'user_id'=>2),
    array('created_on'=>"2006-07-19 19:35:36 +02:00", 'project_id'=>1, 'role_id'=>2, 'id'=>4, 'user_id'=>5),
    array('created_on'=>"2006-07-19 19:35:33 +02:00", 'project_id'=>5, 'role_id'=>1, 'id'=>5, 'user_id'=>2),
    array('created_on'=>"2013-04-27 15:08:00 +09:00", 'project_id'=>6, 'role_id'=>1, 'id'=>6, 'user_id'=>7),
  );
}