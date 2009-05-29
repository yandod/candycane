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
class TimelogController extends AppController
{
  var $name = 'Timelog';

  function beforeFilter() {
    $this->MenuManager->menu_item('issues');

    switch($this->action) {
    case 'edit' :
    case 'destroy' :
      $this->_find_project();
      $this->authorize = array();
      break;
    case 'report' :
    case 'details' :
      $this->_find_optional_project();
      break;
    }
    return parent::beforeFilter();
  }
#
#  verify :method => :post, :only => :destroy, :redirect_to => { :action => :details }
#  
  var $helpers = array('Sort', 'Issues', 'CustomField', 'Timelog', 'Ajax', 'Paginator');
  var $uses = array('TimeEntry', 'Issue');
  var $components = array('Sort', 'RequestHandler');
  var $_project = false;
  
  function report() {
    if ($this->RequestHandler->isAjax() || $this->_get_param('format')) {
      Configure::write('debug', 0);
    }
    $available_criterias = $this->TimeEntry->report_available_criterias($this->_project);
    $criterias = array();
    if($this->_get_param('criterias')) {
      foreach($this->_get_param('criterias') as $criteria) {
        if(array_key_exists($criteria, $available_criterias) && !in_array($criteria, $criterias)) {
          $criterias[] = $criteria;
        }
      }
    }
    $criterias = array_slice($criterias, 0, 3);
  
    $columns = 'month';
    if($this->_get_param('columns') && in_array($this->_get_param('columns'), array('year', 'month', 'week', 'day'))) {
      $columns = $this->_get_param('columns');
    }
  
    $data = array_merge(array('from'=>null, 'to'=>null, 'period_type'=>'1', 'period'=>'all'), $this->params['url']);
    if(!empty($this->data['TimeEntry'])) {
      $data = array_merge($data, $this->data['TimeEntry']);
    }
    $range = $this->TimeEntry->retrieve_date_range($data['period_type'], $data['period'], 
      $this->current_user, $this->_project, array('from'=>$data['from'], 'to'=>$data['to']));
    $data = array_merge($data, $range);
    $this->params['url'] = $data;
  
    if(!empty($criterias)) {
      $hours = $this->TimeEntry->find_report_hours($this->_project, $available_criterias, $criterias, $this->Setting, $this->current_user, $range);      
      $total_hours = $this->TimeEntry->get_total_hours($hours, $columns);
      $periods = $this->TimeEntry->get_periods($range, $columns);
    } else {
      $total_hours = 0;
      $hours = array();
      $periods = array();
    }
    $this->set(compact('criterias', 'columns', 'available_criterias', 'total_hours', 'hours', 'periods'));
    
    switch($this->_get_param('format')) {
    case 'csv' :
      # Export all entries
      $this->helpers = array('Csv', 'Timelog');
      $this->layout = 'csv';
      $this->render('report_csv');
      break;
    }

  }
  
  function details() {
    if ($this->RequestHandler->isAjax() || $this->_get_param('format')) {
      Configure::write('debug', 0);
    }
    $this->TimeEntry->_customFieldAfterFindDisable = true;
    $this->Sort->sort_init('TimeEntry.spent_on', 'desc');
    $this->Sort->sort_update(array(
                'TimeEntry.spent_on' => 'TimeEntry.spent_on',
                'TimeEntry.user_id' => 'TimeEntry.user_id',
                'TimeEntry.activity_id' => 'TimeEntry.activity_id',
                'Project.name' => "Project.name",
                'TimeEntry.issue_id' => 'TimeEntry.issue_id',
                'TimeEntry.hours' => 'TimeEntry.hours'
    ));
    $data = array_merge(array('from'=>null, 'to'=>null, 'period_type'=>'1', 'period'=>'all'), $this->params['url']);
    if(!empty($this->data['TimeEntry'])) {
      $data = array_merge($data, $this->data['TimeEntry']);
    }
    $result = $this->TimeEntry->details_condition($this->Setting, $this->current_user, $this->_project, $this->Issue->data, $data);
    // $result ==> $cond, $range
    extract($result);
    $data = array_merge($data, $range);
    $this->params['url'] = $data;
    $visible = $this->TimeEntry->find_visible_by($this->current_user, $this->_project);
    if(!empty($visible)) {
      switch($this->_get_param('format')) {
      case 'csv' :
        # Export all entries
        unset($this->TimeEntry->_customFieldAfterFindDisable);
        $this->TimeEntry->bindModel(array('belongsTo'=>array('Issue')), false);
        $this->TimeEntry->Issue->_customFieldAfterFindDisable = true;
        $entries = $this->TimeEntry->find('all', array(
          'conditions' => $cond,
          'order' => $this->Sort->sort_clause()
        ));
        $trackers = $this->Issue->Tracker->find('list');
        $custom_fields = $this->TimeEntry->available_custom_fields();
        $this->set(compact('entries', 'trackers', 'custom_fields'));
        $this->helpers = array('Candy', 'Csv', 'CustomField');
        $this->layout = 'csv';
        $this->render('details_csv');
        break;
      case 'atom' :
        $this->TimeEntry->bindModel(array('belongsTo'=>array('Issue')), false);
        $this->TimeEntry->Issue->_customFieldAfterFindDisable = true;
        $entries = $this->TimeEntry->find('all', array(
          'conditions' => $cond,
          'order' => "TimeEntry.created_on DESC",
          'limit' => $this->Setting->feeds_limit
        ));
        $trackers = $this->Issue->Tracker->find('list');
        foreach($entries as $k=>$v) {
          if(!empty($v['Issue']['tracker_id'])) {
            $entries[$k]['Tracker'] = array('name'=>$trackers[$v['Issue']['tracker_id']]);
          }
        }
        $this->render_feed($this->TimeEntry, $entries, array('title' => __('Spent time',true)));
        break;
      default :
        # Paginate results
        $this->TimeEntry->bindModel(array('belongsTo'=>array('Issue')), false);
        $this->TimeEntry->Issue->_customFieldAfterFindDisable = true;
        $limit = $this->_per_page_option();
        $this->paginate['TimeEntry'] = array(
          'conditions' => $cond,
          'limit' => $limit
        );
        $entries = $this->paginate($this->TimeEntry);
        $trackers = $this->Issue->Tracker->find('list');
        $total_hours = $this->TimeEntry->sum('hours', $cond);
        $rss_token = $this->TimeEntry->User->rss_key($this->current_user['id']);
        $this->set(compact('entries', 'trackers', 'total_hours', 'rss_token'));
        if(!empty($this->Issue->data)) {
          $this->set('issue', $this->Issue->data);
        }
        if ($this->RequestHandler->isAjax()) {
          $this->layout = 'ajax';
        }
      }
    }
  }
  
  function edit() {
    if(!$this->TimeEntry->is_editable_by($this->current_user, $this->_project)) {
      $this->cakeError('error404');
    }
    if(empty($this->TimeEntry->data)) {
      $this->TimeEntry->create();
      $this->TimeEntry->set(array(
        'project_id' => $this->_project['Project']['id'], 
        'issue_id' => $this->Issue->id, 
        'user_id' => $this->current_user['id'], 
        'spent_on' => date('Y-m-d')
      ));
    }
    if(!empty($this->data)) {
      $this->TimeEntry->set($this->data);
      if($this->TimeEntry->save()) {
        $this->Session->setFlash(__('Successful update.', true), 'default', array('class'=>'flash flash_notice'));
        $this->redirect_back_or_default(array('action' => 'details', 'project_id' => $this->TimeEntry->data['TimeEntry']['project_id']));
      }
    } else {
      $this->data = $this->TimeEntry->data;
    }
    $this->set('time_entry', $this->TimeEntry->data);
    $time_entry_activities = $this->Issue->findTimeEntryActivities();
    $time_entry_custom_fields = $this->TimeEntry->available_custom_fields();
    $this->set(compact('time_entry_activities', 'time_entry_custom_fields'));
  }
  
  function destroy() {
    if(empty($this->TimeEntry->data)) {
      $this->cakeError('error404');
    }
    if(!$this->TimeEntry->is_editable_by($this->current_user, $this->_project)) {
      $this->cakeError('error403');
    }
    if($this->TimeEntry->del()) {
      $this->Session->setFlash(__('Successful deletion.', true), 'default', array('class'=>'flash flash_notice'));
    }
    $this->redirect_back_or_default(array('action' => 'details', 'project_id' => $this->params['project_id']));
  }

#private
  function _find_project() {
    if(!empty($this->params['id'])) {
      $this->TimeEntry->recursive = 2;
      $this->TimeEntry->bindModel(array('belongsTo'=>array('Issue')));
      $this->TimeEntry->read(null, $this->params['id']);
      $this->params['project_id'] = $this->TimeEntry->data['Project']['identifier'];
    } elseif(!empty($this->params['url']['issue_id'])) {
      $this->Issue->read(null, $this->params['url']['issue_id']);
      $this->params['project_id'] = $this->Issue->data['Project']['identifier'];
    } elseif(!empty($this->params['project_id'])) {
      ;
    } else {
      $this->cakeError('error404');
    }
  }
  
  function _find_optional_project() {
    if($this->_get_param('issue_id')) {
      $issue_id = $this->_get_param('issue_id');
      $this->Issue->read(null, $issue_id);
      $this->params['project_id'] = $this->Issue->data['Project']['identifier'];
    } elseif(!empty($this->params['project_id'])) {
      ; // parent::beforeFilter
    }
    parent::_findProject();
    parent::user_setup();
    if(!$this->TimeEntry->User->is_allowed_to($this->current_user, 'view_time_entries', $this->_project, array('global' => true))) {
      return parent::deny_access();
    }
  }
}
