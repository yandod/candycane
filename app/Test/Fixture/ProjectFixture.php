<?php
class ProjectFixture extends CakeTestFixture {
  var $name = 'Project';
  var $fields = array(
    'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
    'name' => array('type' => 'string', 'null' => false, 'length' => 30),
    'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
    'homepage' => array('type' => 'string', 'null' => true),
    'is_public' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
    'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
    'projects_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
    'created_on' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
    'updated_on' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
    'identifier' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 20),
    'status' => array('type' => 'integer', 'null' => false, 'default' => '1'),
    'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
  );
  var $records = array(
    array('created_on'=>"2006-07-19 19:13:59 +02:00", 'name'=>"eCookbook", 'updated_on'=>"2006-07-19 22:53:01 +02:00",
          'projects_count'=>3, 'id'=>1, 'description'=>"Recipes management application", 'status' => 1,
          'homepage'=>"http://ecookbook.somenet.foo/", 'is_public'=>true, 'identifier'=>"ecookbook", 'parent_id'=>null),
    array('created_on'=>"2006-07-19 19:14:19 +02:00", 'name'=>"OnlineStore", 'updated_on'=>"2006-07-19 19:14:19 +02:00",
          'projects_count'=>0, 'id'=>2, 'description'=>"E-commerce web site",  'status' => 1,
          'homepage'=>"", 'is_public'=>false, 'identifier'=>"onlinestore", 'parent_id'=>null),
    array('created_on'=>"2006-07-19 19:15:21 +02:00", 'name'=>"eCookbook Subproject 1", 'updated_on'=>"2006-07-19 19:18:12 +02:00",
          'projects_count'=>0, 'id'=>3, 'description'=>"eCookBook Subproject 1",  'status' => 1,
          'homepage'=>"", 'is_public'=>true, 'identifier'=>"subproject1", 'parent_id'=>1),
    array('created_on'=>"2006-07-19 19:15:51 +02:00", 'name'=>"eCookbook Subproject 2", 'updated_on'=>"2006-07-19 19:17:07 +02:00",
          'projects_count'=>0, 'id'=>4, 'description'=>"eCookbook Subproject 2", 'status' => 1,
          'homepage'=>"", 'is_public'=>true, 'identifier'=>"subproject2", 'parent_id'=>1),
    array('created_on'=>"2006-07-19 19:15:51 +02:00", 'name'=>"Private child of eCookbook", 'updated_on'=>"2006-07-19 19:17:07 +02:00",
          'projects_count'=>0, 'id'=>5, 'description'=>"This is a private subproject of a public project", 'status' => 1,
          'homepage'=>"", 'is_public'=>false, 'identifier'=>"private_child", 'parent_id'=>1),
    array('created_on'=>"2013-04-27 13:30:0 +09:00", 'name'=>"Project hidden from Fetcher", 'updated_on'=>"2013-04-27 13:30:0 +09:00",
          'projects_count'=>0, 'id'=>6, 'description'=>"This is for test about the visiblity of the news.", 'status' => 1,
          'homepage'=>"", 'is_public'=>false, 'identifier'=>"hiddenFromFetcher", 'parent_id'=>null),
    array('created_on'=>"2013-04-27 13:30:0 +09:00", 'name'=>"No member project", 'updated_on'=>"2013-04-27 13:30:0 +09:00",
          'projects_count'=>0, 'id'=>7, 'description'=>"This is for test about no members.", 'status' => 1,
          'homepage'=>"", 'is_public'=>false, 'identifier'=>"nomembers", 'parent_id'=>null),
  );
}