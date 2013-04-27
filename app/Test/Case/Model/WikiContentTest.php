<?php
App::uses('WikiContent', 'Model');

/**
 * WikiContent Test Case
 *
 */
class WikiContentTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.wiki_content', 'app.user', 'app.token', 'app.user_preference', 'app.member', 'app.project', 'app.wiki', 'app.wiki_page', 'app.wiki_redirect', 'app.issue_category', 'app.version', 'app.issue', 'app.issue_status', 'app.enumeration', 'app.tracker', 'app.workflow', 'app.time_entry', 'app.changeset', 'app.changesets_issue', 'app.enabled_module', 'app.projects_tracker', 'app.custom_field', 'app.custom_fields_project', 'app.role', 'app.wiki_content_version');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->WikiContent = ClassRegistry::init('WikiContent');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->WikiContent);

		parent::tearDown();
	}

    public function testVoid()
    {
        
    }
}
