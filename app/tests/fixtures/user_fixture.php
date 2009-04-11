<?php 
class UserFixture extends CakeTestFixture {
  var $name = 'User';
  var $import = array('model'=>'User');
  var $records = array(
    array('created_on'=>"2006-07-19 19:34:07 +02:00", 'status'=>1, 'last_login_on'=>null, 'language'=>"en",
          'hashed_password'=>"4e4aeb7baaf0706bd670263fef42dad15763b608", 'updated_on'=>"2006-07-19 19:34:07 +02:00",
          'admin'=>false, 'mail'=>"rhill@somenet.foo", 'lastname'=>"Hill", 'firstname'=>"Robert", 'id'=>4,
          'auth_source_id'=>null, 'mail_notification'=>true, 'login'=>"rhill", 'type'=>"User"),
    array('created_on'=>"2006-07-19 19:12:21 +02:00", 'status'=>1, 'last_login_on'=>"2006-07-19 22:57:52 +02:00", 'language'=>"en",
          'hashed_password'=>"d033e22ae348aeb5660fc2140aec35850c4da997", 'updated_on'=>"2006-07-19 22:57:52 +02:00",
          'admin'=>true, 'mail'=>"admin@somenet.foo", 'lastname'=>"Admin", 'firstname'=>"redMine", 'id'=>1,
          'auth_source_id'=>null, 'mail_notification'=>true, 'login'=>"admin", 'type'=>"User"), 
    array('created_on'=>"2006-07-19 19:32:09 +02:00", 'status'=>1, 'last_login_on'=>"2006-07-19 22:42:15 +02:00", 'language'=>"en",
          'hashed_password'=>"a9a653d4151fa2c081ba1ffc2c2726f3b80b7d7d", 'updated_on'=>"2006-07-19 22:42:15 +02:00",
          'admin'=>false, 'mail'=>"jsmith@somenet.foo", 'lastname'=>"Smith", 'firstname'=>"John", 'id'=>2,
          'auth_source_id'=>null, 'mail_notification'=>true, 'login'=>"jsmith", 'type'=>"User"), 
    array('created_on'=>"2006-07-19 19:33:19 +02:00", 'status'=>1, 'last_login_on'=>null, 'language'=>"en",
          'hashed_password'=>"7feb7657aa7a7bf5aef3414a5084875f27192415", 'updated_on'=>"2006-07-19 19:33:19 +02:00", 
          'admin'=>false, 'mail'=>"dlopper@somenet.foo", 'lastname'=>"Lopper", 'firstname'=>"Dave", 'id'=>3,
          'auth_source_id'=>null, 'mail_notification'=>true, 'login'=>"dlopper", 'type'=>"User"),
    array('created_on'=>"2006-07-19 19:33:19 +02:00", 'status'=>3, 'last_login_on'=>null, 'language'=>"en",
          'hashed_password'=>"7feb7657aa7a7bf5aef3414a5084875f27192415", 'updated_on'=>"2006-07-19 19:33:19 +02:00",
          'admin'=>false, 'mail'=>"dlopper2@somenet.foo", 'lastname'=>"Lopper2", 'firstname'=>"Dave2", 'id'=>5,
          'auth_source_id'=>null, 'mail_notification'=>true, 'login'=>"dlopper2", 'type'=>"User"), 
    array('created_on'=>"2006-07-19 19:33:19 +02:00", 'status'=>1, 'last_login_on'=>null, 'language'=>'',
          'hashed_password'=>"1", 'updated_on'=>"2006-07-19 19:33:19 +02:00", 
          'admin'=>false, 'mail'=>'', 'lastname'=>"Anonymous", 'firstname'=>'', 'id'=>6,
          'auth_source_id'=>null, 'mail_notification'=>false, 'login'=>'', 'type'=>"AnonymousUser")
  );
}
?>
