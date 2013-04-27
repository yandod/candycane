<?php 
class ProjectsTrackerFixture extends CakeTestFixture {
  var $name = 'ProjectsTracker';
  var $fields = array(
      'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
      'project_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'primary'),
      'tracker_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
      'indexes' => array('projects_trackers_project_id' => array('column' => 'project_id', 'unique' => 0))
  );
  var $records = array(
    array('project_id'=>4, 'tracker_id'=>3),
    array('project_id'=>1, 'tracker_id'=>1),
    array('project_id'=>5, 'tracker_id'=>1),
    array('project_id'=>1, 'tracker_id'=>2),
    array('project_id'=>5, 'tracker_id'=>2),
    array('project_id'=>5, 'tracker_id'=>3),
    array('project_id'=>2, 'tracker_id'=>1),
    array('project_id'=>2, 'tracker_id'=>2),
    array('project_id'=>2, 'tracker_id'=>3),
    array('project_id'=>3, 'tracker_id'=>2),
    array('project_id'=>3, 'tracker_id'=>3),
    array('project_id'=>4, 'tracker_id'=>1),
    array('project_id'=>4, 'tracker_id'=>2),
    array('project_id'=>1, 'tracker_id'=>3),
  );
}