<?php 
class EnumerationFixture extends CakeTestFixture {
  var $name = 'Enumeration';
  var $import = array('model'=>'Enumeration');

  var $records = array(
    array('name'=>'Uncategorized',      'id'=>1,'opt'=>'DCAT'),
    array('name'=>'User documentation', 'id'=>2,'opt'=>'DCAT'),
    array('name'=>'Technical documentation','id'=>3,'opt'=>'DCAT'),
    array('name'=>'Low',                'id'=>4,'opt'=>'IPRI'),
    array('name'=>'Normal', 'id'=>5,'opt'=>'IPRI','is_default'=>true),
    array('name'=>'High',   'id'=>6,'opt'=>'IPRI'),
    array('name'=>'Urgent', 'id'=>7,'opt'=>'IPRI'),
    array('name'=>'Immediate','id'=>8,'opt'=>'IPRI'),
    array('name'=>'Design', 'id'=>9,'opt'=>'ACTI'),
    array('name'=>'Development', 'id'=>10, 'opt'=>'ACTI', 'is_default'=>true),
    array('name'=>'QA', 'id'=>11, 'opt'=>'ACTI')
  );
}
?> 