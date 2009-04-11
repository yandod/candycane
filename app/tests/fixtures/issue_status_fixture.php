<?php 
class IssueStatusFixture extends CakeTestFixture {
  var $name = 'IssueStatus';
  var $import = array('model'=>'IssueStatus');
  var $records = array(
    array('name'=>"Rejected", 'is_default'=>false, 'is_closed'=>true, 'id'=>6),
    array('name'=>"New",      'is_default'=>true,  'is_closed'=>false,'id'=>1),
    array('name'=>"Assigned", 'is_default'=>false, 'is_closed'=>false,'id'=>2),
    array('name'=>"Resolved", 'is_default'=>false, 'is_closed'=>false,'id'=>3),
    array('name'=>"Feedback", 'is_default'=>false, 'is_closed'=>false,'id'=>4),
    array('name'=>"Closed",   'is_default'=>false, 'is_closed'=>true, 'id'=>5),
  );
}
?>