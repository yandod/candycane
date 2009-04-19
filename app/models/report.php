<?php
class Report extends AppModel
{
  var $name = 'Report';
  var $useTable = false;

  /**
   * find issues_by_tracker
   *
   * @param  integer $projectId
   * @return mixed
   */
  function findIssuesByTracker($projectId)
  {
    $issue =& ClassRegistry::init('Issue');
    $issueStatus =& ClassRegistry::init('IssueStatus');
    $tracker =& ClassRegistry::init('Tracker');

    $sql = <<<EOT
select s.id as status_id, s.is_closed as closed,
  o.id as tracker_id,
  count(i.id) as total
from
  {$issue->useTable} i, {$issueStatus->useTable} s, {$tracker->useTable} o
where
  i.status_id=s.id
  and i.tracker_id=o.id
  and i.project_id={$projectId}
group by s.id, s.is_closed, o.id
EOT;

    return $this->query($sql);
  }

  /**
   * find issues_by_version
   *
   * @param  integer $projectId
   * @return mixed
   */
  function findIssuesByVersion($projectId)
  {
    $issue =& ClassRegistry::init('Issue');
    $issueStatus =& ClassRegistry::init('IssueStatus');
    $version =& ClassRegistry::init('Version');

    $sql = <<<EOT
select s.id as status_id,
  s.is_closed as closed,
  o.id as fixed_version_id,
  count(i.id) as total
from
  {$issue->useTable} i, {$issueStatus->useTable} s, {$version->useTable} o
where
  i.status_id=s.id
  and i.fixed_version_id=o.id
  and i.project_id={$projectId}
group by s.id, s.is_closed, o.id
EOT;

    return $this->query($sql);
  }

  /**
   * find issues_by_priority
   *
   * @param  integer $projectId
   * @return mixed
   */
  function findIssuesByPriority($projectId)
  {
    $issue =& ClassRegistry::init('Issue');
    $issueStatus =& ClassRegistry::init('IssueStatus');
    $enumeration =& ClassRegistry::init('Enumeration');

    $sql = <<<EOT
select s.id as status_id,
  s.is_closed as closed,
  o.id as priority_id,
  count(i.id) as total
from
  {$issue->useTable} i, {$issueStatus->useTable} s, {$enumeration->useTable} o
where
  i.status_id=s.id
  and i.priority_id=o.id
  and i.project_id={$projectId}
group by s.id, s.is_closed, o.id
EOT;

    return $this->query($sql);
  }

  /**
   * find issues_by_category
   *
   * @param  integer $projectId
   * @return mixed
   */
  function findIssuesByCategory($projectId)
  {
    $issue =& ClassRegistry::init('Issue');
    $issueStatus =& ClassRegistry::init('IssueStatus');
    $issueCategory =& ClassRegistry::init('IssueCategory');

    $sql = <<<EOT
select s.id as status_id,
  s.is_closed as closed,
  o.id as category_id,
  count(i.id) as total
from
  {$issue->useTable} i, {$issueStatus->useTable} s, {$issueCategory->useTable} o
where
  i.status_id=s.id
  and i.category_id=o.id
  and i.project_id={$projectId}
group by s.id, s.is_closed, o.id
EOT;

    return $this->query($sql);
  }

  /**
   * find issues_by_assigned_to
   *
   * @param  integer $projectId
   * @return mixed
   */
  function findIssuesByAssignedTo($projectId)
  {
    $issue =& ClassRegistry::init('Issue');
    $issueStatus =& ClassRegistry::init('IssueStatus');
    $user =& ClassRegistry::init('User');

    $sql = <<<EOT
select s.id as status_id,
  s.is_closed as closed,
  o.id as assigned_to_id,
  count(i.id) as total
from
  {$issue->useTable} i, {$issueStatus->useTable} s, {$user->useTable} o
where
  i.status_id=s.id
  and i.assigned_to_id=o.id
  and i.project_id={$projectId}
group by s.id, s.is_closed, o.id
EOT;

    return $this->query($sql);
  }

  /**
   * find issues_by_author
   *
   * @param  integer $projectId
   * @return mixed
   */
  function findIssuesByAuthor($projectId)
  {
    $issue =& ClassRegistry::init('Issue');
    $issueStatus =& ClassRegistry::init('IssueStatus');
    $user =& ClassRegistry::init('User');

    $sql = <<<EOT
select s.id as status_id,
  s.is_closed as closed,
  o.id as author_id,
  count(i.id) as total
from
  {$issue->useTable} i, {$issueStatus->useTable} s, {$user->useTable} o
where
  i.status_id=s.id
  and i.author_id=o.id
  and i.project_id={$projectId}
group by s.id, s.is_closed, o.id
EOT;

    return $this->query($sql);
  }

  /**
   * find issues_by_subproject
   *
   * @param  array $projectIds
   * @return mixed
   */
  function findIssuesBySubproject($projectIds)
  {
    $issue =& ClassRegistry::init('Issue');
    $issueStatus =& ClassRegistry::init('IssueStatus');
    $ids = implode(',', $projectIds);

    $sql = <<<EOT
select    s.id as status_id,
  s.is_closed as closed,
  o.project_id as project_id,
  count(o.id) as total
from
  {$issue->useTable} o, {$issueStatus->useTable} s
where
  o.status_id=s.id
  and o.project_id IN ({$ids})
group by s.id, s.is_closed, o.project_id
EOT;

    return $this->query($sql);
  }

  /**
   * find members
   *
   * @param  integer $projectId
   * @return array
   */
  function findMembers($projectId)
  {
    $member =& ClassRegistry::init('Member');
    $members = $member->find('all', array('conditions' => array(
                                                      'project_id' => $projectId
                                                    , 'User.status' => USER_STATUS_ACTIVE)));
    $data = array();
    foreach ($members as $v) {
      $data[] = array(
        'id' => $v['User']['id'],
        'name' => $v['User']['firstname'].' '.$v['User']['lastname'],
      );
    }

    return $data;
  }

  /**
   * find enumerations
   *
   * @return array
   */
  function findEnumurations()
  {
    $enumeration =& ClassRegistry::init('Enumeration');

    $ret = $enumeration->get_values('IPRI');

    if ($ret) {
     $values = $ret;
     $ret = array();
     foreach ($values as $v) {
       $ret[] = $v[$enumeration->alias];
     }
    }

    return $ret;
  }
}
