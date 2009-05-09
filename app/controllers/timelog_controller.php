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
#  menu_item :issues
  function beforeFilter() {
    switch($this->action) {
    case 'edit' :
    case 'destroy' :
      $this->_find_project();
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
    $available_criterias = array(
      'project'  => array('sql' => 'TimeEntry.project_id',
                           'klass' => $this->TimeEntry->Project,
                           'label' => 'Project'),
      'version'  => array('sql' => "Issue.fixed_version_id",
                           'klass' => $this->Issue->FixedVersion,
                           'label' => 'Version'),
      'category' => array('sql' => "Issue.category_id",
                           'klass' => $this->Issue->Category,
                           'label' => 'Category'),
      'member'   => array('sql' => "TimeEntry.user_id",
                           'klass' => $this->TimeEntry->User,
                           'label' => 'Member'),
      'tracker'  => array('sql' => "Issue.tracker_id",
                           'klass' => $this->Issue->Tracker,
                           'label' => 'Tracker'),
      'activity' => array('sql' => "TimeEntry.activity_id",
                           'klass' => $this->TimeEntry->Activity,
                           'label' => 'Activity'),
      'issue'    => array('sql' => "TimeEntry.issue_id",
                           'klass' => $this->Issue,
                           'label' => 'Issue'),
    );
    $CustomValue = & ClassRegistry::init('CustomValue');
    $custom_value_table_name = $CustomValue->fullTableName();
    $project_table_name = $this->TimeEntry->Project->fullTableName();
    $issue_table_name = $this->Issue->fullTableName();
    $time_entry_table_name = $this->TimeEntry->fullTableName();
    #    # Add list and boolean custom fields as available criterias
    $custom_fields = empty($this->params['project_id']) ? $this->Issue->available_custom_fields() : $this->Issue->available_custom_fields($this->params['project_id']);
    foreach($custom_fields as $cf) {
      if(!empty($cf['CustomField']['field_format'])) {
        $available_criterias["cf_{$cf['CustomField']['id']}"] = array(
          'sql' => "(SELECT c.value FROM $custom_value_table_name c WHERE c.custom_field_id = {$cf['CustomField']['id']} AND c.customized_type = 'Issue' AND c.customized_id = {$issue_table_name}.id)",
          'format' => $cf['CustomField']['field_format'],
          'label' => $cf['CustomField']['name']);
      }
    }
  
    # Add list and boolean time entry custom fields
    $custom_fields = $this->TimeEntry->available_custom_fields();
    foreach($custom_fields as $cf) {
      if(!empty($cf['CustomField']['field_format'])) {
        $available_criterias["cf_{$cf['CustomField']['id']}"] = array(
          'sql' => "(SELECT c.value FROM $custom_value_table_name c WHERE c.custom_field_id = {$cf['CustomField']['id']} AND c.customized_type = 'TimeEntry' AND c.customized_id = {$time_entry_table_name}.id)",
          'format' => $cf['CustomField']['field_format'],
          'label' => $cf['CustomField']['name']);
      }
    }
  
    $criterias = array();
    if(!empty($this->data['TimeEntry']['criterias'])) {
      foreach($this->data['TimeEntry']['criterias'] as $criteria) {
        if(array_key_exists($criteria, $available_criterias) && !in_array($criteria, $criterias)) {
          $criterias[] = $criteria;
        }
      }
    }
    $criterias = array_slice($criterias, 0, 3);
  
    $columns = 'month';
    if(!empty($this->data['TimeEntry']['columns']) && in_array($this->data['TimeEntry']['columns'], array('year', 'month', 'week', 'day'))) {
      $columns = $this->data['TimeEntry']['columns'];
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
      $sql_select = array();
      $sql_group_by = array();
      foreach($criterias as $criteria) {
        $sql_select[]   = $available_criterias[$criteria]['sql']." AS ".$criteria;
        $sql_group_by[] = $available_criterias[$criteria]['sql'];
      }
      $sql_select = join(', ', $sql_select).', ';
      $sql_group_by = join(', ', $sql_group_by).', ';
  
      $sql  = "SELECT {$sql_select} tyear, tmonth, tweek, spent_on, SUM(hours) AS hours";
      $sql .= " FROM $time_entry_table_name AS TimeEntry";
      $sql .= " LEFT JOIN $issue_table_name ON TimeEntry.issue_id = {$issue_table_name}.id";
      $sql .= " LEFT JOIN $project_table_name ON TimeEntry.project_id = {$project_table_name}.id";
      $sql .= " WHERE";
      if(!empty($this->_project)) {
        $sql .= sprintf(" (%s) AND", $this->TimeEntry->Project->project_condition($this->Setting->display_subprojects_issues, $this->_project['Project'], true));
      }
      $sql .= sprintf(" (%s) AND", $this->TimeEntry->Project->allowed_to_condition_string($this->current_user, ':view_time_entries'));
      $sql .= sprintf(" (spent_on BETWEEN '%s' AND '%s')" , $this->TimeEntry->quoted_date($range['from'], 'spent_on'), $this->TimeEntry->quoted_date($range['to'], 'spent_on'));
      $sql .= " GROUP BY {$sql_group_by} tyear, tmonth, tweek, spent_on";
  
      $hours = $this->TimeEntry->query($sql);

      $total_hours = 0;
      foreach($hours as $k=>$row) {
        $total_hours += $row[0]['hours'];
        $row = $row['TimeEntry'];
        switch($columns) {
        case 'year' :
          $hours[$k]['TimeEntry']['year'] = $row['tyear'];
          break;
        case 'month' :
          $hours[$k]['TimeEntry']['month'] = "{$row['tyear']}-{$row['tmonth']}";
          break;
        case 'week' :
          $hours[$k]['TimeEntry']['week'] = "{$row['tyear']}-{$row['tweek']}";
          break;
        case 'day' :
          $hours[$k]['TimeEntry']['day'] = "{$row['spent_on']}";
          break;
        }
      }
      
      $periods = array();
      # Date#at_beginning_of_ not supported in Rails 1.2.x
      $date_from = strtotime($range['from']);
      # 100 columns max
      while($date_from <= strtotime($range['to']) && count($periods) < 100) {
        switch($columns) {
        case 'year' :
          $periods[] = date('Y', $date_from);
          $date_from = strtotime(date('Y-1-1', strtotime('+1 year', $date_from)));
          break;
        case 'month' :
          $periods[] = date('Y-n', $date_from);
          $date_from = strtotime(date('Y-n-1', strtotime('+1 month', $date_from)));
          break;
        case 'week' :
          $periods[] = date("Y-W", $date_from);
          $w = date('w', $time);
          if($w == 1) {
            $add = 7;
          } else {
            $add = 8 - $w;
          }
          $date_from = strtotime("+{$add} day", $date_from);
          break;
        case 'day' :
          $periods[] = date('Y-n-j', $date_from);
          $date_from = strtotime('+1 day', $date_from);
          break;
        }
      }
    } else {
      $total_hours = 0;
      $hours = array();
      $periods = array();
    }
    $this->set(compact('criterias', 'columns', 'available_criterias', 'total_hours', 'hours', 'periods'));
#    
#    respond_to do |format|
#      format.html { render :layout => !request.xhr? }
#      format.csv  { send_data(report_to_csv(@criterias, @periods, @hours).read, :type => 'text/csv; header=present', :filename => 'timelog.csv') }
#    end

  }
  
  function details() {
    if ($this->RequestHandler->isAjax()) {
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
      if(!empty($this->params['url']['format'])) {
        switch($this->params['url']['format']) {
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
        }
      } else { 
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
#  
#  def destroy
#    render_404 and return unless @time_entry
#    render_403 and return unless @time_entry.editable_by?(User.current)
#    @time_entry.destroy
#    flash[:notice] = l(:notice_successful_delete)
#    redirect_to :back
#  rescue ::ActionController::RedirectBackError
#    redirect_to :action => 'details', :project_id => @time_entry.project
#  end
#
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
    if(!empty($this->params['url']['issue_id']) || !empty($this->data['TimeEntry']['issue_id'])) {
      $issue_id = !empty($this->params['url']['issue_id']) ? $this->params['url']['issue_id'] : $this->data['TimeEntry']['issue_id'];
      $this->Issue->read(null, $issue_id);
      $this->params['project_id'] = $this->Issue->data['Project']['identifier'];
    } elseif(!empty($this->params['project_id'])) {
      ; // parent::beforeFilter
    }
    parent::_findProject();
    if(!$this->TimeEntry->User->is_allowed_to($this->current_user, 'view_time_entries', $this->_project, array('global' => true))) {
      // TODO deny_access
      $this->cakeError('error404');
    }
  }
}
