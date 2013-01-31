<?php 
class IssueFixture extends CakeTestFixture {
  var $name = 'Issue';
  var $fields = array(
    'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
    'tracker_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
    'project_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
    'subject' => array('type' => 'string', 'null' => false),
    'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
    'due_date' => array('type' => 'date', 'null' => true, 'default' => NULL),
    'category_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
    'status_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
    'assigned_to_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
    'priority_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
    'fixed_version_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
    'author_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
    'lock_version' => array('type' => 'integer', 'null' => false, 'default' => '0'),
    'created_on' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
    'updated_on' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
    'start_date' => array('type' => 'date', 'null' => true, 'default' => NULL),
    'done_ratio' => array('type' => 'integer', 'null' => false, 'default' => '0'),
    'estimated_hours' => array('type' => 'float', 'null' => true, 'default' => NULL),
    'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'issues_project_id' => array('column' => 'project_id', 'unique' => 0))
  );
  var $records = array(
/*    array('created_on'=>date('Y-m-d H:i:s', strtotime("-3 day")), 'project_id'=>1, 
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
*/
    array('created_on'=>"-3 day", 'project_id'=>1, 
          'updated_on'=>"-1 day", 'priority_id'=>4, 
          'subject'=>"Can't print recipes", 'id'=>1, 'fixed_version_id'=>null, 'category_id'=>1, 
          'description'=>'Unable to print recipes', 'tracker_id'=>1, 'assigned_to_id'=>null, 'author_id'=>2,
          'status_id'=>1, 'start_date'=>"-1 day", 
          'due_date'=>"+10 day",'lock_version'=>0
    ),
    array('created_on'=>"2006-07-19 21:04:21 +02:00", 'project_id'=>1, 
          'updated_on'=>"2006-07-19 21:09:50 +02:00", 'priority_id'=>5,
          'subject'=>"Add ingredients categories", 'id'=>2, 'fixed_version_id'=>2, 'category_id'=>null,
          'description'=>"Ingredients of the recipe should be classified by categories", 'tracker_id'=>2, 'assigned_to_id'=>3, 'author_id'=>2,
          'status_id'=>2, 'start_date'=>"-2 day",
          'due_date'=>null,'lock_version'=>0
    ),
    array('created_on'=>"2006-07-19 21:07:27 +02:00", 'project_id'=>1,
          'updated_on'=>"2006-07-19 21:07:27 +02:00", 'priority_id'=>4,
          'subject'=>"Error 281 when updating a recipe", 'id'=>3, 'fixed_version_id'=>null, 'category_id'=>null,
          'description'=>"Error 281 is encountered when saving a recipe", 'tracker_id'=>1, 'assigned_to_id'=>3, 'author_id'=>2,
          'status_id'=>1, 'start_date'=>"+1 day",
          'due_date'=>"-40 day",'lock_version'=>0
    ),
    array('created_on'=>"-5 day", 'project_id'=>2,
          'updated_on'=>"-2 day", 'priority_id'=>4,
          'subject'=>"Issue on project 2", 'id'=>4, 'fixed_version_id'=>null, 'category_id'=>null,
          'description'=>"Issue on project 2", 'tracker_id'=>1, 'assigned_to_id'=>null, 'author_id'=>2,
          'status_id'=>1, 'start_date'=>null,
          'due_date'=>null,'lock_version'=>0
    ),
    array('created_on'=>"-5 day", 'project_id'=>3,
          'updated_on'=>"-2 day", 'priority_id'=>4,
          'subject'=>"Subproject issue", 'id'=>5, 'fixed_version_id'=>null, 'category_id'=>null,
          'description'=>"This is an issue on a cookbook subproject", 'tracker_id'=>1, 'assigned_to_id'=>null, 'author_id'=>2,
          'status_id'=>1, 'start_date'=>null,
          'due_date'=>null,'lock_version'=>0
    ),
    array('created_on'=>"-1 minute", 'project_id'=>5,
          'updated_on'=>"-1 minute", 'priority_id'=>4,
          'subject'=>"Issue of a private subproject", 'id'=>6, 'fixed_version_id'=>null, 'category_id'=>null,
          'description'=>"This is an issue of a private subproject of cookbook", 'tracker_id'=>1, 'assigned_to_id'=>null, 'author_id'=>2,
          'status_id'=>1, 'start_date'=>"now",
          'due_date'=>"+1 day",'lock_version'=>0
    ),
    array('created_on'=>"-10 day", 'project_id'=>1,
          'updated_on'=>"-10 day", 'priority_id'=>3,
          'subject'=>"Issue due today", 'id'=>7, 'fixed_version_id'=>null, 'category_id'=>null, 
          'description'=>"This is an issue that is due today", 'tracker_id'=>1, 'assigned_to_id'=>null, 'author_id'=>2,
          'status_id'=>1, 'start_date'=>"-10 day",
          'due_date'=>"now", 'lock_version'=>0
    )
  );
  function init() {
    foreach($this->records as $index => $record) {
      $this->records[$index]['created_on'] = date('Y-m-d H:m:s', strtotime($record['created_on']));
      $this->records[$index]['updated_on'] = date('Y-m-d H:m:s', strtotime($record['updated_on']));
      $this->records[$index]['due_date'] = date('Y-m-d H:m:s', strtotime($record['due_date']));
      if(!empty($record['start_date'])) $this->records[$index]['start_date'] = date('Y-m-d H:m:s', strtotime($record['start_date']));
    }

    return parent::init();
  }
}