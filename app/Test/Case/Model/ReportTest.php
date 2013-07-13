<?php
App::uses('Report', 'Model');

/**
 * Report Test Case
 *
 */
class ReportTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		//'app.report'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Report = ClassRegistry::init('Report');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Report);

		parent::tearDown();
	}

/**
 * testFindIssuesByTracker method
 *
 * @return void
 */
	public function testFindIssuesByTracker() {
	}

/**
 * testFindIssuesByVersion method
 *
 * @return void
 */
	public function testFindIssuesByVersion() {
	}

/**
 * testFindIssuesByPriority method
 *
 * @return void
 */
	public function testFindIssuesByPriority() {
	}

/**
 * testFindIssuesByCategory method
 *
 * @return void
 */
	public function testFindIssuesByCategory() {
	}

/**
 * testFindIssuesByAssignedTo method
 *
 * @return void
 */
	public function testFindIssuesByAssignedTo() {
	}

/**
 * testFindIssuesByAuthor method
 *
 * @return void
 */
	public function testFindIssuesByAuthor() {
	}

/**
 * testFindIssuesBySubproject method
 *
 * @return void
 */
	public function testFindIssuesBySubproject() {
	}

/**
 * testFindMembers method
 *
 * @return void
 */
	public function testFindMembers() {
	}

/**
 * testFindEnumurations method
 *
 * @return void
 */
	public function testFindEnumurations() {
	}

/**
 * testConvFlatArray method
 *
 * @return void
 */
	public function testConvFlatArray() {
	}

}
