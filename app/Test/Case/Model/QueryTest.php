<?php
App::uses('Query', 'Model');

/**
 * Query Test Case
 *
 */
class QueryTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.query',
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
		$this->Query = ClassRegistry::init('Query');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Query);

		parent::tearDown();
	}

/**
 * testAvailableFilters method
 *
 * @return void
 */
	public function testAvailableFilters() {
	}

/**
 * testShowFilters method
 *
 * @return void
 */
	public function testShowFilters() {
	}

/**
 * testValidateFilters method
 *
 * @return void
 */
	public function testValidateFilters() {
	}

/**
 * testGetFilterCond method
 *
 * @return void
 */
	public function testGetFilterCond() {
	}

/**
 * testAddFilter method
 *
 * @return void
 */
	public function testAddFilter() {
	}

/**
 * testAddShortFilter method
 *
 * @return void
 */
	public function testAddShortFilter() {
	}

/**
 * testHasFilter method
 *
 * @return void
 */
	public function testHasFilter() {
	}

/**
 * testOperatorFor method
 *
 * @return void
 */
	public function testOperatorFor() {
	}

/**
 * testValuesFor method
 *
 * @return void
 */
	public function testValuesFor() {
	}

/**
 * testLabelFor method
 *
 * @return void
 */
	public function testLabelFor() {
	}

/**
 * testAvailableColumns method
 *
 * @return void
 */
	public function testAvailableColumns() {
	}

/**
 * testProjectStatement method
 *
 * @return void
 */
	public function testProjectStatement() {
	}

/**
 * testDateRangeClause method
 *
 * @return void
 */
	public function testDateRangeClause() {
	}

/**
 * testGetFilters method
 *
 * @return void
 */
	public function testGetFilters() {
	}

}
