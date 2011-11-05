<?php 
class WikiFixture extends CakeTestFixture {
  var $name = 'Wiki';
  var $import = array('table'=>'wikis');
  var $records = array(
    array('status'=>'1','start_page'=>'CookBook documentation','project_id'=>'1','id'=>'1'),
    array('status'=>'1','start_page'=>'Start page','project_id'=>'2','id'=>'2'),
  );
}