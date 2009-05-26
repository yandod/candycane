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
#class AdminController < ApplicationController

#  helper :sort
#  include SortHelper 
#
#  def index
#    @no_configuration_data = Redmine::DefaultData::Loader::no_data?
#  end
# 
  
#  def plugins
#    @plugins = Redmine::Plugin.all
#  end
#  
#  # Loads the default configuration
#  # (roles, trackers, statuses, workflow, enumerations)
#  def default_configuration
#    if request.post?
#      begin
#        Redmine::DefaultData::Loader::load(params[:lang])
#        flash[:notice] = l(:notice_default_data_loaded)
#      rescue Exception => e
#        flash[:error] = l(:error_can_t_load_default_data, e.message)
#      end
#    end
#    redirect_to :action => 'index'
#  end
#  
#  def test_email
#    raise_delivery_errors = ActionMailer::Base.raise_delivery_errors
#    # Force ActionMailer to raise delivery errors so we can catch it
#    ActionMailer::Base.raise_delivery_errors = true
#    begin
#      @test = Mailer.deliver_test(User.current)
#      flash[:notice] = l(:notice_email_sent, User.current.mail)
#    rescue Exception => e
#      flash[:error] = l(:notice_email_error, e.message)
#    end
#    ActionMailer::Base.raise_delivery_errors = raise_delivery_errors
#    redirect_to :controller => 'settings', :action => 'edit', :tab => 'notifications'
#  end
#  
#  def info
#    @db_adapter_name = ActiveRecord::Base.connection.adapter_name
#    @flags = {
#      :default_admin_changed => User.find(:first, :conditions => ["login=? and hashed_password=?", 'admin', User.hash_password('admin')]).nil?,
#      :file_repository_writable => File.writable?(Attachment.storage_path),
#      :plugin_assets_writable => File.writable?(Engines.public_directory),
#      :rmagick_available => Object.const_defined?(:Magick)
#    }
#  end  
#end
//Configure::write('Config.language',"ja");

class AdminController extends AppController {

  var $name = 'Admin';
  var $uses = array('Project');
  var $helpers = array('Candy');
  var $components = array('Sort');

  /**
   * beforeFilter
   *
   * # before_filter :require_admin
   */
  function beforeFilter()
  {
    parent::beforeFilter();
    $this->require_admin();
  }

  function index()
  {
    // this is dummy user
    //$this->set('currentuser','suzuki');
  }

  /**
   * projects
   *
   */
  function projects()
  {
    $this->Sort->sort_init('name', 'asc');
    $this->Sort->sort_update(
      array('name', 'is_public', 'created_on')
    );

    if (isset($this->params['url']['status'])) {
      $status = (int)$this->params['url']['status'];
    } else {
      $status = 1;
    }

    $this->set('status', $status);

    $status_options = array(
      '' => __('all', true),
      1  => __('active', true),
    );

    $this->set('status_options', $status_options);

    if ($status == '1') {
      $condition = array('Project.status' => $status);
    } else {
      $condition = array();
    }

    $name = null;
    if(!empty($this->params['url']['name'])) {
      $name = $this->params['url']['name'];
      $q_name = "%{$name}%";
      $condition['LOWER(Project.identifier) LIKE ? OR LOWER(Project.name) LIKE ?'] = array($q_name, $q_name);
    } 

    $this->set('name', $name);

#    @project_count = Project.count(:conditions => c.conditions)
#    @project_pages = Paginator.new self, @project_count,
#               per_page_option,
#               params['page']                
#    @projects = Project.find :all, :order => sort_clause,
#                        :conditions => c.conditions,
#           :limit  =>  @project_pages.items_per_page,
#           :offset =>  @project_pages.current.offset
#

    // @todo fix limit count
    $projects = $this->Project->find('all',
      array(
        'recursive' => 0,
        'conditions' => $condition,
        'limit' => 10,
      )
    );

    $this->set('projects', $projects);
#    render :action => "projects", :layout => false if request.xhr?

   }

  function plugins()
  {
    $this->set('currentuser','suzuki');
    
  }

  function default_configration()
  {
  }

  function test_email()
  {
  }

  /**
   * info
   *
   */
  function info()
  {
    $db =& ConnectionManager::getDataSource($this->Project->useDbConfig);
    $this->set('db_driver', $db->config['driver']);

/*    $this->set('currentuser','suzuki'); */
/*    App::import('Vendor', 'candycane'); */
/*    $Candycane = new Candycane; */
/*    $this->set('Candycane', $Candycane); */

  }


}
