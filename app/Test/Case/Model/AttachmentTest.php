<?php
App::uses('Attachment', 'Model');

/**
 * Attachment Test Case
 *
 */
class AttachmentTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.attachment',
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
		$this->Attachment = ClassRegistry::init('Attachment');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Attachment);

		parent::tearDown();
	}

/**
 * testCreateDiskFilename method
 *
 * @return void
 */
	public function testCreateDiskFilename() {
	}

/**
 * testDiskfile method
 *
 * @return void
 */
	public function testDiskfile() {
	}

/**
 * testIsVisible method
 *
 * @return void
 */
	public function testIsVisible() {
	}

/**
 * testIsDeletable method
 *
 * @return void
 */
	public function testIsDeletable() {
	}

/**
 * testIncrementDownload method
 *
 * @return void
 */
	public function testIncrementDownload() {
	}

/**
 * testProject method
 *
 * @return void
 */
	public function testProject() {
	}

/**
 * testIsImage method
 *
 * @return void
 */
	public function testIsImage() {
	}

/**
 * testIsText method
 *
 * @return void
 */
	public function testIsText() {
	}

/**
 * testIsDiff method
 *
 * @return void
 */
	public function testIsDiff() {
	}

/**
 * testSanitizeFilename method
 *
 * @return void
 */
	public function testSanitizeFilename() {
	}

/**
 * testDiskFilename method
 *
 * @return void
 */
	public function testDiskFilename() {
	}

/**
 * testDigest method
 *
 * @return void
 */
	public function testDigest() {
	}

}
