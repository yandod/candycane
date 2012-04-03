<?php 
class CustomValueFixture extends CakeTestFixture {
  var $name = 'CustomValue';
  var $import = array('table'=>'custom_values');
  var $records = array(
    array('customized_type'=>'Issue', 'custom_field_id'=>'2', 'customized_id'=>'3', 'id'=>'9', 'value'=>"125"),
    array('customized_type'=>'Project', 'custom_field_id'=>'3', 'customized_id'=>'1', 'id'=>'10', 'value'=>'Stable'),
    array('customized_type'=>'User', 'custom_field_id'=>'4', 'customized_id'=>'3', 'id'=>'2', 'value'=>""),
    array('customized_type'=>'User', 'custom_field_id'=>'4', 'customized_id'=>'4', 'id'=>'3', 'value'=>'01 23 45 67 89'),
    array('customized_type'=>'User', 'custom_field_id'=>'4', 'customized_id'=>'2', 'id'=>'4', 'value'=>""),
    array('customized_type'=>'Issue', 'custom_field_id'=>'2', 'customized_id'=>'1', 'id'=>'7', 'value'=>"125"),
    array('customized_type'=>'Issue', 'custom_field_id'=>'2', 'customized_id'=>'2', 'id'=>'8', 'value'=>""),
    array('customized_type'=>'Issue', 'custom_field_id'=>'1', 'customized_id'=>'3', 'id'=>'11', 'value'=>"MySQL"),
    array('customized_type'=>'Issue', 'custom_field_id'=>'2', 'customized_id'=>'3', 'id'=>'12', 'value'=>"this is a stringforcustomfield search"),
  );
}