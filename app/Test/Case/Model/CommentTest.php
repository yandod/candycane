<?php
App::uses('Comment', 'Model');

/**
 * Comment Test Case
 *
 */
class CommentTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.comment',
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
		'app.issue',
		'app.issue_status',
		'app.enumeration',
		'app.tracker',
		'app.workflow',
		'app.time_entry',
		'app.changeset',
		'app.changesets_issue',
		'app.enabled_module',
		'app.projects_tracker',
		'app.custom_field',
		'app.custom_fields_project',
		'app.role'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Comment = ClassRegistry::init('Comment');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Comment);

		parent::tearDown();
	}

    public function testFind() {

    }
}
