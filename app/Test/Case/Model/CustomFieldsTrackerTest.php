<?php
App::uses('CustomFieldsTracker', 'Model');

/**
 * CustomFieldsTracker Test Case
 *
 */
class CustomFieldsTrackerTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.custom_fields_tracker',
		'app.custom_field',
		'app.tracker',
		'app.workflow',
		'app.issue',
		'app.issue_status',
		'app.user',
		'app.token',
		'app.user_preference',
		'app.member',
		'app.project',
		'app.wiki',
		'app.wiki_page',
		'app.wiki_content',
		'app.wiki_content_version',
		'app.wiki_redirect',
		'app.issue_category',
		'app.version',
		'app.enabled_module',
		'app.time_entry',
		'app.enumeration',
		'app.projects_tracker',
		'app.custom_fields_project',
		'app.role',
		'app.changeset',
		'app.changesets_issue'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->CustomFieldsTracker = ClassRegistry::init('CustomFieldsTracker');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CustomFieldsTracker);

		parent::tearDown();
	}

    public function testFind() {

    }
}
