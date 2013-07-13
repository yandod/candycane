<?php
App::uses('CustomValue', 'Model');

/**
 * CustomValue Test Case
 *
 */
class CustomValueTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.custom_value',
		'app.custom_field'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->CustomValue = ClassRegistry::init('CustomValue');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CustomValue);

		parent::tearDown();
	}

/**
 * testValidateValueRegexp method
 *
 * @return void
 */
	public function testValidateValueRegexp() {
	}

/**
 * testValidateValueRange method
 *
 * @return void
 */
	public function testValidateValueRange() {
	}

/**
 * testValidateValueFormat method
 *
 * @return void
 */
	public function testValidateValueFormat() {
	}

}
