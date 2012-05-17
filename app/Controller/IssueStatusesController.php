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
#class IssueStatusesController < ApplicationController
#  before_filter :require_admin
#
#  verify :method => :post, :only => [ :destroy, :create, :update, :move ],
#         :redirect_to => { :action => :list }
#         
#  def index
#    list
#    render :action => 'list' unless request.xhr?
#  end
#
#  def list
#    @issue_status_pages, @issue_statuses = paginate :issue_statuses, :per_page => 25, :order => "position"
#    render :action => "list", :layout => false if request.xhr?
#  end
#
#  def new
#    @issue_status = IssueStatus.new
#  end
#
#  def create
#    @issue_status = IssueStatus.new(params[:issue_status])
#    if @issue_status.save
#      flash[:notice] = l(:notice_successful_create)
#      redirect_to :action => 'list'
#    else
#      render :action => 'new'
#    end
#  end
#
#  def edit
#    @issue_status = IssueStatus.find(params[:id])
#  end
#
#  def update
#    @issue_status = IssueStatus.find(params[:id])
#    if @issue_status.update_attributes(params[:issue_status])
#      flash[:notice] = l(:notice_successful_update)
#      redirect_to :action => 'list'
#    else
#      render :action => 'edit'
#    end
#  end
#  
#  def move
#    @issue_status = IssueStatus.find(params[:id])
#    case params[:position]
#    when 'highest'
#      @issue_status.move_to_top
#    when 'higher'
#      @issue_status.move_higher
#    when 'lower'
#      @issue_status.move_lower
#    when 'lowest'
#      @issue_status.move_to_bottom
#    end if params[:position]
#    redirect_to :action => 'list'
#  end
#
#  def destroy
#    IssueStatus.find(params[:id]).destroy
#    redirect_to :action => 'list'
#  rescue
#    flash[:error] = "Unable to delete issue status"
#    redirect_to :action => 'list'
#  end  	
#end
class IssueStatusesController extends AppController {
  var $name = 'IssueStatuses';
  var $components = array('RequestHandler');

  function beforeFilter() {
    parent::beforeFilter();
    return parent::require_admin();
  }
  function index() {
    $this->lists();
    if(!$this->RequestHandler->isAjax()) {
      $this->render("list");
    }
  }
  function lists() {
    $this->request->params[ 'named' ]['show'] = 25;
    $this->request->params[ 'named' ]['sort'] = "position";
    $issue_statuses = $this->paginate();
    $issue_status_pages = $issue_statuses;
    $this->set(compact('issue_statuses', 'issue_status_pages'));
    if($this->RequestHandler->isAjax()) {
      $this->render("list", "ajax");
    }
  }
  function move($id) {
    $this->IssueStatus->read(null, $id);
    if(!empty($this->request->params['named']['position'])) {
      switch($this->request->params['named']['position']) {
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
  function edit($id = false) {
    if($id == false) {
      $this->Session->setFlash(__("Invalid id"), 'default', array('class'=>'flash flash_error'));
      $this->redirect('index');
    }
    if(!empty($this->request->data)) {
      $this->IssueStatus->id = $id;
      if (!$this->IssueStatus->exists()) {
        $this->Session->setFlash(__("Invalid id"), 'default', array('class'=>'flash flash_error'));
        $this->redirect('index');
      }
      if($this->IssueStatus->save($this->request->data)) {
        $this->Session->setFlash(__('Successful update.'), 'default', array('class'=>'flash flash_notice'));
        $this->redirect('index');
      } else {
        $this->Session->setFlash(__('Please correct errors below.'), 'default', array('class'=>'flash flash_error'));
      }
    }
    if(empty($this->request->data)) {
      $this->request->data = $this->IssueStatus->read(null, $id);
    }
  }
  function add() {
    if(!empty($this->request->data)) {
      $this->IssueStatus->create();
      if($this->IssueStatus->save($this->request->data)) {
        $this->Session->setFlash(__('Successful update.'), 'default', array('class'=>'flash flash_notice'));
        $this->redirect('index');
      } else {
        $this->Session->setFlash(__('Please correct errors below.'), 'default', array('class'=>'flash flash_error'));
      }
    }
    $this->render("new");
  }
  function destroy($id=false) {
    if($id == false) {
      $this->Session->setFlash(__("Invalid id"), 'default', array('class'=>'flash flash_error'));
      $this->redirect('index');
    }
    if ($this->IssueStatus->delete($id)) {
      $this->Session->setFlash(__('Successful deletion.'), 'default', array('class'=>'flash flash_notice'));
    } else {
      $this->Session->setFlash(sprintf(__('There was an error deleting with id: %1$d'), $id));
    }
    $this->redirect('index');
  }
}
?>
