<?php
App::uses('Workflow', 'Model');

/**
 * Workflow Test Case
 *
 */
class WorkflowTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.workflow'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Workflow = ClassRegistry::init('Workflow');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Workflow);

		parent::tearDown();
	}

/**
 * testCountByTrackerAndRole method
 *
 * @return void
 */
	public function testCountByTrackerAndRole() {
	}

}
