<?php 
App::import('Core', array('AppModel', 'Model'));
class ActivityProviderTestCase extends CakeTestCase {
  var $Project = null;
  var $data = null;
  var $fixtures = array('app.projects', 'app.versions', 'app.users', 'app.roles', 'app.members', 
        'app.issues', 'app.journals', 'app.journal_details', 'app.trackers', 'app.projects_trackers',
        'app.issue_statuses', 'app.enabled_modules', 'app.enumerations', 'app.boards', 'app.messages');

  function startTest() {
    $this->Project =& ClassRegistry::init('Project');
    $this->data = $this->Project->read(null, 1);
  }

  function test_activity_without_subprojects() {
    $User =& ClassRegistry::init('User');
    $user = $User->read(null, 6);

    $events = $this->Project->find_events('projects', $user['User'], false, false, array('project' => $this->data));
    $this->assertNotNull($events);
    e(pr($events));
//    $this->assertTrue($events.include?(Issue.find(1))
//    assert !events.include?(Issue.find(4))
//    # subproject issue
//    assert !events.include?(Issue.find(5))
  }
  
/*
  def test_activity_with_subprojects
    events = find_events(User.anonymous, :project => @project, :with_subprojects => 1)
    assert_not_nil events

    assert events.include?(Issue.find(1))
    # subproject issue
    assert events.include?(Issue.find(5))
  end

  def test_global_activity_anonymous
    events = find_events(User.anonymous)
    assert_not_nil events

    assert events.include?(Issue.find(1))
    assert events.include?(Message.find(5))
    # Issue of a private project
    assert !events.include?(Issue.find(4))
  end

  def test_global_activity_logged_user
    events = find_events(User.find(2)) # manager
    assert_not_nil events

    assert events.include?(Issue.find(1))
    # Issue of a private project the user belongs to
    assert events.include?(Issue.find(4))
  end

  def test_user_activity
    user = User.find(2)
    events = Redmine::Activity::Fetcher.new(User.anonymous, :author => user).events(nil, nil, :limit => 10)

    assert(events.size > 0)
    assert(events.size <= 10)
    assert_nil(events.detect {|e| e.event_author != user})
  end

  private

  def find_events(user, options={})
    Redmine::Activity::Fetcher.new(user, options).events(Date.today - 30, Date.today + 1)
  end
*/

}
?>