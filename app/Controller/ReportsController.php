<?php

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
                $this->set('report_title', 'Tracker');
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
                $this->set('report_title', 'Version');
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
                $this->set('report_title', 'Priority');
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
                $this->set('report_title', 'Category');
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
                $this->set('report_title', 'Assigned to');
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
                $this->set('report_title', 'Author');
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

                $this->set('report_title', 'Subproject');
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