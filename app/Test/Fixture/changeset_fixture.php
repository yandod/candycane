<?php 
class ChangesetFixture extends CakeTestFixture {
  var $name = 'Changeset';
  var $import = array('table'=>'changesets');
  var $records = array(
    array('commit_date'=>'2007-04-11', 'committed_on'=>'2007-04-11 15:14:44 +02:00', 'revision'=>'1', 'id'=>'100', 'comments'=>'My very first commit', 'repository_id'=>'10', 'committer'=>'dlopper', 'user_id'=>3),
    array('commit_date'=>'2007-04-12', 'committed_on'=>'2007-04-12 15:14:44 +02:00', 'revision'=>'2', 'id'=>'101', 'comments'=>'\'This commit fixes #1, #2 and references #1 & #3\'', 'repository_id'=>'10', 'committer'=>'dlopper', 'user_id'=>3),
    array('commit_date'=>'2007-04-12', 'committed_on'=>'2007-04-12 15:14:44 +02:00', 'revision'=>'3', 'id'=>'102', 'comments'=>'|-\', \'  A commit with wrong issue ids\', \'  IssueID 666 3', 'repository_id'=>'10', 'committer'=>'dlopper', 'user_id'=>3),
    array('commit_date'=>'2007-04-12', 'committed_on'=>'2007-04-12 15:14:44 +02:00', 'revision'=>'4', 'id'=>'103', 'comments'=>"|-', '  A commit with an issue id of an other project', '  IssueID 4 2", 'repository_id'=>'10', 'committer'=>'dlopper', 'user_id'=>3),
  );
}