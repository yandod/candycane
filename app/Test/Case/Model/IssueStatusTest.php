<?php 
class IssueStatusTest extends CakeTestCase {
  var $IssueStatus = null;
  var $autoFixtures = false;
  var $fixtures = array(
      'app.issue', 'app.project', 'app.tracker', 'app.issue_status', 'app.user', 'app.version',
      'app.enumeration', 'app.issue_category', 'app.token', 'app.member', 'app.role', 'app.user_preference',
      'app.enabled_module', 'app.issue_category', 'app.time_entry', 'app.changeset', 'app.changesets_issue', 'app.attachment',
      'app.projects_tracker', 'app.custom_value', 'app.custom_field', 'app.watcher',
      'app.wiki', 'app.wiki_page', 'app.wiki_content', 'app.wiki_content_version', 'app.wiki_redirect','app.workflow'
  );

  function startTest() {
    $this->IssueStatus =& ClassRegistry::init('IssueStatus');
  }

  function test_create() {
    $this->loadFixtures('IssueStatus');
    $this->IssueStatus->create();
    $this->IssueStatus->set(array('name' => "Assigned"));
    $this->assertFalse($this->IssueStatus->save());
    # status name uniqueness
    $this->assertEqual(1, count($this->IssueStatus->validationErrors));

    $this->IssueStatus->set(array('name' => "  "));
    $this->assertFalse($this->IssueStatus->save());
    $this->assertEqual(1, count($this->IssueStatus->validationErrors));

    $this->IssueStatus->create();
    $this->IssueStatus->set(array('name' => "新規"));
    $this->assertNotEmpty($this->IssueStatus->save());

    $this->IssueStatus->create();
    $this->IssueStatus->set(array('name' => "Test Status"));
    $this->assertNotEmpty($this->IssueStatus->save());
    $this->IssueStatus->read(null, $this->IssueStatus->getLastInsertID());
    $this->assertEquals('0',$this->IssueStatus->data['IssueStatus']['is_default']);
  }

  function test_destroy() {
    $this->loadFixtures('Issue', 'Project', 'Tracker', 'IssueStatus', 'User', 'Version',
      'Enumeration', 'IssueCategory', 'Token', 'Member', 'Role', 'UserPreference',
      'IssueCategory', 'TimeEntry', 'Changeset', 'ChangesetsIssue', 'Attachment',
      'ProjectsTracker', 'CustomValue', 'CustomField', 'Watcher'
    );
    $count_before = $this->IssueStatus->find('count');
    $this->IssueStatus->read(null, 3);
    $this->assertTrue($this->IssueStatus->delete());
    $this->assertEqual($count_before - 1, $this->IssueStatus->find('count'));
  }

  function test_destroy_status_in_use() {
    $this->loadFixtures('Issue', 'Project', 'Tracker', 'IssueStatus', 'User', 'Version',
      'Enumeration', 'IssueCategory', 'Token', 'Member', 'Role', 'UserPreference',
      'IssueCategory', 'TimeEntry', 'Changeset', 'ChangesetsIssue', 'Attachment',
      'ProjectsTracker', 'CustomValue', 'CustomField', 'Watcher'
    );
    # Status assigned to an Issue
    $Issue =& ClassRegistry::init('Issue');
    $issue = $Issue->findById(1);
    $this->assertFalse($this->IssueStatus->delete($issue['Issue']['status_id']));
  }

  function test_default() {
    $this->loadFixtures('IssueStatus');
    $status = $this->IssueStatus->findDefault();
    $this->assertEqual(1, count($status));
  }

  function test_change_default() {
    $this->loadFixtures('IssueStatus');
    $this->IssueStatus->read(null, 2);
    $this->assertEquals('0',$this->IssueStatus->data['IssueStatus']['is_default']);
    $this->IssueStatus->data['IssueStatus']['is_default'] = true;
    $this->assertNotEmpty($this->IssueStatus->save());
    $this->IssueStatus->read(null, 2);

    $this->assertEquals('1',$this->IssueStatus->data['IssueStatus']['is_default']);
    $this->IssueStatus->read(null, 1);
    $this->assertEquals('0',$this->IssueStatus->data['IssueStatus']['is_default']);
  }

  function test_reorder_should_not_clear_default_status() {
    $this->loadFixtures('IssueStatus');
    $status = $this->IssueStatus->findDefault();
    $this->IssueStatus->read(null, key($status));
    $this->IssueStatus->move_to_bottom();
    $this->IssueStatus->read(null, key($status));
    $this->assertEquals('1',$this->IssueStatus->data['IssueStatus']['is_default']);
  }

}
