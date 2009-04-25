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
#class VersionsController < ApplicationController
#  menu_item :roadmap
#  before_filter :find_project, :authorize
#
#  def show
#  end
#  
#  def edit
#    if request.post? and @version.update_attributes(params[:version])
#      flash[:notice] = l(:notice_successful_update)
#      redirect_to :controller => 'projects', :action => 'settings', :tab => 'versions', :id => @project
#    end
#  end
#
#  def destroy
#    @version.destroy
#    redirect_to :controller => 'projects', :action => 'settings', :tab => 'versions', :id => @project
#  rescue
#    flash[:error] = l(:notice_unable_delete_version)
#    redirect_to :controller => 'projects', :action => 'settings', :tab => 'versions', :id => @project
#  end
#  
#  def status_by
#    respond_to do |format|
#      format.html { render :action => 'show' }
#      format.js { render(:update) {|page| page.replace_html 'status_by', render_issue_status_by(@version, params[:status_by])} }
#    end
#  end
#
#private
#  def find_project
#    @version = Version.find(params[:id])
#    @project = @version.project
#  rescue ActiveRecord::RecordNotFound
#    render_404
#  end  
#end


class VersionsController extends AppController
{
  var $name = 'Versions';
  var $uses = array('User', 'Version', 'Wiki');
  var $helpers = array('Time');
#  menu_item :roadmap
#  before_filter :find_project, :authorize

  function show($id)
  {
    $this->Version->id = $id;
    $this->data = $this->Version->read();

    $issues = $this->Version->FixedIssue->find('all');
    foreach($issues as $key=>$issue) {
      $issues[$key]['Issue'] = $issue['FixedIssue'];
    }
    $this->set('issues', $issues); // @FIXME
    $fixed_issue_count = count($issues);
    $this->set('fixed_issue_count', $fixed_issue_count);
    $wiki_content = $this->Wiki->Page->find('first',
                                                aa('conditions',
                                                   aa('Page.title',
                                                   $this->data['Version']['wiki_page_title'])));
    $this->set('wiki_content', $wiki_content);
    /*
<% issues = @version.fixed_issues.find(:all,
                                       :include => [:status, :tracker],
                                       :order => "#{Tracker.table_name}.position, #{Issue.table_name}.id") %>
     */
  }

  function edit($id)
  {
#    if request.post? and @version.update_attributes(params[:version])
#      flash[:notice] = l(:notice_successful_update)
#      redirect_to :controller => 'projects', :action => 'settings', :tab => 'versions', :id => @project
#    end
    $this->Version->id = $id;

    if(!empty($this->data)) {
      if($this->Version->save($this->data, true, array('name', 'description', 'wiki_page_title', 'effective_date'))) {
        $this->Session->setFlash(__('Successful update.'));
        $this->redirect(array('controller'=>'versions', 'action'=>'show', 'id'=>$this->Version->id));
      }
    }

    if ($id !== null) {
      $this->data = $this->Version->read();
    }
  }

  function destroy($id)
  {
    if ($this->Version->del($id)) {
    } else {
      $this->Session->setFlash(__('Unable to delete version.'));
    }
    $this->redirect(array('controller'=>'versions', 'action'=>'show', 'id'=>$this->Version->id));
#    @version.destroy
#    redirect_to :controller => 'projects', :action => 'settings', :tab => 'versions', :id => @project
#  rescue
#    flash[:error] = l(:notice_unable_delete_version)
#    redirect_to :controller => 'projects', :action => 'settings', :tab => 'versions', :id => @project
  }

  function status_by($id)
  {
#    respond_to do |format|
#      format.html { render :action => 'show' }
#      format.js { render(:update) {|page| page.replace_html 'status_by', render_issue_status_by(@version, params[:status_by])} }
#    end

  }

  // private
  function find_project($id)
  {
    $this->version = $this->Version->find_By_Id($id);
    $this->project = $this->version['Project'];
#    @version = Version.find(params[:id])
#    @project = @version.project
#  rescue ActiveRecord::RecordNotFound
#    render_404
#  end  
  }

}

