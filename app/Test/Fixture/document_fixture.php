<?php 
class DocumentFixture extends CakeTestFixture {
  var $name = 'Document';
  var $import = array('table'=>'documents');
  var $records = array(
    array('created_on'=>'2007-01-27 15:08:27 +01:00', 'project_id'=>1, 'title'=>"Test document", 'id'=>1, 'description'=>"Document description", 'category_id'=>1),
  );
}