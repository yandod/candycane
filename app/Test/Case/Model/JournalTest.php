<?php
App::uses('Journal', 'Model');

/**
 * Journal Test Case
 *
 */
class JournalTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.journal', 'app.issue', 'app.issue_status', 'app.user', 'app.token', 'app.user_preference', 'app.member', 'app.project', 'app.wiki', 'app.wiki_page', 'app.wiki_content', 'app.wiki_content_version', 'app.wiki_redirect', 'app.issue_category', 'app.version', 'app.enabled_module', 'app.time_entry', 'app.enumeration', 'app.tracker', 'app.workflow', 'app.projects_tracker', 'app.custom_field', 'app.custom_fields_project', 'app.role', 'app.changeset', 'app.changesets_issue', 'app.journal_detail', 'app.custom_value', 'app.watcher', 'app.issue_relation');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Journal = ClassRegistry::init('Journal');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Journal);

		parent::tearDown();
	}

/**
 * testIsEditableBy method
 *
 * @return void
 */
 	public function testIsEditableBy() {
    # admin user
    $usr = $this->Journal->User->findById(1);
		  $usr['id'] = 1;
		  $usr['admin'] = true;
		  $usr['logged'] = true;
    $this->Journal->set($this->Journal->findByJournalizedId(1));
    $this->assertTrue($this->Journal->is_editable_by($usr));
		  
    # manager 
    $usr['admin'] = false;
		  $usr['status'] = "2";
		  $usr['memberships'][0]['project_id'] = '1';
		  $usr['memberships'][0]['role_id'] = '1';
    $this->assertTrue($this->Journal->is_editable_by($usr));
    
    # anonymous
		  $usr['memberships'][0]['project_id'] = '2';
		  $usr['memberships'][0]['role_id'] = '1';
    $this->assertFalse($this->Journal->is_editable_by($usr));
 	}
  
/**
 * testSaveAll method
 *
 * @return void
 */
  public function testSaveAll() {
    $this->assertTrue($this->Journal->saveAll($journal = $this->Journal->findByJournalizedId(1)));
    
    unset($journal['Journal']['notes']);
    $this->Journal->set($journal);
    $this->assertTrue($this->Journal->saveAll());
    
    $journal['Journal']['notes'] = 'test';
    unset($journal['JournalDetail']);
    $this->Journal->set($journal);
    $this->assertTrue($this->Journal->saveAll());
    
    unset($journal['Journal']['notes']);
    $this->Journal->set($journal);
    $this->assertFalse($this->Journal->saveAll());
  }
}
