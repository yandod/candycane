<?php 
class WikiPageFixture extends CakeTestFixture {
  var $name = 'WikiPage';
  var $import = array('table'=>'wiki_pages');
  var $records = array(
    array('created_on'=>'2007-03-07 00:08:07 +01:00', 'title'=>'CookBook_documentation', 'id'=>1, 'wiki_id'=>1, 'protected'=>true, 'parent_id'=>null), 
    array('created_on'=>'2007-03-08 00:18:07 +01:00', 'title'=>'Another_page', 'id'=>2, 'wiki_id'=>1, 'protected'=>false, 'parent_id'=>null),
    array('created_on'=>'2007-03-08 00:18:07 +01:00', 'title'=>'Start_page', 'id'=>3, 'wiki_id'=>2, 'protected'=>false, 'parent_id'=>null),
    array('created_on'=>'2007-03-08 00:18:07 +01:00', 'title'=>'Page_with_an_inline_image', 'id'=>4, 'wiki_id'=>1, 'protected'=>false, 'parent_id'=>1),
    array('created_on'=>'2007-03-08 00:18:07 +01:00', 'title'=>'Child_1', 'id'=>5, 'wiki_id'=>1, 'protected'=>false, 'parent_id'=>2),
    array('created_on'=>'2007-03-08 00:18:07 +01:00', 'title'=>'Child_2', 'id'=>6, 'wiki_id'=>1, 'protected'=>false, 'parent_id'=>2),
    array('created_on'=>'2007-03-08 00:18:07 +01:00', 'title'=>'日本語ページ', 'id'=>7, 'wiki_id'=>1, 'protected'=>false, 'parent_id'=>2)
  );
}