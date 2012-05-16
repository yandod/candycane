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
#class ReportsController < ApplicationController
#  menu_item :issues
#  before_filter :find_project, :authorize
#
#  def issue_report
#    @statuses = IssueStatus.find(:all, :order => 'position')
#    
#    case params[:detail]
#    when "tracker"
#      @field = "tracker_id"
#      @rows = @project.trackers
#      @data = issues_by_tracker
#      @report_title = l(:field_tracker)
#      render :template => "reports/issue_report_details"
#    when "version"
#      @field = "fixed_version_id"
#      @rows = @project.versions.sort
#      @data = issues_by_version
#      @report_title = l(:field_version)
#      render :template => "reports/issue_report_details"
#    when "priority"
#      @field = "priority_id"
#      @rows = Enumeration::get_values('IPRI')
#      @data = issues_by_priority
#      @report_title = l(:field_priority)
#      render :template => "reports/issue_report_details"   
#    when "category"
#      @field = "category_id"
#      @rows = @project.issue_categories
#      @data = issues_by_category
#      @report_title = l(:field_category)
#      render :template => "reports/issue_report_details"   
#    when "assigned_to"
#      @field = "assigned_to_id"
#      @rows = @project.members.collect { |m| m.user }
#      @data = issues_by_assigned_to
#      @report_title = l(:field_assigned_to)
#      render :template => "reports/issue_report_details"
#    when "author"
#      @field = "author_id"
#      @rows = @project.members.collect { |m| m.user }
#      @data = issues_by_author
#      @report_title = l(:field_author)
#      render :template => "reports/issue_report_details"  
#    when "subproject"
#      @field = "project_id"
#      @rows = @project.active_children
#      @data = issues_by_subproject
#      @report_title = l(:field_subproject)
#      render :template => "reports/issue_report_details"  
#    else
#      @trackers = @project.trackers
#      @versions = @project.versions.sort
#      @priorities = Enumeration::get_values('IPRI')
#      @categories = @project.issue_categories
#      @assignees = @project.members.collect { |m| m.user }
#      @authors = @project.members.collect { |m| m.user }
#      @subprojects = @project.active_children
#      issues_by_tracker
#      issues_by_version
#      issues_by_priority
#      issues_by_category
#      issues_by_assigned_to
#      issues_by_author
#      issues_by_subproject
#      
#      render :template => "reports/issue_report"
#    end
#  end  
#  
#  def delays
#    @trackers = Tracker.find(:all)
#    if request.get?
#      @selected_tracker_ids = @trackers.collect {|t| t.id.to_s }
#    else
#      @selected_tracker_ids = params[:tracker_ids].collect { |id| id.to_i.to_s } if params[:tracker_ids] and params[:tracker_ids].is_a? Array
#    end
#    @selected_tracker_ids ||= []    
#    @raw = 
#      ActiveRecord::Base.connection.select_all("SELECT datediff( a.created_on, b.created_on ) as delay, count(a.id) as total
#      FROM issue_histories a, issue_histories b, issues i
#      WHERE a.status_id =5
#      AND a.issue_id = b.issue_id
#      AND a.issue_id = i.id
#      AND i.tracker_id in (#{@selected_tracker_ids.join(',')})
#      AND b.id = (
#      SELECT min( c.id )
#      FROM issue_histories c
#      WHERE b.issue_id = c.issue_id ) 
#      GROUP BY delay") unless @selected_tracker_ids.empty?    
#    @raw ||=[]
#    
#    @x_from = 0
#    @x_to = 0
#    @y_from = 0
#    @y_to = 0
#    @sum_total = 0
#    @sum_delay = 0
#    @raw.each do |r|
#      @x_to = [r['delay'].to_i, @x_to].max
#      @y_to = [r['total'].to_i, @y_to].max
#      @sum_total = @sum_total + r['total'].to_i
#      @sum_delay = @sum_delay + r['total'].to_i * r['delay'].to_i
#    end    
#  end
#  
#private
#  # Find project of id params[:id]
#  def find_project
#    @project = Project.find(params[:id])		
#  rescue ActiveRecord::RecordNotFound
#    render_404
#  end
#
#  def issues_by_tracker
#    @issues_by_tracker ||= 
#        ActiveRecord::Base.connection.select_all("select    s.id as status_id, 
#                                                  s.is_closed as closed, 
#                                                  t.id as tracker_id,
#                                                  count(i.id) as total 
#                                                from 
#                                                  #{Issue.table_name} i, #{IssueStatus.table_name} s, #{Tracker.table_name} t
#                                                where 
#                                                  i.status_id=s.id 
#                                                  and i.tracker_id=t.id
#                                                  and i.project_id=#{@project.id}
#                                                group by s.id, s.is_closed, t.id")	
#  end
#
#  def issues_by_version
#    @issues_by_version ||= 
#        ActiveRecord::Base.connection.select_all("select    s.id as status_id, 
#                                                  s.is_closed as closed, 
#                                                  v.id as fixed_version_id,
#                                                  count(i.id) as total 
#                                                from 
#                                                  #{Issue.table_name} i, #{IssueStatus.table_name} s, #{Version.table_name} v
#                                                where 
#                                                  i.status_id=s.id 
#                                                  and i.fixed_version_id=v.id
#                                                  and i.project_id=#{@project.id}
#                                                group by s.id, s.is_closed, v.id")	
#  end
#  	
#  def issues_by_priority    
#    @issues_by_priority ||= 
#      ActiveRecord::Base.connection.select_all("select    s.id as status_id, 
#                                                  s.is_closed as closed, 
#                                                  p.id as priority_id,
#                                                  count(i.id) as total 
#                                                from 
#                                                  #{Issue.table_name} i, #{IssueStatus.table_name} s, #{Enumeration.table_name} p
#                                                where 
#                                                  i.status_id=s.id 
#                                                  and i.priority_id=p.id
#                                                  and i.project_id=#{@project.id}
#                                                group by s.id, s.is_closed, p.id")	
#  end
#	
#  def issues_by_category   
#    @issues_by_category ||= 
#      ActiveRecord::Base.connection.select_all("select    s.id as status_id, 
#                                                  s.is_closed as closed, 
#                                                  c.id as category_id,
#                                                  count(i.id) as total 
#                                                from 
#                                                  #{Issue.table_name} i, #{IssueStatus.table_name} s, #{IssueCategory.table_name} c
#                                                where 
#                                                  i.status_id=s.id 
#                                                  and i.category_id=c.id
#                                                  and i.project_id=#{@project.id}
#                                                group by s.id, s.is_closed, c.id")	
#  end
#  
#  def issues_by_assigned_to
#    @issues_by_assigned_to ||= 
#      ActiveRecord::Base.connection.select_all("select    s.id as status_id, 
#                                                  s.is_closed as closed, 
#                                                  a.id as assigned_to_id,
#                                                  count(i.id) as total 
#                                                from 
#                                                  #{Issue.table_name} i, #{IssueStatus.table_name} s, #{User.table_name} a
#                                                where 
#                                                  i.status_id=s.id 
#                                                  and i.assigned_to_id=a.id
#                                                  and i.project_id=#{@project.id}
#                                                group by s.id, s.is_closed, a.id")
#  end
#  
#  def issues_by_author
#    @issues_by_author ||= 
#      ActiveRecord::Base.connection.select_all("select    s.id as status_id, 
#                                                  s.is_closed as closed, 
#                                                  a.id as author_id,
#                                                  count(i.id) as total 
#                                                from 
#                                                  #{Issue.table_name} i, #{IssueStatus.table_name} s, #{User.table_name} a
#                                                where 
#                                                  i.status_id=s.id 
#                                                  and i.author_id=a.id
#                                                  and i.project_id=#{@project.id}
#                                                group by s.id, s.is_closed, a.id")	
#  end
#  
#  def issues_by_subproject
#    @issues_by_subproject ||= 
#      ActiveRecord::Base.connection.select_all("select    s.id as status_id, 
#                                                  s.is_closed as closed, 
#                                                  i.project_id as project_id,
#                                                  count(i.id) as total 
#                                                from 
#                                                  #{Issue.table_name} i, #{IssueStatus.table_name} s
#                                                where 
#                                                  i.status_id=s.id 
#                                                  and i.project_id IN (#{@project.active_children.collect{|p| p.id}.join(',')})
#                                                group by s.id, s.is_closed, i.project_id") if @project.active_children.any?
#    @issues_by_subproject ||= []
#  end
#end
class ReportsController extends AppController
{
#  menu_item :issues
#  before_filter :find_project, :authorize
  var $name = 'Reports';
  var $uses = array('Report', 'IssueStatus', 'Member', 'User');
  var $helpers = array('Reports');

#  def issue_report
  /**
   * @action
   * @param  string $identifier
   */
  function issue_report()
  {
    $project = $this->_find_project($this->_get_param('project_id'));

    $projectId = $project['Project']['id'];
    $this->set('project', $project['Project']);

#    @statuses = IssueStatus.find(:all, :order => 'position')
    $this->statuses = $this->IssueStatus->find('all', array('order' => 'position'));
    if (empty($this->statuses)) {
      $this->statuses = array();
    }
    $this->set('statuses', $this->statuses);

#    case params[:detail]
#    when "tracker"
#      @field = "tracker_id"
#      @rows = @project.trackers
#      @data = issues_by_tracker
#      @report_title = l(:field_tracker)
#      render :template => "reports/issue_report_details"
    $detail = Set::extract($this->request->query, 'detail');
    switch ($detail) {
      case 'tracker':
        $this->set('field', 'tracker_id');
        $this->set('rows', $project['Tracker']);
        $this->set('data', $this->_issues_by_tracker($projectId));
        $this->set('report_title',  'Tracker');
        $this->render('issue_report_details');
        break;
#    
#    when "version"
#      @field = "fixed_version_id"
#      @rows = @project.versions.sort
#      @data = issues_by_version
#      @report_title = l(:field_version)
#      render :template => "reports/issue_report_details"
      case 'version':
        $this->set('field', 'fixed_version_id');
        $this->set('rows', $project['Version']);
        $this->set('data', $this->_issues_by_version($projectId));
        $this->set('report_title',  'Version');
        $this->render('issue_report_details');
        break;
#    when "priority"
#      @field = "priority_id"
#      @rows = Enumeration::get_values('IPRI')
#      @data = issues_by_priority
#      @report_title = l(:field_priority)
#      render :template => "reports/issue_report_details"   
      case 'priority':
        $this->set('field', 'priority_id');
        $this->set('rows', $this->Report->findEnumurations());
        $this->set('data', $this->_issues_by_priority($projectId));
        $this->set('report_title',  'Priority');
        $this->render('issue_report_details');
        break;
#    when "category"
#      @field = "category_id"
#      @rows = @project.issue_categories
#      @data = issues_by_category
#      @report_title = l(:field_category)
#      render :template => "reports/issue_report_details"   
      case 'category':
        $this->set('field', 'category_id');
        $this->set('rows', $project['IssueCategory']);
        $this->set('data', $this->_issues_by_category($projectId));
        $this->set('report_title',  'Category');
        $this->render('issue_report_details');
        break;
#    when "assigned_to"
#      @field = "assigned_to_id"
#      @rows = @project.members.collect { |m| m.user }
#      @data = issues_by_assigned_to
#      @report_title = l(:field_assigned_to)
#      render :template => "reports/issue_report_details"
      case 'assigned_to':
        $this->set('field', 'assigned_to_id');
        $this->set('rows', $this->Report->findMembers($projectId));
        $this->set('data', $this->_issues_by_assigned_to($projectId));
        $this->set('report_title',  'Assigned to');
        $this->render('issue_report_details');
        break;
#    when "author"
#      @field = "author_id"
#      @rows = @project.members.collect { |m| m.user }
#      @data = issues_by_author
#      @report_title = l(:field_author)
#      render :template => "reports/issue_report_details"  
      case 'author':
        $this->set('field', 'author_id');
        $this->set('rows', $this->Report->findMembers($projectId));
        $this->set('data', $this->_issues_by_author($projectId));
        $this->set('report_title',  'Author');
        $this->render('issue_report_details');
        break;
#    when "subproject"
#      @field = "project_id"
#      @rows = @project.active_children
#      @data = issues_by_subproject
#      @report_title = l(:field_subproject)
#      render :template => "reports/issue_report_details"  
      case 'subproject':
        $this->set('field', 'project_id');

        $subprojects = $this->Project->active_children($projectId);
        $this->set('rows', $subprojects);
        $this->set('data', $this->_issues_by_subproject($subprojects));

        $this->set('report_title',  'Subproject');
        $this->render('issue_report_details');
        break;
#    else
#      @trackers = @project.trackers
#      @versions = @project.versions.sort
#      @priorities = Enumeration::get_values('IPRI')
#      @categories = @project.issue_categories
#      @assignees = @project.members.collect { |m| m.user }
#      @authors = @project.members.collect { |m| m.user }
#      @subprojects = @project.active_children
#      issues_by_tracker
#      issues_by_version
#      issues_by_priority
#      issues_by_category
#      issues_by_assigned_to
#      issues_by_author
#      issues_by_subproject
#      
#      render :template => "reports/issue_report"
#    end
#  end  
      default:
        $this->set('project', $project['Project']);
        $this->set('trackers', $project['Tracker']);
        $this->set('versions', $project['Version']);
        $this->set('priorities', $this->Report->findEnumurations());
        $this->set('categories', $project['IssueCategory']);
        $this->set('assignees', $this->Report->findMembers($projectId));
        $this->set('authors', $this->Report->findMembers($projectId));

        $subprojects = $this->Project->active_children($projectId);
        $this->set('subprojects', $subprojects);

        $this->set('issues_by_tracker', $this->_issues_by_tracker($projectId));
        $this->set('issues_by_version', $this->_issues_by_version($projectId));
        $this->set('issues_by_priority', $this->_issues_by_priority($projectId));
        $this->set('issues_by_category', $this->_issues_by_category($projectId));
        $this->set('issues_by_assigned_to', $this->_issues_by_assigned_to($projectId));
        $this->set('issues_by_author', $this->_issues_by_author($projectId));
        $this->set('issues_by_subproject', $this->_issues_by_subproject($subprojects));
    }  
  }

  function _find_project($identifier)
  {
    return $this->Project->find($identifier);
  }

  function _issues_by_tracker($projectId)
  {
    return $this->Report->findIssuesByTracker($projectId);
  }

  function _issues_by_version($projectId)
  {
    return $this->Report->findIssuesByVersion($projectId);
  }

  function _issues_by_priority($projectId)
  {
    return $this->Report->findIssuesByPriority($projectId);
  }

  function _issues_by_category($projectId)
  {
    return $this->Report->findIssuesByCategory($projectId);
  }

  function _issues_by_assigned_to($projectId)
  {
    return $this->Report->findIssuesByAssignedTo($projectId);
  }

  function _issues_by_author($projectId)
  {
    return $this->Report->findIssuesByAuthor($projectId);
  }

  function _issues_by_subproject($projects)
  {
    $ids = array();
    foreach ($projects as $v) {
      $ids[] = $v['id'];
    }
    if (!$ids) {
      return array();
    }
    return $this->Report->findIssuesBySubproject($ids);
  }
}
