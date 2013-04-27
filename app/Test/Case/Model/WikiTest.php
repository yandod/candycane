<?php
App::uses('Wiki', 'Model');

/**
 * Wiki Test Case
 *
 */
class WikiTestCase extends CakeTestCase {

    /**
     * @var Wiki
     */
    public $Wiki;


/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.wiki', 'app.project', 'app.issue_category', 'app.user', 'app.token', 'app.user_preference', 'app.member', 'app.role', 'app.version', 'app.issue', 'app.issue_status', 'app.enumeration', 'app.tracker', 'app.workflow', 'app.time_entry', 'app.changeset', 'app.changesets_issue', 'app.enabled_module', 'app.projects_tracker', 'app.custom_field', 'app.custom_fields_project', 'app.wiki_page', 'app.wiki_content', 'app.wiki_content_version', 'app.wiki_redirect');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Wiki = ClassRegistry::init('Wiki');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Wiki);

		parent::tearDown();
	}

/**
 * testFindByProjectId method
 *
 * @return void
 */
	public function testFindByProjectId() {
        $data = $this->Wiki->findByProjectId(1);
        $this->assertEqual($data['Wiki']['id'], 1);

        foreach ($data['WikiPage'] as $row) {
            $this->assertEqual($row['wiki_id'], 1);
        }
        //$this->assertEqual($data['WikiPage'][1]['title'], "日本語ページ");
    }

    /**
 * testFindOrNewPage method
 *
 * @return void
 */
	public function testFindOrNewPage() {

	}
/**
 * testFindPage method
 *
 * @return void
 */
	public function testFindPage() {

	}
/**
 * testTitleize method
 *
 * @return void
 */
	public function testTitleize() {

	}
}
