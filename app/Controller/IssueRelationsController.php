<?php

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
        switch ($this->request->action) {
            case 'add' :
                $this->_find_issue($this->request->params['pass'][0]);
                break;
            case 'destroy' :
                $this->_find_issue($this->request->params['named']['issue_id']);
                break;
        }
        if (!empty($this->_issue)) {
            $this->request->params['project_id'] = $this->_issue['Project']['identifier'];
        }
        return parent::beforeFilter();
    }

    function add($issue_id)
    {
        if ($this->RequestHandler->isAjax()) {
            Configure::write('debug', 0);
        }
        if (!empty($this->request->data)) {
            $this->IssueRelation->create($this->request->data);
            $this->IssueRelation->data['IssueRelation']['issue_from_id'] = $this->_issue['Issue']['id'];
            if ($this->IssueRelation->save()) {
                $this->request->data = array();
            }
        }
        if ($this->request->is('ajax')) {
            $issue_relations = $this->IssueRelation->findRelations($this->_issue);
            $this->set(compact('issue_relations'));
            $this->layout = 'ajax';
        } else {
            $this->redirect(array('controller' => 'issues', 'action' => 'show', $this->_issue['Issue']['id']));
        }
    }

    function destroy($relation_id)
    {
        if ($this->request->is('ajax')) {
            Configure::write('debug', 0);
        }

        $relation = $this->IssueRelation->read(null, $relation_id);
        if ($relation) {
            $this->IssueRelation->delete();
        }

        $this->redirect(array('controller' => 'issues', 'action' => 'show', $this->_issue['Issue']['id']));
    }

#private
    function _find_issue($id)
    {
        if ($this->_issue = $this->IssueRelation->IssueFrom->find('first', array(
            'conditions' => array('IssueFrom.id' => $id),
            'recursive' => 0
        ))
        ) {
            $this->_issue['Issue'] = $this->_issue['IssueFrom'];
            unset($this->_issue['IssueFrom']);
            $this->set(array('issue' => $this->_issue));
            return $this->_issue;
        } else {
            throw new NotFoundException();

        }
    }
}