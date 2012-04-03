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
class MembersController extends AppController
{
  var $name = 'Members';
  var $components = array('RequestHandler');
#  before_filter :find_member, :except => :new
#  before_filter :find_project, :only => :new
#  before_filter :authorize

  function _prepareSettingTabMembers()
  {
    $members = $this->Member->find('all',aa('conditions',aa('project_id',$this->_project['Project']['id']),'order','Role.position'));
    $this->set('members',$members);

    $roles = $this->Member->Role->find_all_givable();
    $this->set('roles_data',$roles);
    
    $users = $this->Member->User->find('all',aa('conditions',aa('status',USER_STATUS_ACTIVE), 'recursive',-1));
    $this->set('users_data',$users);
  }
  function add()
  {
    if ($this->request->data) {
      $this->request->data['Member']['project_id'] = $this->_project['Project']['id'];
      $this->Member->save($this->request->data,true,array('project_id','user_id','role_id'));
    }
    $this->_prepareSettingTabMembers();
    $this->render('/elements/projects/settings/members');
  }
  
  function edit()
  {
    if ($this->request->data) {
      $this->Member->id = $this->request->params['id'];
      $this->Member->saveField('role_id',$this->request->data['Member']['role_id']);   
    }
    
    $this->_prepareSettingTabMembers();
    $this->render('/elements/projects/settings/members');  
  }

  function destroy()
  {
    $this->Member->del($this->request->params['id'],false);
    
    $this->_prepareSettingTabMembers();
    $this->render('/elements/projects/settings/members');  
  }

#private
#  def find_project
#    @project = Project.find(params[:id])
#  rescue ActiveRecord::RecordNotFound
#    render_404
#  end
#  
#  def find_member
#    @member = Member.find(params[:id]) 
#    @project = @member.project
#  rescue ActiveRecord::RecordNotFound
#    render_404
#  end
}
