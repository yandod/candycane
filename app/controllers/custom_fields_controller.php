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
#class CustomFieldsController < ApplicationController
class CustomFieldsController extends AppController {
  var $name = 'CustomFields';
  var $components = array(
    'RequestHandler',
  );
  var $helpers = array(
    'CustomField',
  );

  function beforeFilter()
  {
    parent::beforeFilter();
    $this->require_admin();
  }
    
  function index() {
    $custom_fields_by_type = $this->CustomField->group_by($this->CustomField->find('all'), 'type');
    $tab = $this->_get_param('tab');
    if (empty($tab)) {
      $tab = 'IssueCustomField';
    }

    $this->set('selected_tab', $tab);
    $this->set('custom_fields_by_type', $custom_fields_by_type);
    if ($this->RequestHandler->isAjax()) {
      $this->layout = 'ajax';
    }
    $this->render("list");
  }
#  
#  def new
#    case params[:type]
#      when "IssueCustomField" 
#        @custom_field = IssueCustomField.new(params[:custom_field])
#        @custom_field.trackers = Tracker.find(params[:tracker_ids]) if params[:tracker_ids]
#      when "UserCustomField" 
#        @custom_field = UserCustomField.new(params[:custom_field])
#      when "ProjectCustomField" 
#        @custom_field = ProjectCustomField.new(params[:custom_field])
#      when "TimeEntryCustomField" 
#        @custom_field = TimeEntryCustomField.new(params[:custom_field])
#      else
#        redirect_to :action => 'list'
#        return
#    end  
#    if request.post? and @custom_field.save
#      flash[:notice] = l(:notice_successful_create)
#      redirect_to :action => 'list', :tab => @custom_field.class.name
#    end
#    @trackers = Tracker.find(:all, :order => 'position')
#  end
#
#  def edit
#    @custom_field = CustomField.find(params[:id])
#    if request.post? and @custom_field.update_attributes(params[:custom_field])
#      if @custom_field.is_a? IssueCustomField
#        @custom_field.trackers = params[:tracker_ids] ? Tracker.find(params[:tracker_ids]) : []
#      end
#      flash[:notice] = l(:notice_successful_update)
#      redirect_to :action => 'list', :tab => @custom_field.class.name
#    end
#    @trackers = Tracker.find(:all, :order => 'position')
#  end
#
#  def move
#    @custom_field = CustomField.find(params[:id])
#    case params[:position]
#    when 'highest'
#      @custom_field.move_to_top
#    when 'higher'
#      @custom_field.move_higher
#    when 'lower'
#      @custom_field.move_lower
#    when 'lowest'
#      @custom_field.move_to_bottom
#    end if params[:position]
#    redirect_to :action => 'list', :tab => @custom_field.class.name
#  end
#  
#  def destroy
#    @custom_field = CustomField.find(params[:id]).destroy
#    redirect_to :action => 'list', :tab => @custom_field.class.name
#  rescue
#    flash[:error] = "Unable to delete custom field"
#    redirect_to :action => 'list'
#  end
#end
}