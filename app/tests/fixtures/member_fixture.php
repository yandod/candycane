<?php 
class MemberFixture extends CakeTestFixture {
  var $name = 'Member';
  var $import = array('model'=>'Member');
  var $records = array(
    array('created_on'=>"2006-07-19 19:35:33 +02:00", 'project_id'=>1, 'role_id'=>1, 'id'=>1, 'user_id'=>2),
    array('created_on'=>"2006-07-19 19:35:36 +02:00", 'project_id'=>1, 'role_id'=>2, 'id'=>2, 'user_id'=>3),
    array('created_on'=>"2006-07-19 19:35:36 +02:00", 'project_id'=>2, 'role_id'=>2, 'id'=>3, 'user_id'=>2),
    array('created_on'=>"2006-07-19 19:35:36 +02:00", 'project_id'=>1, 'role_id'=>2, 'id'=>4, 'user_id'=>5),
    array('created_on'=>"2006-07-19 19:35:33 +02:00", 'project_id'=>5, 'role_id'=>1, 'id'=>5, 'user_id'=>2)
  );
}