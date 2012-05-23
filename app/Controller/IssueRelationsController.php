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
class IssueRelationsController extends AppController
{
  var $name = 'IssueRelations';
  var $components = array(
    'RequestHandler',
  );
  var $helpers = array(
    'Issues', 'Js' => array('Prototype')
  );
  var $_issue;

  function beforeFilter()
  {
    switch($this->request->action) {
    case 'add' :
      $this->_find_issue($this->request->params['pass'][0]);
      break;
    case 'destroy' :
      $this->_find_issue($this->request->params['named']['issue_id']);
      break;
    }
    if(!empty($this->_issue)) {
      $this->request->params['project_id'] = $this->_issue['Project']['identifier'];
    }
    return parent::beforeFilter();
  }
  
  function add($issue_id) {
    if($this->RequestHandler->isAjax()) {
      Configure::write('debug', 0);
    }
    if(!empty($this->request->data)) {
      $this->IssueRelation->create($this->request->data);
      $this->IssueRelation->data['IssueRelation']['issue_from_id'] = $this->_issue['Issue']['id'];
      if($this->IssueRelation->save()) {
        $this->request->data = array();
      }
    }
    if($this->request->is('ajax')) {
      $issue_relations = $this->IssueRelation->findRelations($this->_issue);
      $this->set(compact('issue_relations'));
      $this->layout = 'ajax';
    } else {
      $this->redirect(array('controller'=>'issues', 'action'=>'show', $this->_issue['Issue']['id']));
    }
  }
  
  function destroy($relation_id) {
    if($this->RequestHandler->isAjax()) {
      Configure::write('debug', 0);
    }
    $relation = $this->IssueRelation->read(null, $relation_id);
    if($this->RequestHandler->isPost() && $relation) {
      $this->IssueRelation->delete();
    }
    if($this->RequestHandler->isAjax()) {
      $issue_relations = $this->IssueRelation->findRelations($this->_issue);
      $this->set(compact('issue_relations'));
      $this->layout = 'ajax';
    } else {
      $this->redirect(array('controller'=>'issues', 'action'=>'show', 'id'=>$this->_issue['Issue']['id']));
    }
  }
  
#private
  function _find_issue($id)
  {
    if ($this->_issue = $this->IssueRelation->IssueFrom->find('first', array(
      'conditions'=>array('IssueFrom.id' => $id),
      'recursive'=>0
    ))) {
      $this->_issue['Issue'] = $this->_issue['IssueFrom'];
      unset($this->_issue['IssueFrom']);
      $this->set(array('issue'=>$this->_issue));
      return $this->_issue;
    } else {
      throw new NotFoundException();

    }
  }
}
