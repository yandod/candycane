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
  
#  def report
#    @available_criterias = { 'project' => {:sql => "#{TimeEntry.table_name}.project_id",
#                                          :klass => Project,
#                                          :label => :label_project},
#                             'version' => {:sql => "#{Issue.table_name}.fixed_version_id",
#                                          :klass => Version,
#                                          :label => :label_version},
#                             'category' => {:sql => "#{Issue.table_name}.category_id",
#                                            :klass => IssueCategory,
#                                            :label => :field_category},
#                             'member' => {:sql => "#{TimeEntry.table_name}.user_id",
#                                         :klass => User,
#                                         :label => :label_member},
#                             'tracker' => {:sql => "#{Issue.table_name}.tracker_id",
#                                          :klass => Tracker,
#                                          :label => :label_tracker},
#                             'activity' => {:sql => "#{TimeEntry.table_name}.activity_id",
#                                           :klass => Enumeration,
#                                           :label => :label_activity},
#                             'issue' => {:sql => "#{TimeEntry.table_name}.issue_id",
#                                         :klass => Issue,
#                                         :label => :label_issue}
#                           }
#    
#    # Add list and boolean custom fields as available criterias
#    custom_fields = (@project.nil? ? IssueCustomField.for_all : @project.all_issue_custom_fields)
#    custom_fields.select {|cf| %w(list bool).include? cf.field_format }.each do |cf|
#      @available_criterias["cf_#{cf.id}"] = {:sql => "(SELECT c.value FROM #{CustomValue.table_name} c WHERE c.custom_field_id = #{cf.id} AND c.customized_type = 'Issue' AND c.customized_id = #{Issue.table_name}.id)",
#                                             :format => cf.field_format,
#                                             :label => cf.name}
#    end if @project
#    
#    # Add list and boolean time entry custom fields
#    TimeEntryCustomField.find(:all).select {|cf| %w(list bool).include? cf.field_format }.each do |cf|
#      @available_criterias["cf_#{cf.id}"] = {:sql => "(SELECT c.value FROM #{CustomValue.table_name} c WHERE c.custom_field_id = #{cf.id} AND c.customized_type = 'TimeEntry' AND c.customized_id = #{TimeEntry.table_name}.id)",
#                                             :format => cf.field_format,
#                                             :label => cf.name}
#    end
#    
#    @criterias = params[:criterias] || []
#    @criterias = @criterias.select{|criteria| @available_criterias.has_key? criteria}
#    @criterias.uniq!
#    @criterias = @criterias[0,3]
#    
#    @columns = (params[:columns] && %w(year month week day).include?(params[:columns])) ? params[:columns] : 'month'
#    
#    retrieve_date_range
#    
#    unless @criterias.empty?
#      sql_select = @criterias.collect{|criteria| @available_criterias[criteria][:sql] + " AS " + criteria}.join(', ')
#      sql_group_by = @criterias.collect{|criteria| @available_criterias[criteria][:sql]}.join(', ')
#      
#      sql = "SELECT #{sql_select}, tyear, tmonth, tweek, spent_on, SUM(hours) AS hours"
#      sql << " FROM #{TimeEntry.table_name}"
#      sql << " LEFT JOIN #{Issue.table_name} ON #{TimeEntry.table_name}.issue_id = #{Issue.table_name}.id"
#      sql << " LEFT JOIN #{Project.table_name} ON #{TimeEntry.table_name}.project_id = #{Project.table_name}.id"
#      sql << " WHERE"
#      sql << " (%s) AND" % @project.project_condition(Setting.display_subprojects_issues?) if @project
#      sql << " (%s) AND" % Project.allowed_to_condition(User.current, :view_time_entries)
#      sql << " (spent_on BETWEEN '%s' AND '%s')" % [ActiveRecord::Base.connection.quoted_date(@from.to_time), ActiveRecord::Base.connection.quoted_date(@to.to_time)]
#      sql << " GROUP BY #{sql_group_by}, tyear, tmonth, tweek, spent_on"
#      
#      @hours = ActiveRecord::Base.connection.select_all(sql)
#      
#      @hours.each do |row|
#        case @columns
#        when 'year'
#          row['year'] = row['tyear']
#        when 'month'
#          row['month'] = "#{row['tyear']}-#{row['tmonth']}"
#        when 'week'
#          row['week'] = "#{row['tyear']}-#{row['tweek']}"
#        when 'day'
#          row['day'] = "#{row['spent_on']}"
#        end
#      end
#      
#      @total_hours = @hours.inject(0) {|s,k| s = s + k['hours'].to_f}
#      
#      @periods = []
#      # Date#at_beginning_of_ not supported in Rails 1.2.x
#      date_from = @from.to_time
#      # 100 columns max
#      while date_from <= @to.to_time && @periods.length < 100
#        case @columns
#        when 'year'
#          @periods << "#{date_from.year}"
#          date_from = (date_from + 1.year).at_beginning_of_year
#        when 'month'
#          @periods << "#{date_from.year}-#{date_from.month}"
#          date_from = (date_from + 1.month).at_beginning_of_month
#        when 'week'
#          @periods << "#{date_from.year}-#{date_from.to_date.cweek}"
#          date_from = (date_from + 7.day).at_beginning_of_week
#        when 'day'
#          @periods << "#{date_from.to_date}"
#          date_from = date_from + 1.day
#        end
#      end
#    end
#    
#    respond_to do |format|
#      format.html { render :layout => !request.xhr? }
#      format.csv  { send_data(report_to_csv(@criterias, @periods, @hours).read, :type => 'text/csv; header=present', :filename => 'timelog.csv') }
#    end
#  end
#  
  function details() {
    $this->TimeEntry->_customFieldAfterFindDisable = true;
    $this->Sort->sort_init('spent_on', 'desc');
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
      $data = $this->data['TimeEntry'];
    }
    $result = $this->TimeEntry->details_condition($this->Setting, $this->current_user, $this->_project, $this->Issue->data, $data);
    // $result ==> $cond, $range
    extract($result);
    $data = array_merge($data, $range);
    $this->params['url'] = $data;
    $visible = $this->TimeEntry->find_visible_by($this->current_user, $this->_project);
    if(!empty($visible)) {
      if(!empty($this->params['named']['format'])) {
        switch($this->params['named']['format']) {
        case 'pdf' :
//          $this->layout = 'pdf';
//          $this->helpers = array('Candy', 'CustomField', 'Issues', 'Number', 'Tcpdf'=>array());
//          $this->render('issue_to_pdf');
          break;
        case 'atom' :
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
#      respond_to do |format|
#        format.html {
#          # Paginate results
#          @entry_count = TimeEntry.count(:include => :project, :conditions => cond.conditions)
#          @entry_pages = Paginator.new self, @entry_count, per_page_option, params['page']
#          @entries = TimeEntry.find(:all, 
#                                    :include => [:project, :activity, :user, {:issue => :tracker}],
#                                    :conditions => cond.conditions,
#                                    :order => sort_clause,
#                                    :limit  =>  @entry_pages.items_per_page,
#                                    :offset =>  @entry_pages.current.offset)
#          @total_hours = TimeEntry.sum(:hours, :include => :project, :conditions => cond.conditions).to_f
#
#          render :layout => !request.xhr?
#        }
#        format.atom {
#          entries = TimeEntry.find(:all,
#                                   :include => [:project, :activity, :user, {:issue => :tracker}],
#                                   :conditions => cond.conditions,
#                                   :order => "#{TimeEntry.table_name}.created_on DESC",
#                                   :limit => Setting.feeds_limit.to_i)
#          render_feed(entries, :title => l(:label_spent_time))
#        }
#        format.csv {
#          # Export all entries
#          @entries = TimeEntry.find(:all, 
#                                    :include => [:project, :activity, :user, {:issue => [:tracker, :assigned_to, :priority]}],
#                                    :conditions => cond.conditions,
#                                    :order => sort_clause)
#          send_data(entries_to_csv(@entries).read, :type => 'text/csv; header=present', :filename => 'timelog.csv')
#        }
#      end
#    end

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
    if(!empty($this->params['url']['issue_id'])) {
      $this->Issue->read(null, $this->params['url']['issue_id']);
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
