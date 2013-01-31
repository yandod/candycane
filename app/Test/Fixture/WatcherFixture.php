<?php 
class WatcherFixture extends CakeTestFixture {
  var $name = 'Watcher';
  var $import = array('table'=>'watchers');
  var $records = array(
    array('watchable_type'=>'Issue','watchable_id'=>'2','user_id'=>'3'),
    array('watchable_type'=>'Message','watchable_id'=>'1','user_id'=>'1'),
  );
}