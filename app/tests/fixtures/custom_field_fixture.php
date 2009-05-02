<?php 
class CustomFieldFixture extends CakeTestFixture {
  var $name = 'CustomField';
  var $import = array('table'=>'custom_fields');
  var $records = array(
    array('name'=>'Database', 'min_length'=>'0', 'regexp'=>"", 'is_for_all'=>true, 'is_filter'=>true, 'type'=>'IssueCustomField', 'max_length'=>'0', 
'possible_values'=>"
- MySQL
- PostgreSQL
- Oracle
", 'id'=>'1', 'is_required'=>false, 'field_format'=>'list', 'searchable'=>false, 'default_value'=>"", 'position'=>1),
    array('name'=>'Searchable field', 'min_length'=>'1', 'regexp'=>"", 'is_for_all'=>true, 'is_filter'=>false, 'type'=>'IssueCustomField', 'max_length'=>'100', 'possible_values'=>"", 'id'=>'2', 'is_required'=>false, 'field_format'=>'string', 'searchable'=>true, 'default_value'=>"Default string", 'position'=>2),
    array('name'=>'Development status', 'min_length'=>'0', 'regexp'=>"", 'is_for_all'=>false, 'is_filter'=>true, 'type'=>'ProjectCustomField', 'max_length'=>'0', 
'possible_values'=>"
- Stable
- Beta
- Alpha
- Planning
", 'id'=>'3', 'is_required'=>true, 'field_format'=>'list', 'searchable'=>false, 'default_value'=>"", 'position'=>3),
    array('name'=>'Phone number', 'min_length'=>'0', 'regexp'=>"", 'is_for_all'=>false, 'is_filter'=>false, 'type'=>'UserCustomField', 'max_length'=>'0', 'possible_values'=>"", 'id'=>'4', 'is_required'=>false, 'field_format'=>'string', 'searchable'=>false, 'default_value'=>"", 'position'=>4),
    array('name'=>'Money', 'min_length'=>'0', 'regexp'=>"", 'is_for_all'=>false, 'is_filter'=>false, 'type'=>'UserCustomField', 'max_length'=>'0', 'possible_values'=>"", 'id'=>'5', 'is_required'=>false, 'field_format'=>'float', 'searchable'=>false, 'default_value'=>"", 'position'=>5),
  );
}
?>