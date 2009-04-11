<?php 
class JournalDetailFixture extends CakeTestFixture {
  var $name = 'JournalDetail';
  var $import = array('model'=>'JournalDetail');
  var $records = array(
    array('created_on'=>date('Y-m-d H:i:s', strtotime("-2 day")), 'notes'=>"Journal notes", 'id'=>1,
          'journalized_type'=>"Issue", 'user_id'=>1, 'journalized_id'=>1),
    array('created_on'=>date('Y-m-d H:i:s', strtotime("-1 day")), 'notes'=>"Some notes with Redmine links'=>#2, r2.", 'id'=>2,
          'journalized_type'=>"Issue", 'user_id'=>2, 'journalized_id'=>1)
  );
}
?>