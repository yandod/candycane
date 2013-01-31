<?php
/**
 * QueryFixture
 *
 */
class QueryFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'project_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'name' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'filters' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'is_public' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'column_names' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '1',
			'project_id' => '1',
			'name' => 'test',
			'filters' => '---
status_id: 
  :operator: "o"
  :values: 
    - "1"
tracker_id: 
  :operator: "="
  :values: 
    - "2"
',
			'user_id' => '2',
			'is_public' => 0,
			'column_names' => NULL
		),
		array(
			'id' => '4',
			'project_id' => '1',
			'name' => '自分の未完了',
			'filters' => '---
status_id: 
  :operator: "o"
  :values: 
    - "1"
assigned_to_id: 
  :operator: "="
  :values: 
    - me
',
			'user_id' => '3',
			'is_public' => 0,
			'column_names' => NULL
		),
		array(
			'id' => '5',
			'project_id' => '1',
			'name' => '治ったバグ',
			'filters' => '---
status_id: 
  :operator: "c"
  :values: 
    - "1"
tracker_id: 
  :operator: "="
  :values: 
    - "1"
',
			'user_id' => '3',
			'is_public' => 0,
			'column_names' => NULL
		),
		array(
			'id' => '7',
			'project_id' => '1',
			'name' => '未完了バグ非公開',
			'filters' => '---
status_id: 
  :operator: "o"
  :values: 
    - "1"
tracker_id: 
  :operator: "="
  :values: 
    - "1"
',
			'user_id' => '3',
			'is_public' => 0,
			'column_names' => NULL
		),
		array(
			'id' => '8',
			'project_id' => '1',
			'name' => '未完了バグ公開',
			'filters' => '---
status_id: 
  :operator: "o"
  :values: 
    - "1"
tracker_id: 
  :operator: "="
  :values: 
    - "1"
',
			'user_id' => '3',
			'is_public' => 1,
			'column_names' => NULL
		),
	);
}
