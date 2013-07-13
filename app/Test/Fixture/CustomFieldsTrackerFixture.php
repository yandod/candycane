<?php
/**
 * CustomFieldsTrackerFixture
 *
 */
class CustomFieldsTrackerFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'custom_field_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'tracker_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'custom_field_id' => 1,
			'tracker_id' => 1
		),
	);

}
