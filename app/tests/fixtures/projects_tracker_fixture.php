<?php 
class ProjectTrackerFixture extends CakeTestFixture {
  var $name = 'ProjectTracker';
  var $import = array('model'=>'ProjectTracker');
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
?>