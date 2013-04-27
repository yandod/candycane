<?php

class IssueStatusesController extends AppController
{
    var $name = 'IssueStatuses';
    var $components = array('RequestHandler');

    function beforeFilter()
    {
        parent::beforeFilter();
        return parent::require_admin();
    }

    function index()
    {
        $this->lists();
        if (!$this->RequestHandler->isAjax()) {
            $this->render("list");
        }
    }

    function lists()
    {
        $this->request->params['named']['show'] = 25;
        $this->request->params['named']['sort'] = "position";
        $issue_statuses = $this->paginate();
        $issue_status_pages = $issue_statuses;
        $this->set(compact('issue_statuses', 'issue_status_pages'));
        if ($this->RequestHandler->isAjax()) {
            $this->render("list", "ajax");
        }
    }

    function move($id)
    {
        $this->IssueStatus->read(null, $id);
        if (!empty($this->request->params['named']['position'])) {
            switch ($this->request->params['named']['position']) {
                case 'highest' :
                    $this->IssueStatus->move_to_top();
                    break;
                case 'higher' :
                    $this->IssueStatus->move_higher();
                    break;
                case 'lower' :
                    $this->IssueStatus->move_lower();
                    break;
                case 'lowest' :
                    $this->IssueStatus->move_to_bottom();
                    break;
            }
            $this->redirect('index');
        }
    }

    function edit($id = false)
    {
        if ($id == false) {
            $this->Session->setFlash(__("Invalid id"), 'default', array('class' => 'flash flash_error'));
            $this->redirect('index');
        }
        if (!empty($this->request->data)) {
            $this->IssueStatus->id = $id;
            if (!$this->IssueStatus->exists()) {
                $this->Session->setFlash(__("Invalid id"), 'default', array('class' => 'flash flash_error'));
                $this->redirect('index');
            }
            if ($this->IssueStatus->save($this->request->data)) {
                $this->Session->setFlash(__('Successful update.'), 'default', array('class' => 'flash flash_notice'));
                $this->redirect('index');
            } else {
                $this->Session->setFlash(__('Please correct errors below.'), 'default', array('class' => 'flash flash_error'));
            }
        }
        if (empty($this->request->data)) {
            $this->request->data = $this->IssueStatus->read(null, $id);
        }
    }

    function add()
    {
        if (!empty($this->request->data)) {
            $this->IssueStatus->create();
            if ($this->IssueStatus->save($this->request->data)) {
                $this->Session->setFlash(__('Successful update.'), 'default', array('class' => 'flash flash_notice'));
                $this->redirect('index');
            } else {
                $this->Session->setFlash(__('Please correct errors below.'), 'default', array('class' => 'flash flash_error'));
            }
        }
        $this->render("new");
    }

    function destroy($id = false)
    {
        if ($id == false) {
            $this->Session->setFlash(__("Invalid id"), 'default', array('class' => 'flash flash_error'));
            $this->redirect('index');
        }
        if ($this->IssueStatus->delete($id)) {
            $this->Session->setFlash(__('Successful deletion.'), 'default', array('class' => 'flash flash_notice'));
        } else {
            $this->Session->setFlash(sprintf(__('There was an error deleting with id: %1$d'), $id));
        }
        $this->redirect('index');
    }
}