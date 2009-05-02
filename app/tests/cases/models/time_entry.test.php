<?php 
class TimeEntryTestCase extends CakeTestCase {
  var $TimeEntry = null;
  var $autoFixtures = false;
  var $fixtures = array(
      'app.issue', 'app.project', 'app.tracker', 'app.issue_status', 'app.user', 'app.version',
      'app.enumeration', 'app.issue_category', 'app.token', 'app.member', 'app.role', 'app.user_preference',
      'app.issue_category', 'app.time_entry', 'app.changeset', 'app.changesets_issue', 'app.attachment',
      'app.projects_tracker', 'app.custom_value', 'app.custom_field', 'app.watcher'
  );

  function startTest() {
    $this->loadFixtures('Issue', 'User', 'Project', 'TimeEntry');
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

}
?>