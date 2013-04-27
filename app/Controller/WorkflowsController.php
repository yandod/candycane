<?php

class WorkflowsController extends AppController
{
    var $name = 'Workflows';
    var $uses = array('Workflow', 'Role', 'Tracker', 'IssueStatus');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->require_admin();
    }

    function index()
    {
        $roles = $this->Role->find('all', array('order' => array('builtin', 'position')));
        $trackers = $this->Tracker->find('all', array('order' => 'position'));

        $this->set('roles', $roles);
        $this->set('trackers', $trackers);

        $data = $this->Workflow->find('all', array('group' => array('Workflow.role_id', 'Workflow.tracker_id'),
            'fields' => array('Workflow.role_id', 'Workflow.tracker_id', 'count(id) as "Workflow__count"')));

        // initialize
        foreach ($trackers as $tracker) {
            $tracker_id = $tracker['Tracker']['id'];
            foreach ($roles as $role) {
                $role_id = $role['Role']['id'];
                $counts[$tracker_id][$role_id] = 0;
            }
        }

        // workflow counts
        foreach ($data as $dt) {
            $role_id = $dt['Workflow']['role_id'];
            $tracker_id = $dt['Workflow']['tracker_id'];
            $count = $dt[0]['Workflow__count'];
            $counts[$tracker_id][$role_id] = $count;
        }
        $this->set('counts', $counts);
        //pr($counts);
    }

    function edit()
    {
        $role_id = isset($this->request->query['role_id']) ? $this->request->query['role_id'] : null;
        $tracker_id = isset($this->request->query['tracker_id']) ? $this->request->query['tracker_id'] : null;

        $role_id = isset($this->request->data['Workflow']['role_id']) ? $this->request->data['Workflow']['role_id'] : $role_id;
        $tracker_id = isset($this->request->data['Workflow']['tracker_id']) ? $this->request->data['Workflow']['tracker_id'] : $tracker_id;

        if ($role_id != null) {
            $role = $this->Role->findById($role_id);
        } else {
            $role = null;
        }
        if ($tracker_id != null) {
            $tracker = $this->Tracker->findById($tracker_id);
        } else {
            $tracker = null;
        }
        $this->set('role', $role);
        $this->set('tracker', $tracker);

        $this->Workflow->bindModel(array('belongsTo' => array('IssueStatus' => array('className' => 'IssueStatus',
            'foreignKey' => 'new_status_id'))));
        $allowed_to = array();
        if ($role_id && $tracker_id) {
            $allowed_to = $this->Workflow->find('all', array('conditions' => array('role_id' => $role_id,
                'tracker_id' => $tracker_id),
                'order' => array('old_status_id'),
            ));
        }

        if (!empty($this->request->data)) {
            $this->Workflow->deleteAll(array('role_id' => $this->request->data['Workflow']['role_id'],
                'tracker_id' => $this->request->data['Workflow']['tracker_id']));
            foreach ($this->request->data['issue_status'] as $old => $news) {
                foreach ($news as $new) {
                    $data = array('role_id' => $this->request->data['Workflow']['role_id'],
                        'tracker_id' => $this->request->data['Workflow']['tracker_id'],
                        'old_status_id' => $old,
                        'new_status_id' => $new,
                    );
                    $this->Workflow->create();
                    $this->Workflow->save($data);
                }
            }
            $this->Session->setFlash(__('Successful update.'), 'default', array('class' => 'flash flash_notice'));
            $this->redirect('edit?role_id=' . h($role_id) . '&tracker_id=' . $tracker_id);
        }

        $roles_options = $this->Role->find('list', array('field' => array('id', 'name'),
            'order' => array('builtin', 'position')));
        $trackers_options = $this->Tracker->find('list', array('field' => array('id', 'name'),
            'order' => array('position')));
        $statuses = $this->IssueStatus->find('all', array('order' => array('position')));
        $stasus_length = count($statuses);

        for ($i = 0; $i < count($statuses); ++$i) {
            $issue_status_id = $statuses[$i]['IssueStatus']['id'];
            $new_status_ids_allowed[$issue_status_id] = array();
        }
        for ($j = 0; $j < count($allowed_to); ++$j) {
            $old_status_id = $allowed_to[$j]['Workflow']['old_status_id'];
            $new_status_id = $allowed_to[$j]['Workflow']['new_status_id'];
            $new_status_ids_allowed[$old_status_id][] = $new_status_id;
        }

        $this->set('new_status_ids_allowed', $new_status_ids_allowed);
        $this->set('roles_options', $roles_options);
        $this->set('trackers_options', $trackers_options);
        $this->set('statuses', $statuses);
    }
}