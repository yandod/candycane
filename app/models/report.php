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
  t.id as tracker_id,
  count(i.id) as total
from
  {$issue->useTable} i, {$issueStatus->useTable} s, {$tracker->useTable} t
where
  i.status_id=s.id
  and i.tracker_id=t.id
  and i.project_id={$projectId}
group by s.id, s.is_closed, t.id
EOT;

    return $this->convFlatArray($this->query($sql));
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
  v.id as fixed_version_id,
  count(i.id) as total
from
  {$issue->useTable} i, {$issueStatus->useTable} s, {$version->useTable} v
where
  i.status_id=s.id
  and i.fixed_version_id=v.id
  and i.project_id={$projectId}
group by s.id, s.is_closed, v.id
EOT;

    return $this->convFlatArray($this->query($sql));
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
  p.id as priority_id,
  count(i.id) as total
from
  {$issue->useTable} i, {$issueStatus->useTable} s, {$enumeration->useTable} p
where
  i.status_id=s.id
  and i.priority_id=p.id
  and i.project_id={$projectId}
group by s.id, s.is_closed, p.id
EOT;

    return $this->convFlatArray($this->query($sql));
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
  c.id as category_id,
  count(i.id) as total
from
  {$issue->useTable} i, {$issueStatus->useTable} s, {$issueCategory->useTable} c
where
  i.status_id=s.id
  and i.category_id=c.id
  and i.project_id={$projectId}
group by s.id, s.is_closed, c.id
EOT;

    return $this->convFlatArray($this->query($sql));
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
  a.id as assigned_to_id,
  count(i.id) as total
from
  {$issue->useTable} i, {$issueStatus->useTable} s, {$user->useTable} a
where
  i.status_id=s.id
  and i.assigned_to_id=a.id
  and i.project_id={$projectId}
group by s.id, s.is_closed, a.id
EOT;

    return $this->convFlatArray($this->query($sql));
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
  a.id as author_id,
  count(i.id) as total
from
  {$issue->useTable} i, {$issueStatus->useTable} s, {$user->useTable} a
where
  i.status_id=s.id
  and i.author_id=a.id
  and i.project_id={$projectId}
group by s.id, s.is_closed, a.id
EOT;

    return $this->convFlatArray($this->query($sql));
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
  i.project_id as project_id,
  count(i.id) as total
from
  {$issue->useTable} i, {$issueStatus->useTable} s
where
  i.status_id=s.id
  and i.project_id IN ({$ids})
group by s.id, s.is_closed, i.project_id
EOT;

    return $this->convFlatArray($this->query($sql));
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

  /**
   * 
   * @param  array $data
   * @return mixed
   */
  function convFlatArray($data)
  {
    $ret = array();
    foreach ($data as $v) {
      $tmp = array();
      foreach ($v as $k1 => $v1) {
        if (is_array($v1)) {
          foreach ($v1 as $k2 => $v2) {
            $tmp[$k2] = $v2;
          }
        } else {
          $tmp[$k1] = $v1;
        }
      }
      $ret[] = $tmp;
    }

    return $ret;
  }
}
