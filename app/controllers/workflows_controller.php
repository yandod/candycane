<?php
## Redmine - project management software
## Copyright (C) 2006-2008  Jean-Philippe Lang
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
#class WorkflowsController < ApplicationController
class WorkflowsController extends Appcontroller {

  var $name = 'Workflows';
  var $uses = array('Workflow', 'Role', 'Tracker', 'IssueStatus');

#  before_filter :require_admin
#
  function index() {
#    @workflow_counts = Workflow.count_by_tracker_and_role
    $workflow_counts = $this->_count_by_tracker_and_role();

    $this->set('workflow_counts', $workflow_counts);
  }

  function _count_by_tracker_and_role() {
    $counts = $this->Workflow->find('all' ,  array('group' => 'role_id, tracker_id',
                                                   'fields' => array('Workflow.role_id', 'Workflow.tracker_id', 'count(id) AS "Workflow__c"')));
    $roles = $this->Role->find('all', array('order' => array('builtin', 'position')));

    $trackers = $this->Tracker->find('all', array('order' => 'position'));

    $result = array();
    pr(count($trackers));
    foreach ($trackers as $tracker) {
      $t = array();
      foreach ($roles as $role) {
        foreach ($counts as $c) {
          if (($c['Workflow']['role_id'] == $role['Role']['id']) &&
              ($c['Workflow']['tracker_id'] == $tracker['Tracker']['id'])) {
            array_push($t, array($role, $c['Workflow']['c']));
          } else {
            array_push($t, array($role, 0));
          }
          array_push($result, array($tracker, $t));
        }
      }
    }
    pr(count($result));
    return $result;
#    result = []
#    trackers.each do |tracker|
#      t = []
#      roles.each do |role|
#        row = counts.detect {|c| c['role_id'] == role.id.to_s && c['tracker_id'] == tracker.id.to_s}
#        t << [role, (row.nil? ? 0 : row['c'].to_i)]
#      end
#      result << [tracker, t]
#    end
#    
#    result
#  end
#end
  }
  


#  
#  def edit
  function edit() {
#    @role = Role.find_by_id(params[:role_id])
#    @tracker = Tracker.find_by_id(params[:tracker_id])    
    $role_id    = isset($this->params['url']['role_id'])    ? $this->params['url']['role_id']    : NULL;
    $tracker_id = isset($this->params['url']['tracker_id']) ? $this->params['url']['tracker_id'] : NULL;

    if ($role_id != NULL) {
      $role = $this->Role->findById($role_id);
    } else {
      $role = NULL;
    }
    if ($tracker_id != NULL) {
      $tracker = $this->Tracker->findById($tracker_id);
    } else {
      $tracker = NULL;
    }
    $this->set('role', $role);
    $this->set('tracker', $tracker);

#  def find_new_statuses_allowed_to(role, tracker)  
#    new_statuses = workflows.find(:all, 
#                                   :include => :new_status,
#                                   :conditions => ["role_id=? and tracker_id=?", role.id, tracker.id]).collect{ |w| w.new_status }.compact  if role && tracker
#    new_statuses ? new_statuses.sort{|x, y| x.position <=> y.position } : []
#  end

    $this->Workflow->bindModel(array('belongsTo' => array('IssueStatus' => array('className' => 'IssueStatus',
                                                                                  'foreignKey' => 'new_status_id'))));


    $allowed_to = $this->Workflow->find('all', array('conditions' => array('role_id' => $role['Role']['id'],
                                                                           'tracker_id' => $tracker['Tracker']['id'])));
    //    pr($allowed_to);
    foreach ($allowed_to as $a) {
      
    }



    //    $new_status = uasort($allowed_to, array($this,'_cmp'));



#    
#    if request.post?
#      Workflow.destroy_all( ["role_id=? and tracker_id=?", @role.id, @tracker.id])
#      (params[:issue_status] || []).each { |old, news| 
#        news.each { |new| 
#          @role.workflows.build(:tracker_id => @tracker.id, :old_status_id => old, :new_status_id => new) 
#        }
#      }
#      if @role.save
#        flash[:notice] = l(:notice_successful_update)
#        redirect_to :action => 'edit', :role_id => @role, :tracker_id => @tracker
#      end
#    end
#    @roles = Role.find(:all, :order => 'builtin, position')
#    @trackers = Tracker.find(:all, :order => 'position')
#    @statuses = IssueStatus.find(:all, :order => 'position')

    $roles_options = $this->Role->find('list', array('field' => array('id','name'),
                                                     'order' => array('builtin','position')));
    $trackers_options = $this->Tracker->find('list', array('field' => array('id','name'),
                                                           'order' => array('position')));
    $statuses = $this->IssueStatus->find('all',array('order' => array('position')));
    $stasus_length = count($statuses);

    $this->set('roles_options',$roles_options);
    $this->set('trackers_options', $trackers_options);
    $this->set('statuses', $statuses);

    

#  end
#end
  }

  function _cmp($x, $y) {
    $a = $x['IssueStatus']['position'];
    $b = $y['IssueStatus']['position'];

    if ($a == $b) {
      return 0;
    }
    return ($a < $b) ? -1 : 1;
  }
}
?>