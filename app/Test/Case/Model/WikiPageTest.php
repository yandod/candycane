<?php
App::uses('WikiPage', 'Model');

/**
 * WikiPage Test Case
 *
 */
class WikiPageTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.wiki_page', 'app.wiki', 'app.project', 'app.issue_category', 'app.user', 'app.token', 'app.user_preference', 'app.member', 'app.role', 'app.version', 'app.issue', 'app.issue_status', 'app.enumeration', 'app.tracker', 'app.workflow', 'app.time_entry', 'app.changeset', 'app.changesets_issue', 'app.enabled_module', 'app.projects_tracker', 'app.custom_field', 'app.custom_fields_project', 'app.wiki_redirect', 'app.wiki_content', 'app.wiki_content_version');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->WikiPage = ClassRegistry::init('WikiPage');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->WikiPage);

		parent::tearDown();
	}

/**
 * testContentForVersion method
 *
 * @return void
 */
	public function testContentForVersion() {

	}
/**
 * testProject method
 *
 * @return void
 */
	public function testProject() {

	}
}
