<?php 
class MessageFixture extends CakeTestFixture {
  var $name = 'Message';
  var $import = array('model'=>'Message');
  var $records = array(
    array('created_on'=>"2007-05-12 17:15:32 +02:00", 'updated_on'=>"2007-05-12 17:15:32 +02:00",
          'subject'=>"First post", 'id'=>1, 'replies_count'=>2, 'last_reply_id'=>3,
          'content'=>"This is the very first post\nin the forum", 'author_id'=>1, 'parent_id'=>null, 'board_id'=>1
    ),
    array('created_on'=>"2007-05-12 17:18:00 +02:00", 'updated_on'=>"2007-05-12 17:18:00 +02:00",
          'subject'=>"First reply", 'id'=>2, 'replies_count'=>0, 'last_reply_id'=>null, 
          'content'=>"Reply to the first post", 'author_id'=>1, 'parent_id'=>1, 'board_id'=>1), 
    array('created_on'=>"2007-05-12 17:18:02 +02:00", 'updated_on'=>"2007-05-12 17:18:02 +02:00", 
          'subject'=>"RE'=>First post", 'id'=>3, 'replies_count'=>0, 'last_reply_id'=>null,
          'content'=>"An other reply", 'author_id'=>null, 'parent_id'=>1, 'board_id'=>1), 
    array('created_on'=>"2007-08-12 17:15:32 +02:00", 'updated_on'=>"2007-08-12 17:15:32 +02:00",
          'subject'=>"Post 2", 'id'=>4, 'replies_count'=>2, 'last_reply_id'=>6,
          'content'=>"This is an other post", 'author_id'=>null, 'parent_id'=>null, 'board_id'=>1),
    array('created_on'=>date('Y-m-d H:i:s', strtotime("-3 day")), 'updated_on'=>date('Y-m-d H:i:s', strtotime("-3 day")),
          'subject'=>'RE: post 2', 'id'=>5, 'replies_count'=>0, 'last_reply_id'=>null, 
          'content'=>"Reply to the second post", 'author_id'=>1, 'parent_id'=>4, 'board_id'=>1),
    array('created_on'=>date('Y-m-d H:i:s', strtotime("-2 day")), 'updated_on'=>date('Y-m-d H:i:s', strtotime("-2 day")),
          'subject'=>'RE: post 2', 'id'=>6, 'replies_count'=>0, 'last_reply_id'=>null,
          'content'=>"Another reply to the second post", 'author_id'=>3, 'parent_id'=>4, 'board_id'=>1)
  );
}
?>
