<?php 
App::import('Core', array('AppModel', 'Model'));
class ActivityProviderTestCase extends CakeTestCase {
  var $Issue = null;
  var $data = null;
  var $fixtures = array('app.project', 'app.version', 'app.user', 'app.role', 'app.member', 
        'app.issue', 'app.journal', 'app.journal_detail', 'app.tracker', 'app.projects_tracker',
        'app.issue_status', 'app.enabled_module', 'app.enumeration', 'app.board', 'app.message');

  function startTest() {
    $this->Issue =& ClassRegistry::init('Issue');
    $this->data = $this->Issue->read(null, 1);
  }

  function test_activity_without_subprojects() {
    $User =& ClassRegistry::init('User');
    $user = $User->read(null, 6);

    $events = $this->Issue->find_events('projects', $user['User'], false, false, array('project' => $this->data));
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