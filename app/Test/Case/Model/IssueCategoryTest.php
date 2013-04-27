<?php
App::uses('IssueCategory', 'Model');

/**
 * IssueCategory Test Case
 *
 */
class IssueCategoryTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
    public $fixtures = array('app.issue_category', 'app.user', 'app.token', 'app.user_preference', 'app.member', 'app.project', 'app.wiki', 'app.wiki_page', 'app.wiki_content', 'app.wiki_content_version', 'app.wiki_redirect', 'app.version', 'app.issue', 'app.issue_status', 'app.enumeration', 'app.tracker', 'app.workflow', 'app.time_entry', 'app.changeset', 'app.changesets_issue', 'app.enabled_module', 'app.projects_tracker', 'app.custom_field', 'app.custom_fields_project', 'app.role');

/**
 * setUp method
 *
 * @return void
 */
    public function setUp() {
        parent::setUp();
        $this->IssueCategory = ClassRegistry::init('IssueCategory');
        $this->Issue = ClassRegistry::init('Issue');
    }

/**
 * tearDown method
 *
 * @return void
 */
    public function tearDown() {
        unset($this->IssueCategory);

        parent::tearDown();
    }

/**
 * testDelWithReassgin method
 *
 * @return void
 */
    public function testDelWithReassgin() {
        $id = 1;
        $reassginId = 2;

        $this->assertEqual(1, $this->__countIssueCategory($id));
        $this->assertEqual(1, $this->__countIssue($id));
        $this->assertEqual(1, $this->__countIssueCategory($reassginId));
        $this->assertEqual(0, $this->__countIssue($reassginId));

        $this->IssueCategory->del_with_reassgin($id, $reassginId);

        $this->assertEqual(0, $this->__countIssueCategory($id));
        $this->assertEqual(0, $this->__countIssue($id));
        $this->assertEqual(1, $this->__countIssueCategory($reassginId));
        $this->assertEqual(1, $this->__countIssue($reassginId));
    }

    private function __countIssueCategory($id)
    {
        $belongsTo = array_keys($this->IssueCategory->belongsTo);
        $this->IssueCategory->unbindModel(array('belongsTo' => $belongsTo));

        $conditions = array(
            'id' => $id,
        );

        return $this->IssueCategory->find('count', compact('conditions'));
    }

    private function __countIssue($id)
    {
        $belongsTo = array_keys($this->Issue->belongsTo);
        $this->Issue->unbindModel(array('belongsTo' => $belongsTo));

        $conditions = array(
            'category_id' => $id,
        );

        return $this->Issue->find('count', compact('conditions'));
    }
}
