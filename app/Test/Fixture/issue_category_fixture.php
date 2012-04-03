<?php 
class IssueCategoryFixture extends CakeTestFixture {
  var $name = 'IssueCategory';
  var $import = array('table'=>'issue_categories');

  var $records = array(
    array('name'=>' Printing', 'project_id'=>1, 'assigned_to_id'=>2, 'id'=>1),
    array('name'=>' Recipes', 'project_id'=>1, 'assigned_to_id'=>null, 'id'=>2),
    array('name'=>' Stock management', 'project_id'=>2, 'assigned_to_id'=>null, 'id'=>3),
    array('name'=>' Printing', 'project_id'=>2, 'assigned_to_id'=>null, 'id'=>4),
  );
}