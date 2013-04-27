<?php 
class TimeEntryTest extends CakeTestCase {
  var $TimeEntry = null;
  var $autoFixtures = false;
  var $fixtures = array(
      'app.issue', 'app.project', 'app.tracker', 'app.issue_status', 'app.user', 'app.version',
      'app.enumeration', 'app.issue_category', 'app.token', 'app.member', 'app.role', 'app.user_preference',
      'app.issue_category', 'app.time_entry', 'app.changeset', 'app.changesets_issue', 'app.attachment',
      'app.projects_tracker', 'app.custom_value', 'app.custom_field', 'app.custom_fields_project', 'app.watcher', 'app.enabled_module',
      'app.wiki', 'app.wiki_page', 'app.wiki_content', 'app.wiki_content_version', 'app.wiki_redirect','app.workflow', 'app.setting'
  );

  function startTest() {
    $this->loadFixtures('Issue', 'User', 'Project', 'TimeEntry', 'EnabledModule', 'Wiki', 'IssueCategory', 'Tracker', 'ProjectsTracker', 'Member', 'Changeset', 'ChangesetsIssue', 'Token', 'UserPreference', 'CustomField', 'CustomFieldsProject', 'CustomValue', 'Version', 'Enumeration', 'IssueStatus', 'Setting');
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
    $this->assertEqual('2007-03-11', $ret['from']);
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

  function test_report_available_criterias() {
    $this->loadFixtures('CustomField', 'CustomValue', 'CustomFieldsProject');
    $project = $this->TimeEntry->Project->read(null, 1);
    $available_criterias = $this->TimeEntry->report_available_criterias($project);
    $this->assertEqual(10, count($available_criterias));
    $this->assertTrue(array_key_exists('project', $available_criterias));
    $this->assertTrue(array_key_exists('version', $available_criterias));
    $this->assertTrue(array_key_exists('category', $available_criterias));
    $this->assertTrue(array_key_exists('member', $available_criterias));
    $this->assertTrue(array_key_exists('tracker', $available_criterias));
    $this->assertTrue(array_key_exists('activity', $available_criterias));
    $this->assertTrue(array_key_exists('issue', $available_criterias));
    $this->assertTrue(array_key_exists('cf_1', $available_criterias));
    $this->assertTrue(array_key_exists('cf_6', $available_criterias));
    $this->assertTrue(array_key_exists('cf_8', $available_criterias));
  }

  function test_find_report_hours() {
    $this->loadFixtures('CustomField', 'CustomValue', 'CustomFieldsProject');
    $project = $this->TimeEntry->Project->read(null, 1);
    $available_criterias = $this->TimeEntry->report_available_criterias($project);
    $criterias = array('project', 'issue', 'cf_1');
    $setting =& ClassRegistry::init('Setting');
    $user = $this->TimeEntry->User->read(null, 1);
    $range = array('from'=>'2007-3-23', 'to'=>'2007-4-21');
    
    $hours = $this->TimeEntry->find_report_hours($project, $available_criterias, $criterias, $setting, $user['User'], $range);
    $this->assertEqual(1, $hours[0]['TimeEntry']['project']);
    $this->assertEqual(1, $hours[0]['TimeEntry']['issue']);
    $this->assertEqual('2007', $hours[0]['TimeEntry']['tyear']);
    $this->assertEqual('03', $hours[0]['TimeEntry']['tmonth']);
    $this->assertEqual('12', $hours[0]['TimeEntry']['tweek']);
    $this->assertEqual('2007-03-23', $hours[0]['TimeEntry']['spent_on']);
    $this->assertEqual('', $hours[0][0]['cf_1']);
    $this->assertEqual(4.25, $hours[0][0]['hours']);

    $this->assertEqual(1, $hours[1]['TimeEntry']['project']);
    $this->assertEqual(3, $hours[1]['TimeEntry']['issue']);
    $this->assertEqual('2007', $hours[1]['TimeEntry']['tyear']);
    $this->assertEqual('04', $hours[1]['TimeEntry']['tmonth']);
    $this->assertEqual('16', $hours[1]['TimeEntry']['tweek']);
    $this->assertEqual('2007-04-21', $hours[1]['TimeEntry']['spent_on']);
    $this->assertEqual('MySQL', $hours[1][0]['cf_1']);
    $this->assertEqual(1, $hours[1][0]['hours']);
  }

  function test_get_total_hours() {
    $this->loadFixtures('CustomField', 'CustomValue', 'CustomFieldsProject');
    $project = $this->TimeEntry->Project->read(null, 1);
    $available_criterias = $this->TimeEntry->report_available_criterias($project);
    $criterias = array('project', 'issue', 'cf_1');
    $setting =& ClassRegistry::init('Setting');
    $user = $this->TimeEntry->User->read(null, 1);
    $range = array('from'=>'2007-3-23', 'to'=>'2007-4-21');
    $hours = $this->TimeEntry->find_report_hours($project, $available_criterias, $criterias, $setting, $user['User'], $range);

    $total_hours = $this->TimeEntry->get_total_hours($hours, 'month');

    $this->assertEqual(5.25, $total_hours);
    $this->assertEqual('2007-3', $hours[0]['TimeEntry']['month']);
    $this->assertEqual('2007-4', $hours[1]['TimeEntry']['month']);
  }

  function test_get_periods_year() {
    $periods = $this->TimeEntry->get_periods(array('from'=>'2007-3-23', 'to'=>'2007-4-21'), 'year');
    $this->assertEqual(1, count($periods));
    $this->assertEqual('2007', $periods[0]);

    $periods = $this->TimeEntry->get_periods(array('from'=>'2007-3-23', 'to'=>'2008-4-21'), 'year');
    $this->assertEqual(2, count($periods));
    $this->assertEqual('2007', $periods[0]);
    $this->assertEqual('2008', $periods[1]);

    $periods = $this->TimeEntry->get_periods(array('from'=>'2007-3-23', 'to'=>'2010-4-21'), 'year');
    $this->assertEqual(4, count($periods));
    $this->assertEqual('2007', $periods[0]);
    $this->assertEqual('2008', $periods[1]);
    $this->assertEqual('2009', $periods[2]);
    $this->assertEqual('2010', $periods[3]);
  }

  function test_get_periods_month() {
    $periods = $this->TimeEntry->get_periods(array('from'=>'2007-3-23', 'to'=>'2007-3-30'), 'month');
    $this->assertEqual(1, count($periods));
    $this->assertEqual('2007-3', $periods[0]);

    $periods = $this->TimeEntry->get_periods(array('from'=>'2007-3-23', 'to'=>'2007-4-21'), 'month');
    $this->assertEqual(2, count($periods));
    $this->assertEqual('2007-3', $periods[0]);
    $this->assertEqual('2007-4', $periods[1]);

    $periods = $this->TimeEntry->get_periods(array('from'=>'2007-10-23', 'to'=>'2008-2-21'), 'month');
    $this->assertEqual(5, count($periods));
    $this->assertEqual('2007-10', $periods[0]);
    $this->assertEqual('2007-11', $periods[1]);
    $this->assertEqual('2007-12', $periods[2]);
    $this->assertEqual('2008-1', $periods[3]);
    $this->assertEqual('2008-2', $periods[4]);
  }

  function test_get_periods_week() {
    $periods = $this->TimeEntry->get_periods(array('from'=>'2007-3-19', 'to'=>'2007-3-23'), 'week');
    $this->assertEqual(1, count($periods));
    $this->assertEqual('2007-12', $periods[0]);

    $periods = $this->TimeEntry->get_periods(array('from'=>'2007-3-19', 'to'=>'2007-3-30'), 'week');
    $this->assertEqual(2, count($periods));
    $this->assertEqual('2007-12', $periods[0]);
    $this->assertEqual('2007-13', $periods[1]);

    $periods = $this->TimeEntry->get_periods(array('from'=>'2007-3-19', 'to'=>'2007-4-13'), 'week');
    $this->assertEqual(4, count($periods));
    $this->assertEqual('2007-12', $periods[0]);
    $this->assertEqual('2007-13', $periods[1]);
    $this->assertEqual('2007-14', $periods[2]);
    $this->assertEqual('2007-15', $periods[3]);
  }

  function test_get_periods_day() {
    $periods = $this->TimeEntry->get_periods(array('from'=>'2007-3-19', 'to'=>'2007-3-19'), 'day');
    $this->assertEqual(1, count($periods));
    $this->assertEqual('2007-3-19', $periods[0]);

    $periods = $this->TimeEntry->get_periods(array('from'=>'2007-3-19', 'to'=>'2007-3-20'), 'day');
    $this->assertEqual(2, count($periods));
    $this->assertEqual('2007-3-19', $periods[0]);
    $this->assertEqual('2007-3-20', $periods[1]);

    $periods = $this->TimeEntry->get_periods(array('from'=>'2007-3-19', 'to'=>'2007-3-22'), 'day');
    $this->assertEqual(4, count($periods));
    $this->assertEqual('2007-3-19', $periods[0]);
    $this->assertEqual('2007-3-20', $periods[1]);
    $this->assertEqual('2007-3-21', $periods[2]);
    $this->assertEqual('2007-3-22', $periods[3]);
  }

}
?>
