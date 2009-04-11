<?php 
class BoardFixture extends CakeTestFixture {
  var $name = 'Board';
  var $import = array('model'=>'Board');
  var $records = array(
    array('name'=>'Help', 'project_id'=>1, 'topics_count'=>2, 'id'=>1, 'description'=>'Help board', 'position'=>1, 'last_message_id'=>5, 'messages_count'=>5),
    array('name'=>'Discussion','project_id'=>1,'topics_count'=>0,'id'=>2,'description'=>'Discussion board','position'=>2,'last_message_id'=>null,'messages_count'=>0);
  );
?>