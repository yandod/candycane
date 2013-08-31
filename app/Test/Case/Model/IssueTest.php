<?php
class IssueTest extends CakeTestCase {
    protected $Issue = null;
    protected $IssueRelation = null;

    public $autoFixtures = false;
    var $fixtures = array(
      'app.issue', 'app.project', 'app.tracker', 'app.issue_status', 'app.user', 'app.version',
      'app.enumeration', 'app.issue_category', 'app.token', 'app.member', 'app.role', 'app.user_preference',
      'app.enabled_module', 'app.issue_category', 'app.time_entry', 'app.changeset', 'app.changesets_issue', 'app.attachment',
      'app.projects_tracker', 'app.custom_value', 'app.custom_field', 'app.custom_fields_project', 'app.watcher', 'app.issue_relation',
      'app.journal', 'app.journal_detail', 'app.workflow',
      'app.wiki', 'app.wiki_page', 'app.wiki_content', 'app.wiki_content_version', 'app.wiki_redirect','app.workflow',
      'app.setting',
      );

    public function startTest()
    {
        $this->Issue = ClassRegistry::init('Issue');
        $this->IssueRelation = ClassRegistry::init('IssueRelation');
    }

  function test_create() {
    $this->loadFixtures('Issue', 'Project', 'Tracker', 'IssueStatus', 'User', 'Version', 'Enumeration', 'IssueCategory', 'TimeEntry', 'Changeset', 'ChangesetsIssue', 'Watcher','CustomValue','CustomField','IssueRelation');
    $priorities = $this->Issue->Priority->get_values('IPRI');
    $this->Issue->create();
    $this->Issue->set(array(
      'project_id' => 1, 
      'tracker_id' => 1, 
      'author_id' => 3, 
      'status_id' => 1, 
      'priority_id' => $priorities[0]['Priority']['id'], 
      'subject' => 'test_create', 
      'description' => 'IssueTest#test_create', 
      'estimated_hours' => '1:30'));
    $this->assertNotEmpty($this->Issue->save());
    $this->Issue->read(null, $this->Issue->getLastInsertID());
    $this->assertEqual(1.5, $this->Issue->data['Issue']['estimated_hours']);
    $this->assertNotEmpty($this->Issue->data['Issue']['created_on']);
  }
  function test_create_minimal() {
    $this->loadFixtures('Issue', 'Project', 'Tracker', 'IssueStatus', 'User', 'Version', 'Enumeration', 'IssueCategory', 'TimeEntry', 'Changeset', 'ChangesetsIssue', 'Watcher');
    $priorities = $this->Issue->Priority->get_values('IPRI');
    $this->Issue->create();
    $this->Issue->set(array(
      'project_id' => 1,
      'tracker_id' => 1,
      'author_id' => 3, 
      'status_id' => 1, 
      'priority_id' => $priorities[0]['Priority']['id'], 
      'subject' => 'test_create'));
    $this->assertNotEmpty($this->Issue->save());
    $this->assertNull($this->Issue->data['Issue']['description']);
  }

    function test_create_extra() {
        $this->loadFixtures('Issue', 'Project', 'Tracker', 'IssueStatus', 'User', 'Version', 'Enumeration', 'IssueCategory', 'TimeEntry', 'Changeset', 'ChangesetsIssue', 'Watcher');
        $priorities = $this->Issue->Priority->get_values('IPRI');
        $this->Issue->create();
        $this->Issue->set(array(
                'project_id' => 1,
                'tracker_id' => 1,
                'author_id' => 3,
                'status_id' => 1,
                'priority_id' => $priorities[0]['Priority']['id'],
                'subject' => 'test_create',
                'posttofacebook' => '1'
            ));
        $this->assertNotEmpty($this->Issue->save());
        $this->Issue->read(null, $this->Issue->getLastInsertID());
        $this->assertNull($this->Issue->data['Issue']['description']);
        $this->assertNotEmpty($this->Issue->data['Issue']['created_on']);

    }

    function test_create_with_required_custom_field() {
    $this->loadFixtures('Issue', 'Project', 'Tracker', 'IssueStatus', 'User', 'Version', 'Enumeration', 'IssueCategory', 'TimeEntry', 'Changeset', 'CustomField', 'CustomValue', 'CustomFieldsProject', 'ChangesetsIssue', 'Watcher');
    $IssueCustomField = & ClassRegistry::init('CustomField');
    $field = $IssueCustomField->findByName('Database');

    //change field setting temporary
    $this->assertEqual($field['CustomField']['name'],'Database');
    $IssueCustomField->id = $field['CustomField']['id'];
    $this->assertNotEmpty($IssueCustomField->saveField('is_required',true));

    $this->Issue->create();
    $data = array(
      'project_id' => 1,
      'tracker_id' => 1, 
      'author_id' => 1, 
      'status_id' => 1, 
      'subject' => 'test_create', 
      'description' => 'IssueTest#test_create_with_required_custom_field');
    $this->Issue->set($data);
    
    $fields = $this->Issue->available_custom_fields();
    $this->assertTrue(in_array($field['CustomField']['id'], Set::extract('{n}.CustomField.id', $fields)));
    $this->assertEqual($fields[1]['CustomField']['name'],'Database');
    $this->assertEqual($fields[2]['CustomField']['name'],'Searchable field');
    # No value for the custom field
    $this->assertFalse($this->Issue->save());
    $this->assertTrue(array_key_exists('Database', $this->Issue->validationErrors));
    # Blank value
    $this->Issue->create();
    $this->Issue->set($data);
    $this->Issue->data['Issue']['custom_field_values'] = array($field['CustomField']['id'] => '');
    $this->assertFalse($this->Issue->save());
    $this->assertTrue(array_key_exists('Database', $this->Issue->validationErrors));
    # Invalid value
    $this->Issue->create();
    $this->Issue->set($data);
    $this->Issue->data['Issue']['custom_field_values'] = array($field['CustomField']['id'] => 'SQLServer');
    $this->assertFalse($this->Issue->save());
    $this->assertTrue(array_key_exists('Database', $this->Issue->validationErrors));
    # Valid value
    $this->Issue->create();
    $this->Issue->set($data);
    $this->Issue->data['Issue']['custom_field_values'] = array($field['CustomField']['id'] => 'PostgreSQL');
    $this->assertNotEmpty($this->Issue->save());
    $this->Issue->read(null, $this->Issue->getLastInsertID());
    $this->assertEqual('PostgreSQL', $this->Issue->data['CustomValue'][0]['value']);
  }

  function test_update_issue_with_required_custom_field() {
    $this->loadFixtures(
	    'Issue',
		'Project',
		'Tracker',
		'IssueStatus',
		'User',
		'Version',
		'Enumeration',
		'IssueCategory',
		'TimeEntry',
		'Changeset',
		'CustomField',
		'CustomValue',
		'ChangesetsIssue',
		'Watcher'
	);
    $IssueCustomField = & ClassRegistry::init('CustomField');
    $field = $IssueCustomField->findByName('Database');
    $field['CustomField']['is_required'] = true;
    $ret = $IssueCustomField->save($field,array('validate' => false, 'callbacks' => false ));
	$this->assertTrue($ret['CustomField']['is_required']);
    $this->Issue->read(null, 1);
    $data = $this->Issue->data;
    $this->assertFalse(in_array($field['CustomField']['id'], Set::extract('{n}.custom_field_id', $this->Issue->data['CustomValue'])));

    # No change to custom values, issue can be saved
    $this->Issue->data['Issue']['custom_field_values'] = array(
      $data['CustomValue'][0]['custom_field_id'] => $data['CustomValue'][0]['value']
    );
    $this->assertNotEmpty($this->Issue->save());
    # Blank value
    $this->Issue->create();
    $this->Issue->set($data);
    $this->Issue->data['Issue']['custom_field_values'] = array(
      $data['CustomValue'][0]['custom_field_id'] => $data['CustomValue'][0]['value'],
      $field['CustomField']['id'] => ''
    );
    //$this->assertFalse($this->Issue->validates());
    # Valid value
    $this->Issue->create();
    $this->Issue->set($data);
    $this->Issue->data['Issue']['custom_field_values'] = array(
      $data['CustomValue'][0]['custom_field_id'] => $data['CustomValue'][0]['value'],
      $field['CustomField']['id'] => 'PostgreSQL'
    );
	$result = $this->Issue->save();
    $this->assertNotEqual($result,false);
    $this->Issue->read(null, $this->Issue->id);
    $this->assertEqual('PostgreSQL', $this->Issue->data['CustomValue'][0]['value']);
  }
  function test_should_not_update_attributes_if_custom_fields_validation_fails() {
    $this->loadFixtures('Issue', 'Project', 'Tracker', 'IssueStatus', 'User', 'Version', 'Enumeration', 'IssueCategory', 'TimeEntry', 'Changeset', 'CustomField', 'CustomValue', 'ChangesetsIssue', 'Watcher');
    $this->Issue->read(null, 1);
    $data = $this->Issue->data;
    $IssueCustomField = & ClassRegistry::init('CustomField');
    $field = $IssueCustomField->findByName('Database');
    $fields = $this->Issue->available_custom_fields();
    $this->assertTrue(in_array($field['CustomField']['id'], Set::extract('{n}.CustomField.id', $fields)));

    $this->Issue->data['Issue']['custom_field_values'] = array(
      $data['CustomValue'][0]['custom_field_id'] => $data['CustomValue'][0]['value'],
      $field['CustomField']['id'] => 'Invalid'
    );
    $this->Issue->data['Issue']['subject'] = 'Should be not be saved';
    $this->assertFalse($this->Issue->save());

    $this->Issue->read(null, $this->Issue->id);
    $this->assertEqual('Can\'t print recipes', $this->Issue->data['Issue']['subject']);
  }
  function test_should_not_recreate_custom_values_objects_on_update() {
    $this->loadFixtures('Issue', 'Project', 'Tracker', 'IssueStatus', 'User', 'Version', 'Enumeration', 'IssueCategory', 'TimeEntry', 'Changeset', 'CustomField', 'CustomValue', 'ChangesetsIssue', 'Watcher');
    $IssueCustomField = & ClassRegistry::init('CustomField');
    $field = $IssueCustomField->findByName('Database');

    $this->Issue->read(null, 1);
    $data = $this->Issue->data;

    $this->Issue->data['Issue']['custom_field_values'] = array(
      $data['CustomValue'][0]['custom_field_id'] => $data['CustomValue'][0]['value'],
      $field['CustomField']['id'] => 'PostgreSQL'
    );
    $this->assertNotEmpty($this->Issue->save());
    $this->Issue->read(null, 1);
    $custom_value = $this->Issue->custom_value_for($field);
    $this->Issue->data['Issue']['custom_field_values'] = array(
      $data['CustomValue'][0]['custom_field_id'] => $data['CustomValue'][0]['value'],
      $field['CustomField']['id'] => 'MySQL'
    );
    $this->assertNotEmpty($this->Issue->save());
    $this->Issue->read(null, 1);
    $after = $this->Issue->custom_value_for($field);
    $this->assertEqual($custom_value['id'], $after['id']);
  }

  function test_category_based_assignment() {
    $this->loadFixtures('Issue', 'Project', 'Tracker', 'IssueStatus', 'User', 'Version', 'Enumeration', 'IssueCategory', 'TimeEntry', 'Changeset', 'ChangesetsIssue', 'Watcher');
    $priorities = $this->Issue->Priority->get_values('IPRI');
    $this->Issue->create();
    $this->Issue->set(array(
      'project_id' => 1, 
      'tracker_id' => 1, 
      'author_id' => 3, 
      'status_id' => 1, 
      'priority_id' => $priorities[0]['Priority']['id'], 
      'subject' => 'Assignment test', 
      'description' => 'Assignment test',
      'category_id' => 1));
    
    $this->assertNotEmpty($this->Issue->save());
    $this->Issue->read(null, $this->Issue->getLastInsertID());
    $category = $this->Issue->Category->read(null, 1);
    $this->assertEqual($category['Category']['assigned_to_id'], $this->Issue->data['Issue']['assigned_to_id']);
  }

  function test_copy() {
    $this->loadFixtures('Issue', 'Project', 'Tracker', 'IssueStatus', 'User', 'Version', 'Enumeration', 'IssueCategory', 'TimeEntry', 'Changeset', 'CustomField', 'CustomValue', 'ChangesetsIssue', 'Watcher', 'IssueRelation');
    $issue = $this->Issue->copy_from('1');
    $this->Issue->create();
    $this->assertNotEmpty($this->Issue->save($issue));
    $issue = $this->Issue->findById($this->Issue->getLastInsertID());
    $orig = $this->Issue->findById(1);
    $this->assertEqual($orig['Issue']['subject'], $issue['Issue']['subject']);
    $this->assertEqual($orig['Tracker'], $issue['Tracker']);
    $this->assertEqual($orig['CustomValue'][0]['value'], $issue['CustomValue'][0]['value']);
  }

  function test_should_close_duplicates() {
    $this->loadFixtures('Issue', 'Project', 'Tracker', 'IssueStatus', 'User', 'Version', 'Enumeration', 'IssueCategory', 'TimeEntry', 'Changeset', 'CustomField', 'CustomValue', 'IssueRelation', 'Journal', 'JournalDetail', 'ChangesetsIssue', 'Watcher','Token','UserPreference','Member');
    # Create 3 issues
    $priorities = $this->Issue->Priority->get_values('IPRI');
    $data = array(
      'project_id' => 1, 
      'tracker_id' => 1, 
      'author_id' => 1, 
      'status_id' => 1, 
      'priority_id' => $priorities[0]['Priority']['id'], 
      'subject' => 'Duplicates test', 
      'description' => 'Duplicates test');
    $this->Issue->create();
    $this->assertNotEmpty($this->Issue->save($data));
    $issue1 = $this->Issue->getLastInsertID();
    $this->Issue->create();
    $this->assertNotEmpty($this->Issue->save($data));
    $issue2 = $this->Issue->getLastInsertID();
    $this->Issue->create();
    $this->assertNotEmpty($this->Issue->save($data));
    $issue3 = $this->Issue->getLastInsertID();

    $IssueRelation =& ClassRegistry::init('IssueRelation');
    # 2 is a dupe of 1
    $IssueRelation->create();
    $IssueRelation->save(array('issue_from_id' => $issue2, 'issue_to_id' => $issue1, 'relation_type' => ISSUERELATION_TYPE_DUPLICATES));
    # And 3 is a dupe of 2
    $IssueRelation->create();
    $IssueRelation->save(array('issue_from_id' => $issue3, 'issue_to_id' => $issue2, 'relation_type' => ISSUERELATION_TYPE_DUPLICATES));
    # And 3 is a dupe of 1 (circular duplicates)
    $IssueRelation->create();
    $IssueRelation->save(array('issue_from_id' => $issue3, 'issue_to_id' => $issue1, 'relation_type' => ISSUERELATION_TYPE_DUPLICATES));

    $this->Issue->read(null, $issue1);
    $this->assertTrue(in_array($issue2, Set::extract('{n}.IssueFrom.id', $this->Issue->duplicates())));

    # Closing issue 1
    $user = $this->Issue->Author->find('first');
    $this->Issue->init_journal($this->Issue->data, $user['Author'], "Closing issue1");
    $status = $this->Issue->Status->find('first', array('conditions' => array('is_closed' => true)));
    $this->Issue->data['Issue']['status_id'] = $status['Status']['id'];
    $this->assertNotEmpty($this->Issue->save());

    # 2 and 3 should be also closed
    $this->Issue->read(null, $issue2);
    $this->assertTrue($this->Issue->is_closed());
    $this->Issue->read(null, $issue3);
    $this->assertTrue($this->Issue->is_closed());
  }

  function test_should_not_close_duplicated_issue() {
    $this->loadFixtures('Issue', 'Project', 'Tracker', 'IssueStatus', 'User', 'Version', 'Enumeration', 'IssueCategory', 'TimeEntry', 'Changeset', 'CustomField', 'CustomValue', 'IssueRelation', 'Journal', 'JournalDetail', 'ChangesetsIssue', 'Watcher');
    # Create 3 issues
    $priorities = $this->Issue->Priority->get_values('IPRI');
    $data = array(
      'project_id' => 1, 
      'tracker_id' => 1, 
      'author_id' => 1, 
      'status_id' => 1, 
      'priority_id' => $priorities[0]['Priority']['id'], 
      'subject' => 'Duplicates test', 
      'description' => 'Duplicates test');
    $this->Issue->create();
    $this->assertNotEmpty($this->Issue->save($data));
    $issue1 = $this->Issue->getLastInsertID();
    $this->Issue->create();
    $this->assertNotEmpty($this->Issue->save($data));
    $issue2 = $this->Issue->getLastInsertID();

    $IssueRelation =& ClassRegistry::init('IssueRelation');
    # 2 is a dupe of 1
    $IssueRelation->create();
    $result = $IssueRelation->save(array('issue_from_id' => $issue2, 'issue_to_id' => $issue1, 'relation_type' => ISSUERELATION_TYPE_DUPLICATES));
	$this->assertNotEqual($result,false);
    # 2 is a dup of 1 but 1 is not a duplicate of 2
    $this->Issue->read(null, $issue2);
    $this->assertEqual($this->Issue->duplicates(),array());

    # Closing issue 2
    $user = $this->Issue->Author->find('first');
    $this->Issue->init_journal($this->Issue->data, $user['Author'], "Closing issue2");
    $status = $this->Issue->Status->find('first', array('conditions' => array('is_closed' => true)));
    $this->Issue->data['Issue']['status_id'] = $status['Status']['id'];
    $this->assertNotEqual($this->Issue->save(), false);
    # 1 should not be also closed
    $this->Issue->read(null, $issue1);
    $this->assertFalse($this->Issue->is_closed());
  }

  function test_move_to_another_project_with_same_category() {
    $this->loadFixtures('Issue', 'Project', 'Tracker', 'IssueStatus', 'User', 'Version', 'Enumeration', 'IssueCategory', 'TimeEntry', 'Changeset', 'CustomField', 'CustomValue', 'IssueRelation', 'Journal', 'JournalDetail', 'ChangesetsIssue', 'Watcher', 'Setting');
    $Setting =& ClassRegistry::init('Setting');
    $this->Issue->read(null, 1);
    $this->assertTrue($this->Issue->move_to($Setting, $this->Issue->data, 2));
    $this->Issue->read(null, 1);
    $this->assertEqual(2, $this->Issue->data['Issue']['project_id']);
    # Category changes
    $this->assertEqual(4, $this->Issue->data['Issue']['category_id']);
    # Make sure time entries were move to the target project
    $this->assertEqual(2, $this->Issue->data['TimeEntry'][0]['project_id']);
  }

  function test_move_to_another_project_without_same_category() {
    $this->loadFixtures('Issue', 'Project', 'Tracker', 'IssueStatus', 'User', 'Version', 'Enumeration', 'IssueCategory', 'TimeEntry', 'Changeset', 'CustomField', 'CustomValue', 'IssueRelation', 'Journal', 'JournalDetail', 'ChangesetsIssue', 'Watcher', 'Setting');
    $Setting =& ClassRegistry::init('Setting');
    $this->Issue->read(null, 2);
    $this->assertTrue($this->Issue->move_to($Setting, $this->Issue->data, 2));
    $this->Issue->read(null, 2);
    $this->assertEqual(2, $this->Issue->data['Issue']['project_id']);
    # Category cleared
    $this->assertNull($this->Issue->data['Issue']['category_id']);
  }

  function test_issue_destroy() {
    $this->loadFixtures('Issue', 'Project', 'Tracker', 'IssueStatus', 'User', 'Version', 'Enumeration', 'IssueCategory', 'TimeEntry', 'Changeset', 'CustomField', 'CustomValue', 'IssueRelation', 'Journal', 'JournalDetail', 'ChangesetsIssue', 'Watcher');
    $this->Issue->read(null, 1);
    $this->Issue->delete();
    $this->assertEquals($this->Issue->read(null, 1), array());
    $this->assertEquals($this->Issue->TimeEntry->findByIssueId(1), array());
  }

  function test_overdue() {
    $this->assertTrue($this->Issue->is_overdue(array('Issue'=>array('due_date' => date('Y-m-d H:i:s', strtotime('-1 days'))))));
//    $this->assertFalse($this->Issue->is_overdue(array('Issue'=>array('due_date' => date('Y-m-d')))));
    $this->assertFalse($this->Issue->is_overdue(array('Issue'=>array('due_date' => date('Y-m-d H:i:s', strtotime('+1 days'))))));
    $this->assertFalse($this->Issue->is_overdue(array('Issue'=>array('due_date' => null))));
  }

  function testFindRssJournal() {
    $this->loadFixtures('Issue', 'Project', 'Tracker', 'IssueStatus', 'User', 'Version', 'Enumeration', 'IssueCategory', 'TimeEntry', 'Changeset', 'CustomField', 'CustomValue', 'Journal', 'JournalDetail', 'ChangesetsIssue', 'Watcher');
    $this->Issue->read(null, 1);
    $journals = $this->Issue->findRssJournal();
    $this->assertEqual(2, count($journals));
    $this->assertEqual(1, $journals[0]['Journal']['id']);
    $this->assertEqual(2, $journals[1]['Journal']['id']);
    $this->assertEqual(2, count($journals[0]['JournalDetail']));
    $this->assertEqual(0, count($journals[1]['JournalDetail']));
  }
  function testFindAllJournal() {
    $this->loadFixtures('Issue', 'Project', 'Tracker', 'IssueStatus', 'User', 'Version', 'Enumeration', 'IssueCategory', 'TimeEntry', 'Changeset', 'CustomField', 'CustomValue', 'Journal', 'JournalDetail', 'ChangesetsIssue', 'Watcher', 'Token', 'UserPreference','Member');
    $this->Issue->read(null, 1);
    $user = $this->Issue->Author->find('first');
    $journals = $this->Issue->findAllJournal($user['Author']);
    $this->assertEqual(2, count($journals));
    $this->assertEqual(1, $journals[0]['Journal']['id']);
    $this->assertEqual(2, $journals[1]['Journal']['id']);
    $this->assertEqual(2, count($journals[0]['JournalDetail']));
    $this->assertEqual(0, count($journals[1]['JournalDetail']));
  }
  function testFindManagerStatusList() {
    $this->loadFixtures('Issue', 'Project', 'Tracker', 'IssueStatus', 'User', 'Version', 'Enumeration', 'IssueCategory', 'Workflow', 'ChangesetsIssue', 'Watcher');
    $this->Issue->read(null, 1);
    $list = $this->Issue->findStatusList(1);
    $except = array('1' => 'New', '6' => 'Rejected', '2' => 'Assigned', '3' => 'Resolved', '4' => 'Feedback', '5' => 'Closed');
    $this->assertEqual($except, $list);
  }
  function testFindDeveloperStatusList() {
    $this->loadFixtures('Issue', 'Project', 'Tracker', 'IssueStatus', 'User', 'Version', 'Enumeration', 'IssueCategory', 'Workflow', 'ChangesetsIssue', 'Watcher');
    $this->Issue->read(null, 1);
    $list = $this->Issue->findStatusList(2);
    $except = array('1' => 'New', '6' => 'Rejected', '2' => 'Assigned', '3' => 'Resolved', '4' => 'Feedback', '5' => 'Closed');
    $this->assertEqual($except, $list);
  }
  function testFindReporterStatusList() {
    $this->loadFixtures('Issue', 'Project', 'Tracker', 'IssueStatus', 'User', 'Version', 'Enumeration', 'IssueCategory', 'Workflow', 'ChangesetsIssue', 'Watcher');
    $this->Issue->read(null, 1);
    $list = $this->Issue->findStatusList(3);
    $except = array('1' => 'New', '6' => 'Rejected', '2' => 'Assigned', '3' => 'Resolved', '4' => 'Feedback', '5' => 'Closed');
    $this->assertEqual($except, $list);
  }
  function testFindNonMemberStatusList() {
    $this->loadFixtures('Issue', 'Project', 'Tracker', 'IssueStatus', 'User', 'Version', 'Enumeration', 'IssueCategory', 'Workflow', 'ChangesetsIssue', 'Watcher');
    $this->Issue->read(null, 1);
    $list = $this->Issue->findStatusList(4);
    $except = array('1' => 'New');
    $this->assertEqual($except, $list);
  }
  function testFindAnonymousStatusList() {
    $this->loadFixtures('Issue', 'Project', 'Tracker', 'IssueStatus', 'User', 'Version', 'Enumeration', 'IssueCategory', 'Workflow', 'ChangesetsIssue', 'Watcher');
    $this->Issue->read(null, 1);
    $list = $this->Issue->findStatusList(5);
    $except = array('1' => 'New');
    $this->assertEqual($except, $list);
  }

  function testFindDefaultPriorities() {
    $this->loadFixtures('Issue', 'Project', 'Tracker', 'IssueStatus', 'User', 'Version', 'Enumeration', 'IssueCategory', 'ChangesetsIssue', 'Watcher');
    $this->Issue->read(null, 1);
    $default = null;
    $except = array('4' => 'Low', '5' => 'Normal', '6' => 'High', '7' => 'Urgent', '8' => 'Immediate');
    $list = $this->Issue->findPriorities($default);
    $this->assertEqual($except, $list);
    $this->assertEqual(5, $default);
  }
  function testFindPriorities() {
    $this->loadFixtures('Issue', 'Project', 'Tracker', 'IssueStatus', 'User', 'Version', 'Enumeration', 'IssueCategory','ChangesetsIssue', 'Watcher');
    $this->Issue->read(null, 1);
    $default = '7';
    $except = array('4' => 'Low', '5' => 'Normal', '6' => 'High', '7' => 'Urgent', '8' => 'Immediate');
    $list = $this->Issue->findPriorities($default);
    $this->assertEqual($except, $list);
    $this->assertEqual(7, $default);
  }

    protected function setupRelation($options = null)
    {
        $description = $options['description'];
        $due_date = $options['due_date'];
        $delay = $options['delay'];
        $relation_type = $options['relation_type'];

        $priorities = $this->Issue->Priority->get_values('IPRI');
        $date_fmt = 'Y-m-d H:i:s';
      
        $data = array(
            'project_id' => 1, 
            'tracker_id' => 1, 
            'author_id' => 1, 
            'status_id' => 1, 
            'priority_id' => $priorities[0]['Priority']['id'], 
            'subject' => 'Relation test', 
            'description' => $description,
            'due_date' => $due_date->format($date_fmt),
            );
        $this->Issue->create();
        $this->assertNotEmpty($this->Issue->save($data));
        $issue1 = $this->Issue->getLastInsertID();
        $this->Issue->create();
        $this->assertNotEmpty($this->Issue->save($data));
        $issue2 = $this->Issue->getLastInsertID();

        $this->IssueRelation->create();
        $result = $this->IssueRelation->save(array(
                                                 'issue_from_id' => $issue2,
                                                 'issue_to_id' => $issue1,
                                                 'relation_type' => $relation_type,
                                                 'delay' => $delay,
                                                 ));
        $issue_rel = $this->IssueRelation->getLastInsertID();
        $this->assertNotEqual($result, false);
        return compact('issue1', 'issue2', 'issue_rel', 'date_fmt');
    }

    public function testFindRelations()
    {
        $this->loadFixtures('Issue', 'Project', 'Tracker', 'IssueStatus', 'User', 'Version', 'Enumeration', 'IssueCategory', 'TimeEntry', 'Changeset', 'ChangesetsIssue', 'Watcher','CustomValue','CustomField','IssueRelation');
        $setupopt = array(
            'description' =>__CLASS__ . '#' . __METHOD__,
            'due_date' => new DateTime,
            'relation_type' => ISSUERELATION_TYPE_RELATES,
            'delay' => 1,
            );
        $vals = $this->setupRelation($setupopt);
        // $issue1, $issue2, $issue_rel, $date_fmt are extracted
        extract($vals);

        $issue = $this->Issue->findById($issue1);
        $this->assertNotEmpty($issue);
        $relation = $this->IssueRelation->findRelations($issue);
        $this->assertNotEmpty($relation);
        $relitem = $relation[0];
        $this->assertEqual($relitem['IssueFrom']['Issue']['id'], $issue2);
        $this->assertEqual($relitem['IssueTo']['Issue']['id'], $issue1);
        $this->assertEqual($relitem['IssueRelation']['issue_from_id'], $issue2);
        $this->assertEqual($relitem['IssueRelation']['issue_to_id'], $issue1);
        $this->assertEqual($relitem['IssueRelation']['relation_type'],
                           ISSUERELATION_TYPE_RELATES);
    }

    public function testRelationPrecedes()
    {
        $this->loadFixtures('Issue', 'Project', 'Tracker', 'IssueStatus', 'User', 'Version', 'Enumeration', 'IssueCategory', 'TimeEntry', 'Changeset', 'ChangesetsIssue', 'Watcher','CustomValue','CustomField','IssueRelation');
        $setupopt = array(
            'description' =>__CLASS__ . '#' . __METHOD__,
            'due_date' => new DateTime,
            'relation_type' => ISSUERELATION_TYPE_RELATES,
//            'relation_type' => ISSUERELATION_TYPE_PRECEDES,
            'delay' => 1,
            );
        $vals = $this->setupRelation($setupopt);
        // $issue1, $issue2, $issue_rel, $date_fmt are extracted
        extract($vals);

        $relitem = $this->IssueRelation->findById($issue_rel);
    }
}
