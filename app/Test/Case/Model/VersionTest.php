<?php
App::uses('Version', 'Model');

/**
 * Version Test Case
 *
 */
class VersionTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.version',
		'app.project',
		'app.wiki',
		'app.wiki_page',
		'app.wiki_content',
		'app.user',
		'app.token',
		'app.user_preference',
		'app.member',
		'app.role',
		'app.wiki_content_version',
		'app.wiki_redirect',
		'app.issue_category',
		'app.enabled_module',
		'app.time_entry',
		'app.enumeration',
		'app.tracker',
		'app.workflow',
		'app.issue',
		'app.issue_status',
		'app.changeset',
		'app.changesets_issue',
		'app.projects_tracker',
		'app.custom_field',
		'app.custom_fields_project'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Version = ClassRegistry::init('Version');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Version);

		parent::tearDown();
	}

/**
 * testAfterFindOne method
 *
 * @return void
 */
	public function testAfterFindOne() {
	}

/**
 * testSort method
 *
 * @return void
 */
	public function testSort() {
	}

}
