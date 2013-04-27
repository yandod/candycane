<?php
class NewsFixture extends CakeTestFixture {
  var $name = 'News';
  var $import = array('table'=>'news');
  var $records = array(
    array('created_on'=>'2006-07-19 22:40:26 +02:00', 'project_id'=>1, 'title'=>'eCookbook first release !', 'id'=>1, 'description'=>'eCookbook 1.0 has been released.

Visit http://ecookbook.somenet.foo/',  'summary'=>'First version was released...', 'author_id'=>2, 'comments_count'=>1),
    array('created_on'=>'2006-07-19 22:42:58 +02:00', 'project_id'=>1, 'title'=>'100,000 downloads for eCookbook', 'id'=>2, 'description'=>'eCookbook 1.0 have downloaded 100,000 times', 'summary'=>'eCookbook 1.0 have downloaded 100,000 times', 'author_id'=>2, 'comments_count'=>0),
    array('created_on'=>'2006-07-19 22:42:58 +02:00', 'project_id'=>6, 'title'=>'news 3', 'id'=>3, 'description'=>'', 'summary'=>'', 'author_id'=>2, 'comments_count'=>0),
    array('created_on'=>'2006-07-19 22:42:58 +02:00', 'project_id'=>7, 'title'=>'news 4', 'id'=>4, 'description'=>'', 'summary'=>'', 'author_id'=>2, 'comments_count'=>0),
    array('created_on'=>'2006-07-19 22:42:58 +02:00', 'project_id'=>6, 'title'=>'news 5', 'id'=>5, 'description'=>'', 'summary'=>'', 'author_id'=>2, 'comments_count'=>0),
    array('created_on'=>'2006-07-19 22:42:58 +02:00', 'project_id'=>6, 'title'=>'news 6', 'id'=>6, 'description'=>'', 'summary'=>'', 'author_id'=>2, 'comments_count'=>0),
    array('created_on'=>'2006-07-19 22:42:58 +02:00', 'project_id'=>6, 'title'=>'news 7', 'id'=>7, 'description'=>'', 'summary'=>'', 'author_id'=>2, 'comments_count'=>0),
    array('created_on'=>'2006-07-19 22:42:58 +02:00', 'project_id'=>6, 'title'=>'news 8', 'id'=>8, 'description'=>'', 'summary'=>'', 'author_id'=>2, 'comments_count'=>0),
  );
}