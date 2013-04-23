<?php 
class WatcherTest extends CakeTestCase {
  var $Issue = null;
  var $autoFixtures = false;
  var $fixtures = array(
      'app.issue', 'app.project', 'app.tracker', 'app.issue_status', 'app.user', 'app.version',
      'app.enumeration', 'app.issue_category', 'app.token', 'app.member', 'app.role', 'app.user_preference',
      'app.enabled_module', 'app.issue_category', 'app.time_entry', 'app.changeset', 'app.changesets_issue', 'app.attachment',
      'app.projects_tracker', 'app.custom_value', 'app.custom_field', 'app.watcher',
      'app.wiki', 'app.wiki_page', 'app.wiki_content', 'app.wiki_content_version', 'app.wiki_redirect','app.workflow'
  );
  var $user;
  var $issue;

  function startTest() {
    $this->loadFixtures('Issue', 'User', 'UserPreference','Watcher', 'Token', 'Member', 'IssueStatus', 'IssueCategory', 'Tracker', 'TimeEntry', 'Changeset', 'ChangesetsIssue', 'CustomValue', 'CustomField', 'Version', 'Enumeration', 'Project');
    $this->Issue =& ClassRegistry::init('Issue');
    $User =& ClassRegistry::init('User');
    $this->user = $User->read(null, 1);
    $this->issue = $this->Issue->read(null, 1);
  }
  function test_watch() {
    $this->assertNotEmpty($this->Issue->add_watcher($this->user));
    $this->issue = $this->Issue->read(null, 1);
    $this->assertTrue(in_array($this->user['User']['id'], Set::extract('{n}.User.id', $this->issue['Watcher'])));
  }

  function test_cant_watch_twice() {
    $this->assertNotEmpty($this->Issue->add_watcher($this->user));
    $this->assertFalse($this->Issue->add_watcher($this->user));
  }

  function test_watched_by() {
    $this->assertNotEmpty($this->Issue->add_watcher($this->user));
    $this->issue = $this->Issue->read(null, 1);
    $this->assertTrue($this->Issue->is_watched_by($this->user));
    $watcher = $this->Issue->watched_by($this->user);
    $this->assertEqual('Issue', $watcher['Watcher']['watchable_type']);
    $this->assertEqual('1', $watcher['Watcher']['watchable_id']);
  }

  function test_recipients() {
    $Watcher =& ClassRegistry::init('Watcher');
    $Watcher->deleteAll('1 = 1');
    $this->issue = $this->Issue->read(null, 1);

    $this->assertEqual(0, count($this->Issue->watcher_recipients()));
    $this->assertNotEmpty($this->Issue->add_watcher($this->user));

    $User =& ClassRegistry::init('User');
    $this->user['User']['mail_notification'] = true;
    $User->save($this->user);
    $this->issue = $this->Issue->read(null, 1);
    $this->assertTrue(in_array($this->user['User']['mail'], $this->Issue->watcher_recipients()));

    $this->user['User']['mail_notification'] = false;
    $User->save($this->user);
    $this->issue = $this->Issue->read(null, 1);
    $this->assertTrue(in_array($this->user['User']['mail'], $this->Issue->watcher_recipients()));
  }

  function test_unwatch() {
    $this->assertNotEmpty($this->Issue->add_watcher($this->user));
    $this->issue = $this->Issue->read(null, 1);
    $this->assertEqual(1, $this->Issue->remove_watcher($this->user));  
  }


}
