<?php
App::uses('IssueCustomField', 'Model');

/**
 * IssueCustomField Test Case
 *
 */
class IssueCustomFieldTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.custom_field'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->IssueCustomField = ClassRegistry::init('IssueCustomField');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->IssueCustomField);

		parent::tearDown();
	}

    public function testFind() {

    }
}
