<?php 
class JournalDetailFixture extends CakeTestFixture {
  var $name = 'JournalDetail';
  var $import = array('model'=>'JournalDetail');
  var $records = array(
    array('old_value'=>"1", 'property'=>"attr",'id'=>1,'value'=>"2", 'prop_key'=>"status_id",'journal_id'=>1),
    array('old_value'=>"40",'property'=>"attr",'id'=>2,'value'=>"30",'prop_key'=>"done_ratio",'journal_id'=>1),
  );
}
?>