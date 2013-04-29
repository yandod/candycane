<?php
class EnabledModuleFixture extends CakeTestFixture {
  var $name = 'EnabledModule';
  var $fields = array(
      'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
      'project_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
      'name' => array('type' => 'string', 'null' => false, 'default' => NULL),
      'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'enabled_modules_project_id' => array('column' => 'project_id', 'unique' => 0))
  );
  var $records = array(
    array('name'=>'issue_tracking', 'project_id'=>1,'id'=>1),
    array('name'=>'time_tracking',  'project_id'=>1,'id'=>2),
    array('name'=>'news',           'project_id'=>1,'id'=>3),
    array('name'=>'documents',      'project_id'=>1,'id'=>4),
    array('name'=>'files',          'project_id'=>1,'id'=>5),
    array('name'=>'wiki',           'project_id'=>1,'id'=>6),
    array('name'=>'repository',     'project_id'=>1,'id'=>7),
    array('name'=>'boards',         'project_id'=>1,'id'=>8),
    array('name'=>'repository',     'project_id'=>3,'id'=>9),
    array('name'=>'wiki',           'project_id'=>3,'id'=>10),
    array('name'=>'issue_tracking', 'project_id'=>2,'id'=>11),
    array('name'=>'time_tracking',  'project_id'=>3,'id'=>12),
    array('name'=>'issue_tracking', 'project_id'=>3,'id'=>13),
    array('name'=>'issue_tracking', 'project_id'=>5,'id'=>14),
    array('name'=>'news',           'project_id'=>6,'id'=>15),
    array('name'=>'news',           'project_id'=>7,'id'=>16),
  );
}