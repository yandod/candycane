<?php

class MembersController extends AppController
{
    public $name = 'Members';
    public $components = array('RequestHandler');
    public $helpers = array('Js' => array('Prototype'),
        'Candy',
        'Form');

#  before_filter :find_member, :except => :new
#  before_filter :find_project, :only => :new
#  before_filter :authorize

    protected function _prepareSettingTabMembers()
    {
        $members = $this->Member->find(
            'all',
            array(
                'conditions' => array(
                    'project_id' => $this->_project['Project']['id']
                ),
                'order' => 'Role.position'
            )
        );
        $this->set('members', $members);
        $roles = $this->Member->Role->find_all_givable();
        $this->set('roles_data', $roles);

        $users = $this->Member->User->find(
            'all',
            array(
                'conditions' => array(
                    'status' => USER_STATUS_ACTIVE
                ),
                'recursive' => -1
            )
        );
        $this->set('users_data', $users);
    }

    public function add()
    {
        if ($this->request->data) {
            $this->request->data['Member']['project_id'] = $this->_project['Project']['id'];
            $this->Member->save($this->request->data, true, array('project_id', 'user_id', 'role_id'));
        }
        $this->_prepareSettingTabMembers();
        $this->render('/Elements/projects/settings/members');
    }

    public function edit()
    {
        if ($this->request->data) {
            $this->Member->id = $this->request->params['id'];
            $this->Member->saveField('role_id', $this->request->data['Member']['role_id']);
        }

        $this->_prepareSettingTabMembers();
        $this->render('/Elements/projects/settings/members');
    }

    public function destroy()
    {
        $this->Member->delete($this->request->params['id'], false);
        $this->_prepareSettingTabMembers();
        $this->render('/Elements/projects/settings/members');
    }
}