<?php
App::import('Helper', 'Html');
App::import('Helper', 'Timelog');

class TimelogTestController extends Controller {
  var $name = 'TimelogTests';
}

class AppFormTest extends CakeTestCase {
  var $helper = null;

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
    $this->assertEqual('/projects/timelogtest/timelog/edit', $this->Timelog->link_to_timelog_edit_url($project, array()));
    $this->assertEqual('/projects/timelogtest/timelog/edit?issue_id=123', $this->Timelog->link_to_timelog_edit_url($project, $issue));
  }

  function test_link_to_timelog_detail_url() {
    $project = array('Project'=>array('identifier'=>'timelogtest'));
    $this->assertEqual('/timelog/details/?period=all', $this->Timelog->url(array_merge($this->Timelog->link_to_timelog_detail_url(array()), array('?'=>array('period'=>'all')))));
    $this->assertEqual('/projects/timelogtest/timelog/details?period=all', $this->Timelog->url(array_merge($this->Timelog->link_to_timelog_detail_url($project), array('?'=>array('period'=>'all')))));
  }

  function test_link_to_timelog_report_url($project=array()) {
    $project = array('Project'=>array('identifier'=>'timelogtest'));
    $this->assertEqual('/timelog/reports/?period=all', $this->Timelog->url(array_merge($this->Timelog->link_to_timelog_report_url(array()), array('?'=>array('period'=>'all')))));
    $this->assertEqual('/projects/timelogtest/timelog/reports?period=all', $this->Timelog->url(array_merge($this->Timelog->link_to_timelog_report_url($project), array('?'=>array('period'=>'all')))));
  }
}
?>
