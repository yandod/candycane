<?php
class EnumerationFixture extends CakeTestFixture {
  var $name = 'Enumeration';
  var $fields = array(
      'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
      'opt' => array('type' => 'string', 'null' => false, 'length' => 4),
      'name' => array('type' => 'string', 'null' => false, 'length' => 30),
      'position' => array('type' => 'integer', 'null' => true, 'default' => '1'),
      'is_default' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
      'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
  );

  var $records = array(
    array('name'=>'Uncategorized',      'id'=>1,'opt'=>'DCAT','position' => 1, 'is_default'=>false),
    array('name'=>'User documentation', 'id'=>2,'opt'=>'DCAT','position' => 2, 'is_default'=>false),
    array('name'=>'Technical documentation','id'=>3,'opt'=>'DCAT','position' => 3, 'is_default'=>false),
    array('name'=>'Low',                'id'=>4,'opt'=>'IPRI','position' => 1, 'is_default'=>false),
    array('name'=>'Normal', 'id'=>5,'opt'=>'IPRI','position' => 2, 'is_default'=>true),
    array('name'=>'High',   'id'=>6,'opt'=>'IPRI','position' => 3, 'is_default'=>false),
    array('name'=>'Urgent', 'id'=>7,'opt'=>'IPRI','position' => 4, 'is_default'=>false),
    array('name'=>'Immediate','id'=>8,'opt'=>'IPRI','position' => 5, 'is_default'=>false),
    array('name'=>'Design', 'id'=>9,'opt'=>'ACTI','position' => 1, 'is_default'=>false),
    array('name'=>'Development', 'id'=>10, 'opt'=>'ACTI', 'position' => 2, 'is_default'=>true),
    array('name'=>'QA', 'id'=>11, 'opt'=>'ACTI','position' => 3, 'is_default'=>false)
  );
}