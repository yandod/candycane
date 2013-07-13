<?php
App::uses('CustomFieldsProject', 'Model');

/**
 * CustomFieldsProject Test Case
 *
 */
class CustomFieldsProjectTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.custom_fields_project',
		'app.custom_field',
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
		'app.version',
		'app.issue',
		'app.issue_status',
		'app.enumeration',
		'app.tracker',
		'app.workflow',
		'app.time_entry',
		'app.changeset',
		'app.changesets_issue',
		'app.enabled_module',
		'app.projects_tracker'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->CustomFieldsProject = ClassRegistry::init('CustomFieldsProject');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CustomFieldsProject);

		parent::tearDown();
	}

    public function testFind() {

    }
}
