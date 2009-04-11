<?php 
class TrackerFixture extends CakeTestFixture {
  var $name = 'Tracker';
  var $import = array('model'=>'Tracker');
  var $records = array(
    array('name'=>"Bug", 'id'=>1, 'is_in_chlog'=>true, 'position'=>1), 
    array('name'=>"Feature request", 'id'=>2, 'is_in_chlog'=>true, 'position'=>2),
    array('name'=>"Support request", 'id'=>3, 'is_in_chlog'=>false, 'position'=>3),
  );
}
?>
