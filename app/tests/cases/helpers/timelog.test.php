<?php
App::import('Helper', 'Html');
App::import('Helper', 'Timelog');

class TimelogTestController extends Controller {
  var $name = 'TimelogTests';
}

class AppFormTest extends CakeTestCase {
  var $helper = null;
  var $autoFixtures = false;
  var $fixtures = array(
      'app.issue', 'app.project', 'app.tracker', 'app.issue_status', 'app.user', 'app.version',
      'app.enumeration', 'app.issue_category', 'app.token', 'app.member', 'app.role', 'app.user_preference',
      'app.enabled_module', 'app.issue_category', 'app.time_entry', 'app.changeset', 'app.changesets_issue', 'app.attachment',
      'app.projects_tracker', 'app.custom_value', 'app.custom_field', 'app.watcher',
      'app.wiki', 'app.wiki_page', 'app.wiki_content', 'app.wiki_content_version', 'app.wiki_redirect','app.workflow'
  );

  function setUp() {
    parent::setUp();
    Router::reload();
    Router::connect('/projects/:project_id/timelog/:action/*', array('controller' => 'timelog'), array('project_id' => '.+'));
    Router::connect('/projects/:project_id/timelog/:action/:page/:sort/:direction/*', array('controller' => 'timelog'), array('project_id' => '.+'));
    Router::connect('timelog/:action/:id/*', array('controller' => 'timelog'));

    $this->Timelog = new TimelogHelper();
    $this->Timelog->Html =& new HtmlHelper();
    $this->Controller =& new TimelogTestController();
    $this->View =& new View($this->Controller);

  }
  function tearDown() {
    unset($this->Timelog);
  }

  function test_link_to_timelog_edit_url() {
    $project = array('Project'=>array('identifier'=>'timelogtest'));
    $issue   = array('Issue'  =>array('id' => '123'));
    $this->assertEqual('/projects/timelogtest/timelog/edit', $this->Timelog->url($this->Timelog->link_to_timelog_edit_url($project, array())));
    $this->assertEqual('/projects/timelogtest/timelog/edit?issue_id=123', $this->Timelog->url($this->Timelog->link_to_timelog_edit_url($project, $issue)));
  }

  function test_link_to_timelog_detail_url() {
    $project = array('Project'=>array('identifier'=>'timelogtest'));
    $this->assertEqual('/timelog/details/?period=all', $this->Timelog->url(array_merge($this->Timelog->link_to_timelog_detail_url(array()), array('?'=>array('period'=>'all')))));
    $this->assertEqual('/projects/timelogtest/timelog/details?period=all', $this->Timelog->url(array_merge($this->Timelog->link_to_timelog_detail_url($project), array('?'=>array('period'=>'all')))));
  }

  function test_link_to_timelog_report_url() {
    $project = array('Project'=>array('identifier'=>'timelogtest'));
    $this->assertEqual('/timelog/report/?period=all', $this->Timelog->url(array_merge($this->Timelog->link_to_timelog_report_url(array()), array('?'=>array('period'=>'all')))));
    $this->assertEqual('/projects/timelogtest/timelog/report?period=all', $this->Timelog->url(array_merge($this->Timelog->link_to_timelog_report_url($project), array('?'=>array('period'=>'all')))));
  }
  
  function test_url_options_empty() {
    $this->Timelog->params['url'] = array();
    $this->assertEqual(array('?'=>array()), $this->Timelog->url_options(array(), array()));
  }

  function test_url_options_only_getparameter() {
    $this->Timelog->params['url'] = array('from'=>'2009-05-10','to'=>'2009-05-20');
    $this->assertEqual(array('?'=>array('from'=>'2009-05-10','to'=>'2009-05-20')), $this->Timelog->url_options(array(), array()));
  }
  
  function test_url_options_only_project() {
    $this->loadFixtures('Project', 'Tracker', 'User', 'Version', 'IssueCategory', 'TimeEntry');
    $this->Timelog->params['url'] = array();
    $project = ClassRegistry::init('Project')->read(null, 1);
    $this->assertEqual(array('project_id'=>'ecookbook', '?'=>array()), $this->Timelog->url_options($project, array()));
  }

  function test_url_options_only_project_issue() {
    $this->loadFixtures('Project', 'Tracker', 'User', 'Version', 'IssueCategory', 'TimeEntry', 'Issue', 'IssueStatus', 'Enumeration', 'CustomValue', 'CustomField');
    $this->Timelog->params['url'] = array();
    $project = ClassRegistry::init('Project')->read(null, 1);
    $issue = ClassRegistry::init('Issue')->read(null, 1);
    $this->assertEqual(array('project_id'=>'ecookbook', '?'=>array('issue_id'=>1)), $this->Timelog->url_options($project, $issue));
  }

  function test_url_options_full() {
    $this->loadFixtures('Project', 'Tracker', 'User', 'Version', 'IssueCategory', 'TimeEntry', 'Issue', 'IssueStatus', 'Enumeration', 'CustomValue', 'CustomField');
    $this->Timelog->params['url'] = array('from'=>'2009-05-10','to'=>'2009-05-20');
    $project = ClassRegistry::init('Project')->read(null, 1);
    $issue = ClassRegistry::init('Issue')->read(null, 1);
    $this->assertEqual(array('project_id'=>'ecookbook', '?'=>array('from'=>'2009-05-10','to'=>'2009-05-20', 'issue_id'=>1)), $this->Timelog->url_options($project, $issue));
  }

  function test_select_hours_month_criteria() {
    $data = array(
      array(
        'TimeEntry'=>array('year'=>'2009', 'month'=>'04', 'day'=>'30'),
        'Issue'=>array('category'=>1, 'tracker'=>2),
        array('hours'=>20),
      ),
      array(
        'TimeEntry'=>array('year'=>'2009', 'month'=>'05', 'day'=>'01'),
        'Issue'=>array('category'=>1, 'tracker'=>3),
        array('hours'=>10),
      ),
      array(
        'TimeEntry'=>array('year'=>'2009', 'month'=>'05', 'day'=>'02'),
        'Issue'=>array('category'=>2, 'tracker'=>1),
        array('hours'=>5),
      ),
      array(
        'TimeEntry'=>array('year'=>'2009', 'month'=>'06', 'day'=>'02'),
        'Issue'=>array('category'=>2, 'tracker'=>1),
        array('hours'=>8),
      ),
    );
    
    $expect = array(
      array(
        'TimeEntry'=>array('year'=>'2009', 'month'=>'05', 'day'=>'01'),
        'Issue'=>array('category'=>1, 'tracker'=>3),
        array('hours'=>10),
      ),
      array(
        'TimeEntry'=>array('year'=>'2009', 'month'=>'05', 'day'=>'02'),
        'Issue'=>array('category'=>2, 'tracker'=>1),
        array('hours'=>5),
      ),
    );
    
    $this->assertEqual($expect, $this->Timelog->select_hours($data, 'month', '05'));
  }

  function test_select_hours_category_criteria() {
    $data = array(
      array(
        'TimeEntry'=>array('year'=>'2009', 'month'=>'04', 'day'=>'30'),
        'Issue'=>array('category'=>1, 'tracker'=>2),
        array('hours'=>20),
      ),
      array(
        'TimeEntry'=>array('year'=>'2009', 'month'=>'05', 'day'=>'01'),
        'Issue'=>array('category'=>1, 'tracker'=>3),
        array('hours'=>10),
      ),
      array(
        'TimeEntry'=>array('year'=>'2009', 'month'=>'05', 'day'=>'02'),
        'Issue'=>array('category'=>2, 'tracker'=>1),
        array('hours'=>5),
      ),
      array(
        'TimeEntry'=>array('year'=>'2009', 'month'=>'06', 'day'=>'02'),
        'Issue'=>array('category'=>2, 'tracker'=>1),
        array('hours'=>8),
      ),
    );

    $expect = array(
      array(
        'TimeEntry'=>array('year'=>'2009', 'month'=>'04', 'day'=>'30'),
        'Issue'=>array('category'=>1, 'tracker'=>2),
        array('hours'=>20),
      ),
      array(
        'TimeEntry'=>array('year'=>'2009', 'month'=>'05', 'day'=>'01'),
        'Issue'=>array('category'=>1, 'tracker'=>3),
        array('hours'=>10),
      ),
    );

    $this->assertEqual($expect, $this->Timelog->select_hours($data, 'category', '1'));
  }

  function test_sum_hours() {
    $data = array(
      array(
        'TimeEntry'=>array('year'=>'2009', 'month'=>'04', 'day'=>'30'),
        'Issue'=>array('category'=>1, 'tracker'=>2),
        array('hours'=>20),
      ),
      array(
        'TimeEntry'=>array('year'=>'2009', 'month'=>'05', 'day'=>'01'),
        'Issue'=>array('category'=>1, 'tracker'=>3),
        array('hours'=>10),
      ),
      array(
        'TimeEntry'=>array('year'=>'2009', 'month'=>'05', 'day'=>'02'),
        'Issue'=>array('category'=>2, 'tracker'=>1),
        array('hours'=>5),
      ),
      array(
        'TimeEntry'=>array('year'=>'2009', 'month'=>'06', 'day'=>'02'),
        'Issue'=>array('category'=>2, 'tracker'=>1),
        array('hours'=>8),
      ),
    );

    $this->assertEqual(43, $this->Timelog->sum_hours($data));
  }

  function test_selectable_criterias_all() {
    $locale = Configure::write('Config.language', 'en');
    $available_criterias = array(
      'project'  => array('sql' => 'TimeEntry.project_id',
                           'klass' => ClassRegistry::init('Project'),
                           'label' => 'Project'),
      'version'  => array('sql' => "Issue.fixed_version_id",
                           'klass' => ClassRegistry::init('Version'),
                           'label' => 'Version'),
      'category' => array('sql' => "Issue.category_id",
                           'klass' => ClassRegistry::init('IssueCategory'),
                           'label' => 'Category'),
      'issue'    => array('sql' => "TimeEntry.issue_id",
                           'klass' => ClassRegistry::init('Issue'),
                           'label' => 'Issue'),
    );

    $this->assertEqual(array('project'=>'Project', 'version'=>'Version', 'category'=>'Category', 'issue'=>'Issue'), 
                       $this->Timelog->selectable_criterias($available_criterias, array()));
  }

  function test_selectable_criterias_partial() {
    $locale = Configure::write('Config.language', 'en');
    $available_criterias = array(
      'project'  => array('sql' => 'TimeEntry.project_id',
                           'klass' => ClassRegistry::init('Project'),
                           'label' => 'Project'),
      'version'  => array('sql' => "Issue.fixed_version_id",
                           'klass' => ClassRegistry::init('Version'),
                           'label' => 'Version'),
      'category' => array('sql' => "Issue.category_id",
                           'klass' => ClassRegistry::init('IssueCategory'),
                           'label' => 'Category'),
      'issue'    => array('sql' => "TimeEntry.issue_id",
                           'klass' => ClassRegistry::init('Issue'),
                           'label' => 'Issue'),
    );

    $this->assertEqual(array('version'=>'Version', 'category'=>'Category'), 
                       $this->Timelog->selectable_criterias($available_criterias, array('project', 'issue')));
  }
  
  function test_format_criteria_value_project() {
    $this->loadFixtures('Project');
    $locale = Configure::write('Config.language', 'en');
    $available_criterias = array(
      'project'  => array('sql' => 'TimeEntry.project_id',
                           'klass' => ClassRegistry::init('Project'),
                           'label' => 'Project'),
    );
    
    $this->assertEqual('OnlineStore', $this->Timelog->format_criteria_value($available_criterias, 'project', 2));
  }

  function test_format_criteria_value_version() {
    $this->loadFixtures('Version');
    $locale = Configure::write('Config.language', 'en');
    $available_criterias = array(
      'version'  => array('sql' => "Issue.fixed_version_id",
                           'klass' => ClassRegistry::init('Version'),
                           'label' => 'Version'),
    );

    $this->assertEqual('1.0', $this->Timelog->format_criteria_value($available_criterias, 'version', 2));
  }

  function test_format_criteria_value_category() {
    $this->loadFixtures('IssueCategory');
    $locale = Configure::write('Config.language', 'en');
    $available_criterias = array(
      'category' => array('sql' => "Issue.category_id",
                           'klass' => ClassRegistry::init('IssueCategory'),
                           'label' => 'Category'),
    );

    $this->assertEqual(' Recipes', $this->Timelog->format_criteria_value($available_criterias, 'category', 2));
  }

  function test_format_criteria_value_member() {
    $this->loadFixtures('User');
    $locale = Configure::write('Config.language', 'en');
    $available_criterias = array(
      'member'   => array('sql' => "TimeEntry.user_id",
                           'klass' => ClassRegistry::init('User'),
                           'label' => 'Member'),
    );

    $this->assertEqual('John Smith', $this->Timelog->format_criteria_value($available_criterias, 'member', 2));
  }

  function test_format_criteria_value_tracker() {
    $this->loadFixtures('Tracker');
    $locale = Configure::write('Config.language', 'en');
    $available_criterias = array(
      'tracker'  => array('sql' => "Issue.tracker_id",
                           'klass' => ClassRegistry::init('Tracker'),
                           'label' => 'Tracker'),
    );

    $this->assertEqual('Feature request', $this->Timelog->format_criteria_value($available_criterias, 'tracker', 2));
  }

  function test_format_criteria_value_activity() {
    $this->loadFixtures('Enumeration');
    $locale = Configure::write('Config.language', 'en');
    $available_criterias = array(
      'activity' => array('sql' => "TimeEntry.activity_id",
                           'klass' => ClassRegistry::init('TimeEntry')->Activity,
                           'label' => 'Activity'),
    );

    $this->assertEqual('Design', $this->Timelog->format_criteria_value($available_criterias, 'activity', 9));
  }

  function test_format_criteria_value_issue() {
    $this->loadFixtures('Issue', 'Tracker');
    $locale = Configure::write('Config.language', 'en');
    $available_criterias = array(
      'issue'    => array('sql' => "TimeEntry.issue_id",
                           'klass' => ClassRegistry::init('Issue'),
                           'label' => 'Issue'),
    );

    $this->assertEqual('Feature request #2: Add ingredients categories', $this->Timelog->format_criteria_value($available_criterias, 'issue', 2));
  }

  function test_empty_td() {
    $this->assertEqual('', $this->Timelog->empty_td(0));
    $this->assertEqual('<td></td>', $this->Timelog->empty_td(1));
    $this->assertEqual('<td></td><td></td>', $this->Timelog->empty_td(2));
    $this->assertEqual('<td></td><td></td><td></td><td></td>', $this->Timelog->empty_td(4));
  }

}
?>
