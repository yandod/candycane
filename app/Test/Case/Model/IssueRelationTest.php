<?php
App::uses('IssueRelation', 'Model');

/**
 * IssueRelation Test Case
 *
 */
class IssueRelationTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.issue_relation',
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
		'app.tracker',
		'app.workflow',
		'app.projects_tracker',
		'app.custom_field',
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
		$this->IssueRelation = ClassRegistry::init('IssueRelation');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->IssueRelation);

		parent::tearDown();
	}

/**
 * testExistsIssue method
 *
 * @return void
 */
	public function testExistsIssue() {
	}

/**
 * testSameId method
 *
 * @return void
 */
	public function testSameId() {
	}

/**
 * testSameProject method
 *
 * @return void
 */
	public function testSameProject() {
	}

/**
 * testCircularDependency method
 *
 * @return void
 */
	public function testCircularDependency() {
	}

/**
 * testAllDependentIssues method
 *
 * @return void
 */
	public function testAllDependentIssues() {
	}

/**
 * testSetIssueToDates method
 *
 * @return void
 */
	public function testSetIssueToDates() {
	}

/**
 * testSuccessorSoonestStart method
 *
 * @return void
 */
	public function testSuccessorSoonestStart() {
	}

/**
 * testFindRelations method
 *
 * @return void
 */
	public function testFindRelations() {
	}

}
