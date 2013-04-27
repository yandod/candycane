<?php

class RolesController extends AppController
{
    var $name = 'Roles';
    var $components = array('RequestHandler');
    var $uses = array('Role', 'Permission');

#  verify :method => :post, :only => [ :destroy, :move ],
#         :redirect_to => { :action => :list }
#

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->require_admin();
    }

    function index()
    {
        $this->lists();
        if (!$this->RequestHandler->isAjax()) {
            $this->render('list');
        }
    }

    function lists()
    {
#    @role_pages, @roles = paginate :roles, :per_page => 25, :order => 'builtin, position'
#    render :action => "list", :layout => false if request.xhr?
        $this->request->params['show'] = 25;
        $this->request->params['order'] = 'builtin,position';
        $this->paginate = array('order' => 'position');
        $roles = $this->paginate();
        $this->set('roles', $roles);
        $this->set('role_pages', $roles);
        if ($this->RequestHandler->isAjax()) {
            $this->render('list', 'ajax');
        }
    }

#
#  def new
#    # Prefills the form with 'Non member' role permissions
#    @role = Role.new(params[:role] || {:permissions => Role.non_member.permissions})
#    if request.post? && @role.save
#      # workflow copy
#      if !params[:copy_workflow_from].blank? && (copy_from = Role.find_by_id(params[:copy_workflow_from]))
#        @role.workflows.copy(copy_from)
#      end
#      flash[:notice] = l(:notice_successful_create)
#      redirect_to :action => 'list'
#    end
#    @permissions = @role.setable_permissions
#    @roles = Role.find :all, :order => 'builtin, position'
#  end
    function add()
    {
        $roles = $this->Role->find('list', array('fields' => array('Role.id', 'Role.name')));
        $this->set('roles', $roles);
        $permissions = $this->Permission->setable_permissions();


        $role = $this->Role->non_member();
        if (!empty($role)) {
            $role['Role']['name'] = '';
            $role['Role']['new_record'] = true;
            $role['Role']['builtin'] = false;
        }

        $permissions_array = $this->Role->permissions($role['Role']['permissions']);
        $this->set('permissions_array', $permissions_array);

        $this->set('permissions', $permissions);
        $this->set('role', $role);

        $project_module_name = $this->_project_module_name();
        $this->set('project_module_name', $project_module_name);

        $permission_name = $this->_permission_name();
        $this->set('permission_name', $permission_name);

        if (empty($this->request->data)) {
            $this->render('new');
        } else {
            $permissions = $this->Role->convert_permissions($this->request->data['Role']['permissions']);
            $this->request->data['Role']['permissions'] = $permissions;
            $max = $this->Role->find('first', array('fields' => "max(position) + 1 AS max"));
            $data = array('name' => $this->request->data['Role']['name'],
                'position' => $max[0]['max'],
                'assignable' => $this->request->data['Role']['assignable'],
                'permissions' => $this->request->data['Role']['permissions'],
                'builtin' => 0,
            );

            $this->Role->create();
            if ($this->Role->save($data)) {
                $this->Session->setFlash(__('Successful creation.'), 'default', array('class' => 'flash flash_notice'));
                $this->redirect('index');
            } else {
                $this->render('new');
            }
        }
    }

    function edit($id = null)
    {
        if (isset($id)) {
            $role = $this->Role->findById($id);
            $this->set('role', $role);

            $roles = $this->Role->find('list', array('fields' => array('Role.id', 'Role.name')));
            $this->set('roles', $roles);
        }

        $permissions = $this->Permission->setable_permissions();
        $this->set('permissions', $permissions);

        $permissions_array = $this->Role->permissions($role['Role']['permissions']);
        $this->set('permissions_array', $permissions_array);

        $project_module_name = $this->_project_module_name();
        $this->set('project_module_name', $project_module_name);

        $permission_name = $this->_permission_name();
        $this->set('permission_name', $permission_name);

        if (!empty($this->request->data)) {
            $permissions = $this->Role->convert_permissions($this->request->data['Role']['permissions']);
            $this->request->data['Role']['permissions'] = $permissions;

            $data = array('id' => $id,
                'name' => $this->request->data['Role']['name'],
                'assignable' => $this->request->data['Role']['assignable'],
                'permissions' => $this->request->data['Role']['permissions'],
            );

            $this->Role->create();
            if ($this->Role->save($data, false, array('id', 'name', 'assignable', 'permissions'))) {
                $this->Session->setFlash(__('Successful update.'), 'default', array('class' => 'flash flash_notice'));
                $this->redirect('index');
            }
            return;
        }
    }


    function destroy($id = false)
    {
        if ($id == false) {
            $this->Session->setFlash(__("Invalid id"), 'default', array('class' => 'flash flash_error'));
            $this->redirect('index');
        }
        if ($this->Role->delete($id)) {
            $this->Session->setFlash(__('Successful deletion.'), 'default', array('class' => 'flash flash_notice'));
        } else {
            $this->Session->setFlash(__('This role is in use and can not be deleted.'), 'default', array('class' => 'flash flash_error'));
        }
        $this->redirect('index');
    }

    function move($id)
    {
        $this->Role->read(null, $id);
        if (!empty($this->request->params['named']['position'])) {
            switch ($this->request->params['named']['position']) {
                case 'highest':
                    $this->Role->move_to_top();
                    break;
                case 'higher':
                    $this->Role->move_higher();
                    break;
                case 'lower':
                    $this->Role->move_lower();
                    break;
                case 'lowest':
                    $this->Role->move_to_bottom();
                    break;
            }
            $this->redirect('index');
        }
    }

    function report()
    {
        $roles = $this->Role->find('all', array('order' => array('builtin', 'position')));

        for ($i = 0; $i < count($roles); ++$i) {
            $roles[$i]['Role']['permissions'] = $this->Role->permissions($roles[$i]['Role']['permissions']);
            $tmp = $this->Permission->setable_permissions_name($roles[$i]['Role']['id']);
            $roles[$i]['Role']['setable_permissions'] = $tmp;
        }
        $this->set('roles', $roles);

        $permissions = $this->Permission->non_public_permissions();
        ksort($permissions);
        $this->set('permissions', $permissions);

        $project_module_name = $this->_project_module_name();
        $this->set('project_module_name', $project_module_name);

        $permission_name = $this->_permission_name();
        $this->set('permission_name', $permission_name);

        if (!empty($this->request->data)) {
            foreach ($this->request->data['permissions'] as $id => $perms) {
                $data = array('id' => $id,
                    'permissions' => $this->Role->convert_permissions($perms));
                $this->Role->create();
                $this->Role->save($data, false, array('id', 'permissions'));
            }
            $this->Session->setFlash(__('Successful update.'), 'default', array('class' => 'flash flash_notice'));
            $this->redirect('index');
        }
    }


    function _project_module_name()
    {
        $project_module_name = array('issue_tracking' => 'Issue tracking',
            'time_tracking' => 'Time tracking',
            'news' => 'News',
            'documents' => 'Documents',
            'files' => 'Files',
            'wiki' => 'Wiki',
            'repository' => 'Repository',
            'boards' => 'Boards');
        return $project_module_name;
    }

    function _permission_name()
    {
        $permission_name = array('edit_project' => 'Edit project',
            'select_project_modules' => 'Select project modules',
            'manage_members' => 'Manage members',
            'manage_versions' => 'Manage versions',
            'manage_categories' => 'Manage issue categories',
            'add_issues' => 'Add issues',
            'edit_issues' => 'Edit issues',
            'manage_issue_relations' => 'Manage issue relations',
            'add_issue_notes' => 'Add notes',
            'edit_issue_notes' => 'Edit notes',
            'edit_own_issue_notes' => 'Edit own notes',
            'move_issues' => 'Move issues',
            'delete_issues' => 'Delete issues',
            'manage_public_queries' => 'Manage public queries',
            'save_queries' => 'Save queries',
            'view_gantt' => 'View gantt chart',
            'view_calendar' => 'View calender',
            'view_issue_watchers' => 'View watchers list',
            'add_issue_watchers' => 'Add watchers',
            'log_time' => 'Log spent time',
            'view_time_entries' => 'View spent time',
            'edit_time_entries' => 'Edit time logs',
            'edit_own_time_entries' => 'Edit own time logs',
            'manage_news' => 'Manage news',
            'comment_news' => 'Comment news',
            'manage_documents' => 'Manage documents',
            'view_documents' => 'View documents',
            'manage_files' => 'Manage files',
            'view_files' => 'View files',
            'manage_wiki' => 'Manage wiki',
            'rename_wiki_pages' => 'Rename wiki pages',
            'delete_wiki_pages' => 'Delete wiki pages',
            'view_wiki_pages' => 'View wiki',
            'view_wiki_edits' => 'View wiki history',
            'edit_wiki_pages' => 'Edit wiki pages',
            'delete_wiki_pages_attachments' => 'Delete attachments',
            'protect_wiki_pages' => 'Protect wiki pages',
            'manage_repository' => 'Manage repository',
            'browse_repository' => 'Browse repository',
            'view_changesets' => 'View changesets',
            'commit_access' => 'Commit access',
            'manage_boards' => 'Manage boards',
            'view_messages' => 'View messages',
            'add_messages' => 'Post messages',
            'edit_messages' => 'Edit messages',
            'edit_own_messages' => 'Edit own messages',
            'delete_messages' => 'Delete messages',
            'delete_own_messages' => 'Delete own messages',
        );
        return $permission_name;
    }
}