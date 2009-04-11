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
  var $helpers = array('Users', 'Sort', 'Ajax');
  var $components = array('Sort');

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
    $this->Sort->sort_init('login', 'asc');
    $this->Sort->sort_update(
      array('login', 'firstname', 'lastname', 'mail', 'admin', 'created_on', 'last_login_on')
    );

    if (isset($this->params['url']['status'])) {
      $status = (int)$this->params['url']['status'];
    } else {
      $status = 1;
    }

    $this->set('status', $status);

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
    $this->set('user_list', $users);

    $status_counts = $this->User->find('all',
      array(
        'group' => array('status'),
        'fields' => array('COUNT(*) as cnt'),
      )
    );

    foreach (array(1, 2, 3) as $key) {
      if (!isset($status_counts[$key][0]['cnt'])) {
        $status_counts[$key][0]['cnt'] = 0;
      }
    }

    $status_option = array(
      '' => __('label_all', true),
      1  => __('status_active', true) . ' (' . (int)$status_counts[1][0]['cnt'] . ')',
      2  => __('status_registered', true) . ' (' . (int)$status_counts[2][0]['cnt'] . ')',
      3  => __('status_locked', true) . ' (' . (int)$status_counts[3][0]['cnt'] . ')',
    );

    $this->set('status_option', $status_option);

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
