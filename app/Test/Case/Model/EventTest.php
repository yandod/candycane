<?php
App::uses('Event', 'Model');

/**
 * Event Test Case
 *
 */
class EventTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		//'app.event'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Event = ClassRegistry::init('Event');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Event);

		parent::tearDown();
	}

/**
 * testEventDate method
 *
 * @return void
 */
	public function testEventDate() {
	}

/**
 * testEventUrl method
 *
 * @return void
 */
	public function testEventUrl() {
	}

/**
 * testEventDatetime method
 *
 * @return void
 */
	public function testEventDatetime() {
	}

/**
 * testEventTitle method
 *
 * @return void
 */
	public function testEventTitle() {
	}

/**
 * testEventDescription method
 *
 * @return void
 */
	public function testEventDescription() {
	}

/**
 * testEventAuthor method
 *
 * @return void
 */
	public function testEventAuthor() {
	}

/**
 * testEventType method
 *
 * @return void
 */
	public function testEventType() {
	}

/**
 * testGroupBy method
 *
 * @return void
 */
	public function testGroupBy() {
	}

}
