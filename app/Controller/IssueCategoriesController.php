<?php

class IssueCategoriesController extends AppController
{
    public function edit()
    {
        if ($this->request->data) {
            $this->request->data['IssueCategory']['id'] = $this->request->params['id'];
            $this->request->data['IssueCategory']['project_id'] = $this->_project['Project']['id'];
            if ($this->IssueCategory->save($this->request->data, true, array('name', 'assigned_to_id'))) {
                $this->Session->setFlash(__('Successful update.'), 'default', array('class' => 'flash flash_notice'));
                $this->redirect(
                    array(
                        'controller' => 'projects',
                        'action' => 'settings',
                        '?' => 'tab=categories',
                        'project_id' => $this->_project['Project']['identifier'],
                    )
                );
            }
        }
        $issue_category_data = $this->IssueCategory->find(
            'first',
            array(
                'conditions' => array(
                    'IssueCategory.id' => $this->request->params['id']
                )
            )
        );
        $this->set('issue_category_data', $issue_category_data);
    }

    public function destroy()
    {
        App::uses('Issue', 'Model');
        $Issue = new Issue();
        $issue_count = $Issue->find(
            'count',
            array(
                'conditions' => array(
                    'category_id' => $this->request->params['id']
                )
            )
        );

        if ($issue_count == 0) {
            $this->IssueCategory->delete($this->request->params['id']);
            $this->redirect(
                array(
                    'controller' => 'projects',
                    'action' => 'settings',
                    '?' => 'tab=categories',
                    'project_id' => $this->_project['Project']['identifier'],
                )
            );
        } elseif ($this->request->data) {
            $reassgin_to = null;
            if ($this->request->data['IssueCategory']['todo'] == 'reassgin_to') {
                $reassgin_to = $this->request->data['IssueCategory']['reassign_to_id'];
            }
            $this->IssueCategory->del_with_reassgin($this->request->params['id'], $reassgin_to);
            $this->redirect(
                array(
                    'controller' => 'projects',
                    'action' => 'settings',
                    '?' => 'tab=categories',
                    'project_id' => $this->_project['Project']['identifier'],
                )
            );
        }
        $issue_category_data = $this->IssueCategory->find(
            'first',
            array(
                'conditions' => array(
                    'IssueCategory.id' => $this->request->params['id']
                )
            )
        );
        $this->set('issue_category_data', $issue_category_data);
        $this->set('issue_count', $issue_count);
    }
}