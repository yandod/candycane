<?php
App::uses('Setting', 'Model');

/**
 * Setting Test Case
 *
 */
class SettingTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.setting');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Setting = ClassRegistry::init('Setting');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Setting);

		parent::tearDown();
	}

/**
 * testStore method
 *
 * @return void
 */
	public function testStore() {
		$this->loadFixtures('Setting');

		$this->assertNotEmpty($this->Setting->app_title);
		$this->assertEqual('TestCandyCane', $this->Setting->app_title);

		$test_app_title = 'test_app_title';
		$this->Setting->store('app_title', $test_app_title);
		$data = $this->Setting->find('all', array(
			'conditions' => array('name' => 'app_title')
		));
		$this->assertEqual($test_app_title, $data[0]['Setting']['value']);

		$data = $this->Setting->find('all', array(
			'conditions' => array('name' => 'app_subtitle')
		));
		$this->assertEmpty($data);
	}
}
