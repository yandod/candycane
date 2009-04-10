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
#class UsersController < ApplicationController
#  before_filter :require_admin
#
#  helper :sort
#  include SortHelper
#  helper :custom_fields
#  include CustomFieldsHelper   
#
#  def edit
#    @user = User.find(params[:id])
#    if request.post?
#      @user.admin = params[:user][:admin] if params[:user][:admin]
#      @user.login = params[:user][:login] if params[:user][:login]
#      @user.password, @user.password_confirmation = params[:password], params[:password_confirmation] unless params[:password].nil? or params[:password].empty? or @user.auth_source_id
#      if @user.update_attributes(params[:user])
#        flash[:notice] = l(:notice_successful_update)
#        # Give a string to redirect_to otherwise it would use status param as the response code
#        redirect_to(url_for(:action => 'list', :status => params[:status], :page => params[:page]))
#      end
#    end
#    @auth_sources = AuthSource.find(:all)
#    @roles = Role.find_all_givable
#    @projects = Project.find(:all, :order => 'name', :conditions => "status=#{Project::STATUS_ACTIVE}") - @user.projects
#    @membership ||= Member.new
#    @memberships = @user.memberships
#  end
#  
#  def edit_membership
#    @user = User.find(params[:id])
#    @membership = params[:membership_id] ? Member.find(params[:membership_id]) : Member.new(:user => @user)
#    @membership.attributes = params[:membership]
#    @membership.save if request.post?
#    redirect_to :action => 'edit', :id => @user, :tab => 'memberships'
#  end
#  
#  def destroy_membership
#    @user = User.find(params[:id])
#    Member.find(params[:membership_id]).destroy if request.post?
#    redirect_to :action => 'edit', :id => @user, :tab => 'memberships'
#  end
#end

/**
 * UsersController
 *
 */
class UsersController extends AppController
{
  /**
   * beforeFilter
   *
   */
  function beforeFilter()
  {
    parent::beforeFilter();
    $this->require_admin();
  }

  function index()
  {
    $this->list_();
    $this->render('list'); // unless request.xhr?
    # render :action => 'list' unless request.xhr?
  }

  /**
   * list_
   *
   * @todo list is reserved word
   */
  function list_()
  {
#    sort_init 'login', 'asc'
#    sort_update %w(login firstname lastname mail admin created_on last_login_on)

     
    if (isset($this->params['url']['status'])) {
      $this->set('status', (int)$this->params['url']['status']);
    } else {
      $this->set('status', 1);
    }

#    c = ARCondition.new(@status == 0 ? "status <> 0" : ["status = ?", @status])
#
#    unless params[:name].blank?
#      name = "%#{params[:name].strip.downcase}%"
#      c << ["LOWER(login) LIKE ? OR LOWER(firstname) LIKE ? OR LOWER(lastname) LIKE ?", name, name, name]
#    end
#    
#    @user_count = User.count(:conditions => c.conditions)
#    @user_pages = Paginator.new self, @user_count,
#								per_page_option,
#								params['page']								
 
#    @users =  User.find :all,:order => sort_clause,
#                        :conditions => c.conditions,
#                        :limit  =>  @user_pages.items_per_page,
#                        :offset =>  @user_pages.current.offset
    $users = $this->User->find('all');

    $this->set('users', $users);


    if (isset($request->xhr)) {
      $this->layout = false;
    }

    return 'list';
  }

    /**
     * add
     *
     */
    function add()
    {
      if (!$this->data) {
        # @user = User.new(:language => Setting.default_language)
      } else {
        # @user = User.new(params[:user])
        # @user.admin = params[:user][:admin] || false
        # @user.login = params[:user][:login]
        if (!isset($user['auth_source_id']) || !is_numeric($user['auth_source_id'])) {
          // @user.password, @user.password_confirmation = params[:password], params[:password_confirmation]
        }

        $result = $this->User->save($this->data);
        if ($result) {
          #        Mailer.deliver_account_information(@user, params[:password]) if params[:send_information]
          #        flash[:notice] = l(:notice_successful_create)
          #        redirect_to :action => 'list'
          $this->redirect('index');
        }
      }
      #    @auth_sources = AuthSource.find(:all)
    }
}
