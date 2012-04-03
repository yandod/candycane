<?php 
class CommentFixture extends CakeTestFixture {
  var $name = 'Comment';
  var $import = array('table'=>'comments');
  var $records = array(
    array('commented_type'=>'News', 'commented_id'=>'1', 'id'=>'1', 'author_id'=>'1', 'comments'=>'my first comment', 'created_on'=>'2006-12-10 18:10:10 +01:00', 'updated_on'=>'2006-12-10 18:10:10 +01:00'),
    array('commented_type'=>'News', 'commented_id'=>'1', 'id'=>'2', 'author_id'=>'2', 'comments'=>'This is an other comment', 'created_on'=>'2006-12-10 18:12:10 +01:00', 'updated_on'=>'2006-12-10 18:12:10 +01:00'),
  );
}