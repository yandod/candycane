<?php
App::uses('WikiRedirect', 'Model');

/**
 * WikiRedirect Test Case
 *
 */
class WikiRedirectTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.wiki_redirect'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->WikiRedirect = ClassRegistry::init('WikiRedirect');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->WikiRedirect);

		parent::tearDown();
	}

    public function testFind() {
        
    }
}
