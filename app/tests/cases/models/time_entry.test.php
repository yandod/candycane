<?php 
class TimeEntryTestCase extends CakeTestCase {
  var $TimeEntry = null;
  var $autoFixtures = false;
  var $fixtures = array(
      'app.issue', 'app.project', 'app.tracker', 'app.issue_status', 'app.user', 'app.version',
      'app.enumeration', 'app.issue_category', 'app.token', 'app.member', 'app.role', 'app.user_preference',
      'app.issue_category', 'app.time_entry', 'app.changeset', 'app.changesets_issue', 'app.attachment',
      'app.projects_tracker', 'app.custom_value', 'app.custom_field', 'app.watcher', 'app.enabled_module'
  );

  function startTest() {
    $this->loadFixtures('Issue', 'User', 'Project', 'TimeEntry', 'EnabledModule');
    $this->TimeEntry =& ClassRegistry::init('TimeEntry');
  }

  function test_hours_format() {
    $assertions = array(
                   "2"      => 2.0,
                   "21.1"   => 21.1,
                   "2,1"    => 2.1,
                   "1,5h"   => 1.5,
                   "7:12"   => 7.2,
                   "10h"    => 10.0,
                   "10 h"   => 10.0,
                   "45m"    => 0.75,
                   "45 m"   => 0.75,
                   "3h15"   => 3.25,
                   "3h 15"  => 3.25,
                   "3 h 15"   => 3.25,
                   "3 h 15m"  => 3.25,
                   "3 h 15 m" => 3.25,
                   "3 hours"  => 3.0,
                   "12min"    => 0.2,
                   "120min"    => 2,
                  );

    foreach($assertions as $k => $v) {
      $this->TimeEntry->read(null, 1);
      $this->TimeEntry->data['TimeEntry']['hours'] = $k;
      $this->TimeEntry->validates();
      
      $this->assertEqual($v, $this->TimeEntry->data['TimeEntry']['hours']);
    }
  }
  function test_spent_on() {
    $this->TimeEntry->read(null, 1);
    $this->TimeEntry->data['TimeEntry']['spent_on'] = '2009-04-17';
    $this->TimeEntry->validates();

    $this->assertEqual(2009, $this->TimeEntry->data['TimeEntry']['tyear']);
    $this->assertEqual(4, $this->TimeEntry->data['TimeEntry']['tmonth']);
    $this->assertEqual(16, $this->TimeEntry->data['TimeEntry']['tweek']);
  }
  
  function test_retrieve_date_range_all() {
    $time = strtotime('2007-04-15');
    $user = $this->TimeEntry->User->read(null, 1);
    $project = $this->TimeEntry->Project->read(null, 1);
    $ret = $this->TimeEntry->retrieve_date_range('1', 'all', $user['User'], $project, array('from'=>'2007-03-23', 'to'=>'2007-04-22'), $time);
    $this->assertEqual('2007-03-12', $ret['from']);
    $this->assertEqual('2007-04-21', $ret['to']);
  }
  function test_retrieve_date_range_today() {
    $time = strtotime('2007-04-15');
    $user = $this->TimeEntry->User->read(null, 1);
    $project = $this->TimeEntry->Project->read(null, 1);
    $ret = $this->TimeEntry->retrieve_date_range('1', 'today', $user['User'], $project, array('from'=>'2007-03-23', 'to'=>'2007-04-22'), $time);
    $this->assertEqual('2007-04-15', $ret['from']);
    $this->assertEqual('2007-04-15', $ret['to']);
  }
  function test_retrieve_date_range_yesterday() {
    $time = strtotime('2007-04-15');
    $user = $this->TimeEntry->User->read(null, 1);
    $project = $this->TimeEntry->Project->read(null, 1);
    $ret = $this->TimeEntry->retrieve_date_range('1', 'yesterday', $user['User'], $project, array('from'=>'2007-03-23', 'to'=>'2007-04-22'), $time);
    $this->assertEqual('2007-04-14', $ret['from']);
    $this->assertEqual('2007-04-14', $ret['to']);
  }
  function test_retrieve_date_range_current_week() {
    $time = strtotime('2007-04-18');
    $user = $this->TimeEntry->User->read(null, 1);
    $project = $this->TimeEntry->Project->read(null, 1);
    $ret = $this->TimeEntry->retrieve_date_range('1', 'current_week', $user['User'], $project, array('from'=>'2007-03-23', 'to'=>'2007-04-22'), $time);
    $this->assertEqual('2007-04-16', $ret['from']);
    $this->assertEqual('2007-04-22', $ret['to']);

    $time = strtotime('2007-04-16');
    $user = $this->TimeEntry->User->read(null, 1);
    $project = $this->TimeEntry->Project->read(null, 1);
    $ret = $this->TimeEntry->retrieve_date_range('1', 'current_week', $user['User'], $project, array('from'=>'2007-03-23', 'to'=>'2007-04-22'), $time);
    $this->assertEqual('2007-04-16', $ret['from']);
    $this->assertEqual('2007-04-22', $ret['to']);

    $time = strtotime('2007-04-22');
    $user = $this->TimeEntry->User->read(null, 1);
    $project = $this->TimeEntry->Project->read(null, 1);
    $ret = $this->TimeEntry->retrieve_date_range('1', 'current_week', $user['User'], $project, array('from'=>'2007-03-23', 'to'=>'2007-04-22'), $time);
    $this->assertEqual('2007-04-16', $ret['from']);
    $this->assertEqual('2007-04-22', $ret['to']);
  }
  function test_retrieve_date_range_last_week() {
    $time = strtotime('2007-04-25');
    $user = $this->TimeEntry->User->read(null, 1);
    $project = $this->TimeEntry->Project->read(null, 1);
    $ret = $this->TimeEntry->retrieve_date_range('1', 'last_week', $user['User'], $project, array('from'=>'2007-03-23', 'to'=>'2007-04-22'), $time);
    $this->assertEqual('2007-04-16', $ret['from']);
    $this->assertEqual('2007-04-22', $ret['to']);

    $time = strtotime('2007-04-23');
    $user = $this->TimeEntry->User->read(null, 1);
    $project = $this->TimeEntry->Project->read(null, 1);
    $ret = $this->TimeEntry->retrieve_date_range('1', 'last_week', $user['User'], $project, array('from'=>'2007-03-23', 'to'=>'2007-04-22'), $time);
    $this->assertEqual('2007-04-16', $ret['from']);
    $this->assertEqual('2007-04-22', $ret['to']);

    $time = strtotime('2007-04-29');
    $user = $this->TimeEntry->User->read(null, 1);
    $project = $this->TimeEntry->Project->read(null, 1);
    $ret = $this->TimeEntry->retrieve_date_range('1', 'last_week', $user['User'], $project, array('from'=>'2007-03-23', 'to'=>'2007-04-22'), $time);
    $this->assertEqual('2007-04-16', $ret['from']);
    $this->assertEqual('2007-04-22', $ret['to']);
  }
  function test_retrieve_date_range_7_days() {
    $time = strtotime('2007-04-18');
    $user = $this->TimeEntry->User->read(null, 1);
    $project = $this->TimeEntry->Project->read(null, 1);
    $ret = $this->TimeEntry->retrieve_date_range('1', '7_days', $user['User'], $project, array('from'=>'2007-03-23', 'to'=>'2007-04-22'), $time);
    $this->assertEqual('2007-04-11', $ret['from']);
    $this->assertEqual('2007-04-18', $ret['to']);
  }
  function test_retrieve_date_range_current_month() {
    $time = strtotime('2007-04-18');
    $user = $this->TimeEntry->User->read(null, 1);
    $project = $this->TimeEntry->Project->read(null, 1);
    $ret = $this->TimeEntry->retrieve_date_range('1', 'current_month', $user['User'], $project, array('from'=>'2007-03-23', 'to'=>'2007-04-22'), $time);
    $this->assertEqual('2007-04-01', $ret['from']);
    $this->assertEqual('2007-04-30', $ret['to']);
  }
  function test_retrieve_date_range_last_month() {
    $time = strtotime('2007-04-18');
    $user = $this->TimeEntry->User->read(null, 1);
    $project = $this->TimeEntry->Project->read(null, 1);
    $ret = $this->TimeEntry->retrieve_date_range('1', 'last_month', $user['User'], $project, array('from'=>'2007-03-23', 'to'=>'2007-04-22'), $time);
    $this->assertEqual('2007-03-01', $ret['from']);
    $this->assertEqual('2007-03-31', $ret['to']);
  }
  function test_retrieve_date_range_30_days() {
    $time = strtotime('2007-04-18');
    $user = $this->TimeEntry->User->read(null, 1);
    $project = $this->TimeEntry->Project->read(null, 1);
    $ret = $this->TimeEntry->retrieve_date_range('1', '30_days', $user['User'], $project, array('from'=>'2007-03-23', 'to'=>'2007-04-22'), $time);
    $this->assertEqual('2007-03-19', $ret['from']);
    $this->assertEqual('2007-04-18', $ret['to']);
  }
  function test_retrieve_date_range_current_year() {
    $time = strtotime('2007-04-18');
    $user = $this->TimeEntry->User->read(null, 1);
    $project = $this->TimeEntry->Project->read(null, 1);
    $ret = $this->TimeEntry->retrieve_date_range('1', 'current_year', $user['User'], $project, array('from'=>'2007-03-23', 'to'=>'2007-04-22'), $time);
    $this->assertEqual('2007-01-01', $ret['from']);
    $this->assertEqual('2007-12-31', $ret['to']);
  }
  function test_retrieve_date_range_free_period() {
    $time = strtotime('2007-04-18');
    $user = $this->TimeEntry->User->read(null, 1);
    $project = $this->TimeEntry->Project->read(null, 1);
    $ret = $this->TimeEntry->retrieve_date_range('2', 'current_year', $user['User'], $project, array('from'=>'2007-04-01', 'to'=>'2007-04-10'), $time);
    $this->assertEqual('2007-04-01', $ret['from']);
    $this->assertEqual('2007-04-10', $ret['to']);
  }

}
?>