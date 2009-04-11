<?php
## redMine - project management software
## Copyright (C) 2006  Jean-Philippe Lang
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
#class RolesController < ApplicationController
class RolesController extends AppController {
  var $name = 'Roles';
  var $components = array('RequestHandler');
  var $uses = array('Role','Permission');
  
#  before_filter :require_admin
#
#  verify :method => :post, :only => [ :destroy, :move ],
#         :redirect_to => { :action => :list }
#
#  def index
  function index() {
    $this->lists();
    if (!$this->RequestHandler->isAjax()) {
      $this->render('list');
    }
  }
#
#  def list
  function lists() {
#    @role_pages, @roles = paginate :roles, :per_page => 25, :order => 'builtin, position'
#    render :action => "list", :layout => false if request.xhr?
#  end
    $this->params['show'] = 25;
    $this->params['sort'] = 'builtin,position';
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
  function add() {
    if (!empty($this->data)) {
      
    }
  }

#
#  def edit
  function edit($id) {
#    @role = Role.find(params[:id])
    $role = $this->Role->findById($id);
    $this->set('role', $role);

    $roles = $this->Role->find('list', array('fields' => array('Role.id', 'Role.name')));
    $this->set('roles', $roles);
                                                
    //    $permissions = $this->Permission->permissions;
    //    $this->set('permissions', $permissions);

    $permissions = $this->Permission->setable_permissions();
    $this->set('permissions', $permissions);



    $project_module_name = array('issue_tracking' => 'Issue tracking',
                                 'time_tracking' => 'Time tracking',
                                 'news' => 'News',
                                 'documents' => 'Documents',
                                 'files' => 'Files',
                                 'wiki' => 'Wiki',
                                 'repository' => 'Repository',
                                 'boards' => 'Boards');
    $this->set('project_module_name', $project_module_name);

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
    $this->set('permission_name', $permission_name);
    $permissions_array = $this->Role->permissions($role['Role']['permissions']);
    $this->set('permissions_array', $permissions_array);

#    if request.post? and @role.update_attributes(params[:role])
#      flash[:notice] = l(:notice_successful_update)
#      redirect_to :action => 'list'
#    end

#    @permissions = @role.setable_permissions


#  end
  }
#
#  def destroy
#    @role = Role.find(params[:id])
#    @role.destroy
#    redirect_to :action => 'list'
#  rescue
#    flash[:error] = 'This role is in use and can not be deleted.'
#    redirect_to :action => 'index'
#  end
#  
#  def move
#    @role = Role.find(params[:id])
#    case params[:position]
#    when 'highest'
#      @role.move_to_top
#    when 'higher'
#      @role.move_higher
#    when 'lower'
#      @role.move_lower
#    when 'lowest'
#      @role.move_to_bottom
#    end if params[:position]
#    redirect_to :action => 'list'
#  end
#  
#  def report    
#    @roles = Role.find(:all, :order => 'builtin, position')
#    @permissions = Redmine::AccessControl.permissions.select { |p| !p.public? }
#    if request.post?
#      @roles.each do |role|
#        role.permissions = params[:permissions][role.id.to_s]
#        role.save
#      end
#      flash[:notice] = l(:notice_successful_update)
#      redirect_to :action => 'list'
#    end
#  end
#end
}
