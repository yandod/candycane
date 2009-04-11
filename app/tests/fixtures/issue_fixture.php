<?php 
class IssueFixture extends CakeTestFixture {
  var $name = 'Issue';
  var $import = array('model'=>'Issue');
  var $records = array(
    array('created_on'=>date('Y-m-d H:i:s', strtotime("-3 day")), 'project_id'=>1, 
          'updated_on'=>date('Y-m-d H:i:s', strtotime("-1 day")), 'priority_id'=>4, 
          'subject'=>"Can't print recipes", 'id'=>1, 'fixed_version_id'=>null, 'category_id'=>1, 
          'description'=>'Unable to print recipes', 'tracker_id'=>1, 'assigned_to_id'=>null, 'author_id'=>2,
          'status_id'=>1, 'start_date'=>date('Y-m-d H:i:s', strtotime("-1 day")), 
          'due_date'=>date('Y-m-d H:i:s', strtotime("+10 day")),'lock_version'=>null
    ),
    array('created_on'=>"2006-07-19 21:04:21 +02:00", 'project_id'=>1, 
          'updated_on'=>"2006-07-19 21:09:50 +02:00", 'priority_id'=>5,
          'subject'=>"Add ingredients categories", 'id'=>2, 'fixed_version_id'=>2, 'category_id'=>null,
          'description'=>"Ingredients of the recipe should be classified by categories", 'tracker_id'=>2, 'assigned_to_id'=>3, 'author_id'=>2,
          'status_id'=>2, 'start_date'=>date('Y-m-d H:i:s', strtotime("-2 day")),
          'due_date'=>null,'lock_version'=>null
    ),
    array('created_on'=>"2006-07-19 21:07:27 +02:00", 'project_id'=>1,
          'updated_on'=>"2006-07-19 21:07:27 +02:00", 'priority_id'=>4,
          'subject'=>"Error 281 when updating a recipe", 'id'=>3, 'fixed_version_id'=>null, 'category_id'=>null,
          'description'=>"Error 281 is encountered when saving a recipe", 'tracker_id'=>1, 'assigned_to_id'=>3, 'author_id'=>2,
          'status_id'=>1, 'start_date'=>date('Y-m-d H:i:s', strtotime("+1 day")),
          'due_date'=>date('Y-m-d H:i:s', strtotime("-40 day")),'lock_version'=>null
    ),
    array('created_on'=>date('Y-m-d H:i:s', strtotime("-5 day")), 'project_id'=>2,
          'updated_on'=>date('Y-m-d H:i:s', strtotime("-2 day")), 'priority_id'=>4,
          'subject'=>"Issue on project 2", 'id'=>4, 'fixed_version_id'=>null, 'category_id'=>null,
          'description'=>"Issue on project 2", 'tracker_id'=>1, 'assigned_to_id'=>null, 'author_id'=>2,
          'status_id'=>1, 'start_date'=>null,
          'due_date'=>null,'lock_version'=>null
    ),
    array('created_on'=>date('Y-m-d H:i:s', strtotime("-5 day")), 'project_id'=>3,
          'updated_on'=>date('Y-m-d H:i:s', strtotime("-2 day")), 'priority_id'=>4,
          'subject'=>"Subproject issue", 'id'=>5, 'fixed_version_id'=>null, 'category_id'=>null,
          'description'=>"This is an issue on a cookbook subproject", 'tracker_id'=>1, 'assigned_to_id'=>null, 'author_id'=>2,
          'status_id'=>1, 'start_date'=>null,
          'due_date'=>null,'lock_version'=>null
    ),
    array('created_on'=>date('Y-m-d H:i:s', strtotime("-1 minute")), 'project_id'=>5,
          'updated_on'=>date('Y-m-d H:i:s', strtotime("-1 minute")), 'priority_id'=>4,
          'subject'=>"Issue of a private subproject", 'id'=>6, 'fixed_version_id'=>null, 'category_id'=>null,
          'description'=>"This is an issue of a private subproject of cookbook", 'tracker_id'=>1, 'assigned_to_id'=>null, 'author_id'=>2,
          'status_id'=>1, 'start_date'=>date('Y-m-d H:i:s'),
          'due_date'=>date('Y-m-d H:i:s', strtotime("+1 day")),'lock_version'=>null
    ),
    array('created_on'=>date('Y-m-d H:i:s', strtotime("-10 day")), 'project_id'=>1,
          'updated_on'=>date('Y-m-d H:i:s', strtotime("-10 day")), 'priority_id'=>3,
          'subject'=>"Issue due today", 'id'=>7, 'fixed_version_id'=>null, 'category_id'=>null, 
          'description'=>"This is an issue that is due today", 'tracker_id'=>1, 'assigned_to_id'=>null, 'author_id'=>2,
          'status_id'=>1, 'start_date'=>date('Y-m-d H:i:s', strtotime("-10 day")),
          'due_date'=>date('Y-m-d H:i:s'), 'lock_version'=>0
    )
  );
}
?>