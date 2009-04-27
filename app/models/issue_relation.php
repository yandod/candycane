<?php
## redMine - project management software
## Copyright (C) 2006-2007  Jean-Philippe Lang
##
## This program is free software; you can redistribute it and/or
## modify it under the terms of the GNU General Public License
## as published by the Free Software Foundation; either version 2
## of the License, or (at your option) any later version.
## 
## This program is distributed in the hope that it will be useful,
## but WITHOUT ANY WARRANTY; without even the implied warranty of
## MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
## GNU General Public License for more details.
## 
## You should have received a copy of the GNU General Public License
## along with this program; if not, write to the Free Software
## Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
#
define('ISSUERELATION_TYPE_RELATES',    "relates");
define('ISSUERELATION_TYPE_DUPLICATES', "duplicates");
define('ISSUERELATION_TYPE_BLOCKS',     "blocks");
define('ISSUERELATION_TYPE_PRECEDES',   "precedes");

class IssueRelation extends AppModel
{
  var $name = 'IssueRelation';
  var $belongsTo = array(
    'IssueFrom' => array('className'=>'Issue', 'foreign_key'=>'issue_from_id'),
    'IssueTo' => array('className'=>'Issue', 'foreign_key'=>'issue_to_id')
  );
  var $validate = array(
    'issue_from_id' => array(
      'validates_presence_of'=>array('rule'=>array('existsIssue', 'IssueFrom')),
    ),
    'issue_to_id' => array(
      'validates_presence_of'=>array('rule'=>array('existsIssue', 'IssueTo')),
      'validates_invalid_of'=>array('rule'=>array('sameId')),
      'validates_uniqueness_of'=>array('rule'=>array('isUnique')),
      'validates_not_same_project'=>array('rule'=>array('sameProject')),
      'validates_circular_dependency'=>array('rule'=>array('circularDependency')),
    ),
    'relation_type' => array(
      'validates_presence_of'=>array('rule'=>array('notEmpty')),
      'validates_invalid_of'=>array('rule'=>array('inList', array(ISSUERELATION_TYPE_RELATES, ISSUERELATION_TYPE_DUPLICATES, ISSUERELATION_TYPE_BLOCKS, ISSUERELATION_TYPE_PRECEDES))),
    ),
    'delay' => array(
      'validates_numericality_of'=>array('rule'=>array('numeric'), 'allowEmpty'=>true),
    )
  );
  function existsIssue($data, $model) {
    $field = key($data);
    if(empty($this->data[$this->name][$field])) return false;
    $recursive = $this->$model->recursive;
    $this->$model->recursive = -1;
    $result = $this->$model->read(null, $this->data[$this->name][$field]);
    $this->$model->recursive = $recursive;
    return $result;
  }
  function isUnique($field, $data) {
    return parent::isUnique(array('issue_from_id', 'issue_to_id'), false);
  }
  function sameId($data) {
    return $this->data[$this->name]['issue_to_id'] != $this->data[$this->name]['issue_from_id'];
  }
  function sameProject($data) {
    $issue_from = $this->IssueFrom->data;
    $issue_to = $this->IssueTo->data;
    return ($issue_from['IssueFrom']['project_id'] == $issue_to['IssueTo']['project_id'] || $Settings->cross_project_issue_relations);
  }
  function circularDependency($data) {
    return !in_array($this->data[$this->name]['issue_from_id'], $this->all_dependent_issues($this->data[$this->name]['issue_to_id']));
  }
  
  function all_dependent_issues($issue_id) {
    $dependencies = array();
    $relations_from = $this->find('all', array('conditions'=>array('issue_from_id'=>$issue_id), 'fields'=>array('issue_to_id')));
    foreach($relations_from as $relation) {
      $dependencies[] = $relation[$this->name]['issue_to_id'];
      $dependencies = array_merge($dependencies, $this->all_dependent_issues($relation[$this->name]['issue_to_id']));
    }
    return $dependencies;
  }

#  def other_issue(issue) ==> Move to IssuesHelper
#  def label_for(issue) ==> Move to IssuesHelper

  function beforeSave($options = array()) {
    if(ISSUERELATION_TYPE_PRECEDES == $this->data[$this->name]['relation_type']) {
      if(empty($this->data[$this->name]['delay'])) $this->data[$this->name]['delay'] = 0;
      $this->set_issue_to_dates();
    } else {
      $this->data[$this->name]['delay'] = null;
    }
    return true;
  }
  
  function set_issue_to_dates() {
    $soonest_start = $this->successor_soonest_start();
    if($soonest_start && (!$this->IssueTo->data['IssueTo']['start_date'] || strtotime($this->IssueTo->data['IssueTo']['start_date']) < $soonest_start)) {
      $this->IssueTo->data['IssueTo']['start_date'] = date('Y-m-d', $soonest_start); 
      $this->IssueTo->data['IssueTo']['due_date']   = date('Y-m-d', $soonest_start + $this->IssueTo->duration());
      $this->IssueTo->save(null, false, array('start_date','due_date'));
    }
  }
  
  function successor_soonest_start() {
    if(!((ISSUERELATION_TYPE_PRECEDES == $this->data[$this->name]['relation_type']) && ($this->IssueFrom->data['IssueFrom']['start_date'] || $this->IssueFrom->data['IssueFrom']['due_date']))) {
      return null;
    }
    $date = !empty($this->IssueFrom->data['IssueFrom']['due_date']) ? $this->IssueFrom->data['IssueFrom']['due_date'] : $this->IssueFrom->data['IssueFrom']['start_date'];
    $delay = $this->data[$this->name]['delay'] + 1;
    return strtotime("+$delay day", strtotime($date));
  }
#  
#  def <=>(relation)
#    TYPES[self.relation_type][:order] <=> TYPES[relation.relation_type][:order]
#  end
#end
  function findRelations($issue) {
    $relations = $this->find('all', array(
        'conditions'=>array('or'=>array(array('issue_from_id'=>$issue['Issue']['id']),array('issue_to_id'=>$issue['Issue']['id'])))
    ));
    $result = array();
    foreach($relations as $key=>$relation) {
      $body = ($issue['Issue']['id'] == $relation['IssueRelation']['issue_from_id']) ? 'IssueFrom' : 'IssueTo';
      $rel = ($issue['Issue']['id'] == $relation['IssueRelation']['issue_from_id']) ? 'IssueTo' : 'IssueFrom';
      $assoc = $this->$rel->find('first', array(
        'conditions'=>array("$rel.id"=>$relation[$rel]['id']),
        'fields'=>array('Project.*', 'Status.*', 'Tracker.*'),
        'recursive'=>0
      ));
      $result[$key] = array(
        'IssueFrom'=>array('Issue'=>$relation['IssueFrom']), 
        'IssueTo'=>array('Issue'=>$relation['IssueTo']),
        'IssueRelation'=>$relation['IssueRelation']
      );
      if(!empty($assoc)) {
        $result[$key][$rel] = array_merge($result[$key][$rel], $assoc);
        $result[$key][$body] = array_merge($result[$key][$body], $issue);
      }
    }
    return $result;
  }
}
