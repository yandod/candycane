<?php 
App::import('Component', 'Fetcher');

class FetcherComponentTestController extends Controller {}

class FetcherComponentTestCase extends CakeTestCase {
  var $autoFixtures = false;
  var $fixtures = array(
    'app.issue', 'app.project', 'app.tracker', 'app.issue_status', 'app.user', 'app.version',
    'app.enumeration', 'app.issue_category', 'app.token', 'app.member', 'app.role', 'app.user_preference',
    'app.enabled_module', 'app.time_entry', 'app.changeset', 'app.changesets_issue', 'app.attachment',
    'app.projects_tracker', 'app.custom_value', 'app.custom_field', 'app.watcher', 'app.journal', 'app.journal_detail',
    'app.news', 'app.comment', 'app.document',
    'app.wiki', 'app.wiki_page', 'app.wiki_content', 'app.wiki_content_version', 'app.wiki_redirect','app.workflow'
  );
  var $Controller = null;
  var $Component = null;
  var $Project = null;

  function startTest() {
    $this->loadFixtures('Project', 'Version', 'User', 'Role', 'Member', 'Issue', 'Journal', 'JournalDetail', 
              'Tracker', 'ProjectsTracker', 'IssueStatus', 'EnabledModule', 'IssueCategory', 'TimeEntry', 
              'Enumeration', 'CustomValue', 'CustomField', 'News', 'Comment', 'Document'
              );
    $this->Project =& ClassRegistry::init('Project');
    $this->Project->read(null, 1);

    $this->Controller = &new FetcherComponentTestController();
    $this->Component = &new FetcherComponent();
    
    $this->Component->initialize($this->Controller);
  }

  function test_activity_without_subprojects() {
    $User =& ClassRegistry::init('User');
    $user = $User->read(null, 6);  // User.anonymous
    $this->Component->fetch($user['User'], array('project' => $this->Project->data));
    $events = $this->Component->events(date('Y-m-d', strtotime('-30 day')), date('Y-m-d', strtotime('+1 day')));
    $this->assertNotNull($events);
    $this->assertEqual(4, count($events));
    $this->assertEqual('issue', $events[0]['type']);
    $this->assertEqual(1, $events[0]['id']);
    $this->assertEqual('issue', $events[1]['type']);
    $this->assertEqual(7, $events[1]['id']);
    $this->assertEqual('issue-note', $events[2]['type']);
    $this->assertEqual(1, $events[2]['id']);
    $this->assertEqual('issue-note', $events[3]['type']);
    $this->assertEqual(2, $events[3]['id']);
  }

  function test_activity_with_subprojects() {
    $User =& ClassRegistry::init('User');
    $user = $User->read(null, 6);  // User.anonymous
    $this->Component->fetch($user['User'], array('project' => $this->Project->data, 'with_subprojects' => 1));
    $events = $this->Component->events(date('Y-m-d', strtotime('-30 day')), date('Y-m-d', strtotime('+1 day')));
    $this->assertNotNull($events);
    $this->assertEqual(5, count($events));
    $this->assertEqual('issue', $events[0]['type']);
    $this->assertEqual(1, $events[0]['id']);
    $this->assertEqual('issue', $events[2]['type']);
    $this->assertEqual(5, $events[2]['id']);
    # subproject issue
    $this->assertEqual('issue', $events[1]['type']);
    $this->assertEqual(7, $events[1]['id']);

    $this->assertEqual('issue-note', $events[3]['type']);
    $this->assertEqual(1, $events[3]['id']);
    $this->assertEqual('issue-note', $events[4]['type']);
    $this->assertEqual(2, $events[4]['id']);
  }

  function test_global_activity_anonymous() {
    $User =& ClassRegistry::init('User');
    $user = $User->read(null, 6);  // User.anonymous
    $this->Component->fetch($user['User']);
    $events = $this->Component->events(date('Y-m-d', strtotime('-30 day')), date('Y-m-d', strtotime('+1 day')));
    $this->assertNotNull($events);
    $this->assertEqual(5, count($events));
    $this->assertEqual('issue', $events[0]['type']);
    $this->assertEqual(1, $events[0]['id']);
    $this->assertEqual('issue', $events[2]['type']);
    $this->assertEqual(5, $events[2]['id']);
    # subproject issue
    $this->assertEqual('issue', $events[1]['type']);
    $this->assertEqual(7, $events[1]['id']);

    $this->assertEqual('issue-note', $events[3]['type']);
    $this->assertEqual(1, $events[3]['id']);
    $this->assertEqual('issue-note', $events[4]['type']);
    $this->assertEqual(2, $events[4]['id']);

// TODO Message feature
//    assert events.include?(Message.find(5))
    # Issue of a private project
  }

  function test_global_activity_logged_user() {
    $User =& ClassRegistry::init('User');
    $user = $User->find_by_id_logged(2);  // manager
    $this->Component->fetch($user);
    $events = $this->Component->events(date('Y-m-d', strtotime('-30 day')), date('Y-m-d', strtotime('+1 day')));
    $this->assertNotNull($events);

    $this->assertEqual(7, count($events));
    $this->assertEqual('issue', $events[0]['type']);
    $this->assertEqual(1, $events[0]['id']);
    $this->assertEqual('issue', $events[1]['type']);
    # Issue of a private project the user belongs to
    $this->assertEqual(7, $events[1]['id']);
    $this->assertEqual('issue', $events[2]['type']);
    $this->assertEqual(4, $events[2]['id']);
    $this->assertEqual('issue', $events[3]['type']);
    # Issue of a private project the user belongs to
    $this->assertEqual(5, $events[3]['id']);
    $this->assertEqual('issue', $events[4]['type']);
    $this->assertEqual(6, $events[4]['id']);

    $this->assertEqual('issue-note', $events[5]['type']);
    $this->assertEqual(1, $events[5]['id']);
    $this->assertEqual('issue-note', $events[6]['type']);
    $this->assertEqual(2, $events[6]['id']);
  }

  function test_user_activity() {
    $User =& ClassRegistry::init('User');
    $user = $User->find_by_id_logged(2);  // manager
    $anonymous = $User->read(null, 6);  // User.anonymous
    $this->Component->fetch($anonymous['User'], array('author'=>$user));
    $events = $this->Component->events(null, null, array('limit'=>10));

    $this->assertTrue(count($events) > 0);
    $this->assertTrue(count($events) <= 10);
    $this->assertEqual(array('2'=>count($events)), array_count_values(Set::extract('{n}.author.id', $events)));
  }

  function test_news_and_files_activity() {
    $this->loadFixtures('Attachment');
    $User =& ClassRegistry::init('User');
    $user = $User->find_by_id_logged(2);  // manager
    $this->Component->fetch($user);
    $events = $this->Component->events(date('Y-m-d', strtotime('2006-07-19 0:0:0')), date('Y-m-d', strtotime('2006-07-20 0:0:0')));
    $this->assertNotNull($events);
    $this->assertEqual(5, count($events));
    $this->assertEqual('issue', $events[0]['type']);
    $this->assertEqual(2, $events[0]['id']);
    $this->assertEqual(array('controller'=>'issues', 'action'=>'show', 'id'=>2), $events[0]['url']);
    $this->assertEqual('issue', $events[1]['type']);
    $this->assertEqual(3, $events[1]['id']);
    $this->assertEqual(array('controller'=>'issues', 'action'=>'show', 'id'=>3), $events[1]['url']);
    $this->assertEqual('news', $events[2]['type']);
    $this->assertEqual(1, $events[2]['id']);
    $this->assertEqual(array('controller'=>'news', 'action'=>'show', 'id'=>1, 'project_id'=>1), $events[2]['url']);
  }

  function test_documents_activity() {
    $this->loadFixtures('Attachment');
    $User =& ClassRegistry::init('User');
    $user = $User->find_by_id_logged(2);  // manager
    $this->Component->fetch($user);
    $events = $this->Component->events(date('Y-m-d', strtotime('2007-01-27 0:0:0')), date('Y-m-d', strtotime('2007-01-28 0:0:0')));
    $this->assertNotNull($events);
    $this->assertEqual(1, count($events));
    $this->assertEqual('document', $events[0]['type']);
    $this->assertEqual(1, $events[0]['id']);
    $this->assertEqual(array('controller'=>'documents', 'action'=>'show', 'id'=>1), $events[0]['url']);
    
  }

}
