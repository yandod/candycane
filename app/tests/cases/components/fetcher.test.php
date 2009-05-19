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
    'app.news', 'app.comment', 'app.document', 'app.wiki_content_version',
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
    $user = $User->find_by_id_logged(2);  // User.anonymous
    $this->Component->fetch($user, array('project' => $this->Project->data));
    $events = $this->Component->events(date('Y-m-d', strtotime('-30 day')), date('Y-m-d', strtotime('+1 day')));
    $this->assertNotNull($events);
    
    e(pr($events));

/*
    assert events.include?(Issue.find(1))
    assert !events.include?(Issue.find(4))
    # subproject issue
    assert !events.include?(Issue.find(5))
*/
  }


}
