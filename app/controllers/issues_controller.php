<?php
class IssuesController extends AppController
{
  var $name = 'Issues';
  var $uses = array(
    'Issue',
    'User',
    'Query',
    'Project',
    'Enumeration',
  );
  var $helpers = array(
    'Issues',
    'Queries',
    'QueryColumn',
    'Paginator',
    'CustomField',
    'Number',
    'Watchers',
    'Journals'
  );
  var $components = array(
    'RequestHandler',
  );
  var $_query;
  var $_show_filters;
  var $_project;
  var $_issue;
  
#class IssuesController < ApplicationController
#  menu_item :new_issue, :only => :new
#  
#  before_filter :find_issue, :only => [:show, :edit, :reply]
#  before_filter :find_issues, :only => [:bulk_edit, :move, :destroy]
#  before_filter :find_project, :only => [:new, :update_form, :preview]
#  before_filter :authorize, :except => [:index, :changes, :gantt, :calendar, :preview, :update_form, :context_menu]
#  before_filter :find_optional_project, :only => [:index, :changes, :gantt, :calendar]
  function beforeFilter()
  {
    switch ($this->action) {
    case 'show':
      $this->_find_issue($this->params['issue_id']);
      $this->params['project_id'] = $this->_issue['Project']['identifier'];
      break;
    }
    return parent::beforeFilter();
  }
  function beforeRender()
  {
    $this->set('url_param', $this->params['url_param']);
    parent::beforeRender();
  }
#  accept_key_auth :index, :changes
#
#  helper :journals
#  helper :projects
#  include ProjectsHelper   
#  helper :custom_fields
#  include CustomFieldsHelper
#  helper :issue_relations
#  include IssueRelationsHelper
#  helper :watchers
#  include WatchersHelper
#  helper :attachments
#  include AttachmentsHelper
#  helper :queries
#  helper :sort
#  include SortHelper
#  include IssuesHelper
#  helper :timelog
#  include Redmine::Export::PDF
#  
  function index()
  {
    $this->_retrieve_query();
    $limit = $this->_per_page_option();
    $this->paginate = array('Issue' => array(
      'conditions' => $this->_query['Query']['filter_cond'],
      'order' => 'Issue.id DESC',
      'limit' => $limit,
    ));
    $this->set('issue_list', $this->paginate('Issue'));
    $this->set('params', $this->params);
    if ($this->RequestHandler->isAjax()) $this->layout = 'ajax';
  }
#  def index
#    retrieve_query
#    sort_init 'id', 'desc'
#    sort_update({'id' => "#{Issue.table_name}.id"}.merge(@query.columns.inject({}) {|h, c| h[c.name.to_s] = c.sortable; h}))
#    
#    if @query.valid?
#      limit = per_page_option
#      respond_to do |format|
#        format.html { }
#        format.atom { }
#        format.csv  { limit = Setting.issues_export_limit.to_i }
#        format.pdf  { limit = Setting.issues_export_limit.to_i }
#      end
#      @issue_count = Issue.count(:include => [:status, :project], :conditions => @query.statement)
#      @issue_pages = Paginator.new self, @issue_count, limit, params['page']
#      @issues = Issue.find :all, :order => sort_clause,
#                           :include => [ :assigned_to, :status, :tracker, :project, :priority, :category, :fixed_version ],
#                           :conditions => @query.statement,
#                           :limit  =>  limit,
#                           :offset =>  @issue_pages.current.offset
#      respond_to do |format|
#        format.html { render :template => 'issues/index.rhtml', :layout => !request.xhr? }
#        format.atom { render_feed(@issues, :title => "#{@project || Setting.app_title}: #{l(:label_issue_plural)}") }
#        format.csv  { send_data(issues_to_csv(@issues, @project).read, :type => 'text/csv; header=present', :filename => 'export.csv') }
#        format.pdf  { send_data(issues_to_pdf(@issues, @project), :type => 'application/pdf', :filename => 'export.pdf') }
#      end
#    else
#      # Send html if the query is not valid
#      render(:template => 'issues/index.rhtml', :layout => !request.xhr?)
#    end
#  rescue ActiveRecord::RecordNotFound
#    render_404
#  end
#  
#  def changes
#    retrieve_query
#    sort_init 'id', 'desc'
#    sort_update({'id' => "#{Issue.table_name}.id"}.merge(@query.columns.inject({}) {|h, c| h[c.name.to_s] = c.sortable; h}))
#    
#    if @query.valid?
#      @journals = Journal.find :all, :include => [ :details, :user, {:issue => [:project, :author, :tracker, :status]} ],
#                                     :conditions => @query.statement,
#                                     :limit => 25,
#                                     :order => "#{Journal.table_name}.created_on DESC"
#    end
#    @title = (@project ? @project.name : Setting.app_title) + ": " + (@query.new_record? ? l(:label_changes_details) : @query.name)
#    render :layout => false, :content_type => 'application/atom+xml'
#  rescue ActiveRecord::RecordNotFound
#    render_404
#  end
#  
  function show()
  {
    $Journal = & ClassRegistry::init('Journal');
//    $Journal->bindModel(array('belongsTo'=>array('User'), 'hasMany'=>array('JournalDetail')),false);
    $conditions = array('journalized_type'=>'Issue', 'journalized_id'=>$this->_issue['Issue']['id']);
    $journal_list = $Journal->find('all', array('conditions'=>$conditions,'recursive'=>1, 'order'=>"Journal.created_on ASC"));
    if(!empty($journal_list) && !empty($this->current_user['wants_comments_in_reverse_order'])) {
      $journal_list = array_reverse($journal_list);
    }
    $this->set(compact(
      'journal_list'
    ));

    // For Edit values
    $issue = $this->_issue;
    $default_status = $this->Issue->Status->findDefault();
    if(empty($default_status)) {
      $this->Session->setFlash(__('No default issue status is defined. Please check your configuration (Go to "Administration -> Issue statuses").',true), 'default', array('class'=>'flash flash_error'));
      $this->redirect('index');
    }
    $allowed_statuses = $this->Issue->Status->find_new_statuses_allowed_to(
      key($default_status),
      $this->User->role_for_project($this->current_user, $this->_project),
      $issue['Issue']['tracker_id']
    );
    $statuses = $default_status;
    foreach($allowed_statuses as $id => $value) {
      $statuses[$id] = $value;
    }
    $priority_datas = $this->Enumeration->get_values('IPRI');
    $priorities = array();
    foreach($priority_datas as $priority) {
      $priorities[$priority['Enumeration']['id']] = $priority['Enumeration']['name'];
      if(empty($this->data['Issue']['priority_id']) && $priority['Enumeration']['is_default']) {
        $this->data['Issue']['priority_id'] = $priority['Enumeration']['id'];
      }
    }
    $TimeEntry = & ClassRegistry::init('TimeEntry');
    $assignable_users = $this->Project->assignable_users($this->_project['Project']['id']);
    $issue_categories = $this->Issue->Category->find('list', array('conditions'=>array('project_id'=>$this->_project['Project']['id'])));
    $fixed_versions = $this->Project->Version->find('list', array('order'=>array('effective_date', 'name')));
    $custom_field_values = $this->Issue->available_custom_fields(
      $this->_project['Project']['id'],
      $issue['Issue']['tracker_id']
    );
    $time_entry_custom_fields = $TimeEntry->available_custom_fields();
    $time_entry_activity_datas = $this->Enumeration->get_values('ACTI');
    $time_entry_activities = array();
    foreach($time_entry_activity_datas as $time_entry_activity) {
      $time_entry_activities[$time_entry_activity['Enumeration']['id']] = $time_entry_activity['Enumeration']['name'];
    }
    $rss_token = $this->User->rss_key($this->current_user['id']);
    $attachments = $this->Issue->findAttachments($issue['Issue']['id']);
    $attachments_deletable = $this->Issue->is_attachments_deletable($this->current_user, $this->_project);

    $IssueRelation = & ClassRegistry::init('IssueRelation');
    $issue_relations = $IssueRelation->findRelations($issue);
    $this->set(compact(
      'statuses', 'priorities', 'assignable_users', 'issue_categories', 'fixed_versions', 
      'custom_field_values', 'time_entry_custom_fields', 'time_entry_activities', 'rss_token', 
      'attachments', 'attachments_deletable', 'issue_relations'));
    $this->data = $issue;

  }
#  def show
#    @journals = @issue.journals.find(:all, :include => [:user, :details], :order => "#{Journal.table_name}.created_on ASC")
#    @journals.each_with_index {|j,i| j.indice = i+1}
#    @journals.reverse! if User.current.wants_comments_in_reverse_order?
#    @allowed_statuses = @issue.new_statuses_allowed_to(User.current)
#    @edit_allowed = User.current.allowed_to?(:edit_issues, @project)
#    @priorities = Enumeration::get_values('IPRI')
#    @time_entry = TimeEntry.new
#    respond_to do |format|
#      format.html { render :template => 'issues/show.rhtml' }
#      format.atom { render :action => 'changes', :layout => false, :content_type => 'application/atom+xml' }
#      format.pdf  { send_data(issue_to_pdf(@issue), :type => 'application/pdf', :filename => "#{@project.identifier}-#{@issue.id}.pdf") }
#    end
#  end
  /**
   * JournalDetail values of history.
   * This called from IssuesHelper#show_detail on history.ctp
   */
  function detail_values() {
    if(empty($this->params['requested'])) {
      $this->cakeError('error404');
    }
    $result = $this->Issue->findValuesByJournalDetail($this->params['detail']);
    return $result;
  }

  /**
   * Add a new issue
   * The new issue will be created from an existing one if copy_from parameter is given
   * Enter URLs :
   *    /projects/test/issues/add/copy_from:30
   *    /projects/test/issues/add/tracker_id:30
   */
  function add() {
    if(!empty($this->params['named']['copy_from'])) {
      $issue = $this->Issue->copy_from($this->params['named']['copy_from']);
      if(!empty($issue['CustomValue'])) {
        $issue['custom_field_values'] = array();
        foreach($issue['CustomValue'] as $customValue) {
          $issue['custom_field_values'][$customValue['custom_field_id']] = $customValue['value'];
        }
        unset($issue['CustomValue']);
      }
      $this->data = $issue;
    }
    if(!empty($this->params['named']['tracker_id'])) {
      $this->data['Issue']['tracker_id'] = $this->params['named']['tracker_id'];
    }
    # Tracker must be set before custom field values
    $trackers = $this->Issue->Project->ProjectsTracker->find('list', array(
      'conditions'=>array('ProjectsTracker.project_id' => $this->_project['Project']['id']),
      'fields'=>'Tracker.id, Tracker.name', 'recursive'=>0, 'order'=>'Tracker.position'
    ));
    if(empty($trackers)){
      $this->Session->setFlash(__("No tracker is associated to this project. Please check the Project settings.", true), 'default', array('class'=>'flash flash_error'));
      $this->redirect('index');
    }
    $default_status = $this->Issue->Status->findDefault();
    if(empty($default_status)) {
      $this->Session->setFlash(__('No default issue status is defined. Please check your configuration (Go to "Administration -> Issue statuses").',true), 'default', array('class'=>'flash flash_error'));
      $this->redirect('index');
    }
    $allowed_statuses = $this->Issue->Status->find_new_statuses_allowed_to(
      key($default_status),
      $this->User->role_for_project($this->current_user, $this->_project),
      empty($this->data['Issue']['tracker_id']) ? key($trackers) : $this->data['Issue']['tracker_id']
    );
    $statuses = $default_status;
    foreach($allowed_statuses as $id => $value) {
      $statuses[$id] = $value;
    }
    if(!empty($this->data) && $this->RequestHandler->isPost() && !$this->RequestHandler->isAjax()) {
      $this->data['Issue']['project_id'] = $this->_project['Project']['id'];
      $this->data['Issue']['author_id'] = $this->current_user['id'];
      if(!$this->Issue->save($this->data)) {
        return $this->cakeError('error', array('message'=>"Can not save Issue."));
      }
      // TODO : attach file 
      # attach_files(@issue, params[:attachments])
      $this->Session->setFlash(__('Successful update.', true), 'default', array('class'=>'flash flash_notice'));
      # Mailer.deliver_issue_add(@issue) if Setting.notified_events.include?('issue_added')
      if(!empty($this->params['form']['continue'])) {
        $this->redirect('/projects/'.$this->_project['Project']['identifier'].'/issues/add/tracker_id:'.$this->data['Issue']['tracker_id']);
      }
      $this->redirect(array('action'=>'show', 'id'=>$this->Issue->getLastInsertID()));
    } elseif(!$this->RequestHandler->isAjax() && empty($this->data['Issue']['start_date'])) {
      $this->data['Issue']['start_date'] = date('Y-m-d');
    }
    $priority_datas = $this->Enumeration->get_values('IPRI');
    $priorities = array();
    foreach($priority_datas as $priority) {
      $priorities[$priority['Enumeration']['id']] = $priority['Enumeration']['name'];
      if(empty($this->data['Issue']['priority_id']) && $priority['Enumeration']['is_default']) {
        $this->data['Issue']['priority_id'] = $priority['Enumeration']['id'];
      }
    }
    $assignable_users = $this->Project->assignable_users($this->_project['Project']['id']);
    $issue_categories = $this->Issue->Category->find('list', array('conditions'=>array('project_id'=>$this->_project['Project']['id'])));
    $fixed_versions = $this->Project->Version->find('list', array('order'=>array('effective_date', 'name')));
    $custom_field_values = $this->Issue->available_custom_fields(
      $this->_project['Project']['id'],
      empty($this->data['Issue']['tracker_id']) ? key($trackers) : $this->data['Issue']['tracker_id']
    );
    $members = $this->Project->members($this->_project['Project']['id']);

    $this->set(compact(
      'trackers', 'statuses', 'priorities', 'assignable_users', 'issue_categories', 
      'fixed_versions', 'custom_field_values', 'members'));
    $this->render('new');
    if($this->RequestHandler->isAjax()) {
      $this->layout = 'ajax';
    }
  }
  
  /**
   * Attributes that can be updated on workflow transition (without :edit permission)
   * TODO: make it configurable (at least per role)
   *  
   *
   * /projects/test/issues/edit/25?backto=url&issue[status_id]=3
   * Array
   * (
   *     [url] => projects/test/issues/edit/25
   *     [backto] => url
   *     [issue] => Array
   *        (
   *           [status_id] => 3
   *       )
   * )
   */
  function edit() {
    static $UPDATABLE_ATTRS_ON_TRANSITION = array('status_id', 'assigned_to_id', 'fixed_version_id', 'done_ratio');
    if(empty($this->params['issue_id'])) {
      return $this->cakeError('error', array('message'=>"Not exists issue."));
    }
    $issue = $this->_find_issue($this->params['issue_id']);
    if(empty($this->_project)) {
      $this->params['project_id'] = $issue['Project']['identifier'];
      parent::_findProject();
    }
    $default_status = $this->Issue->Status->findDefault();
    if(empty($default_status)) {
      $this->Session->setFlash(__('No default issue status is defined. Please check your configuration (Go to "Administration -> Issue statuses").',true), 'default', array('class'=>'flash flash_error'));
      $this->redirect('index');
    }
    $allowed_statuses = $this->Issue->Status->find_new_statuses_allowed_to(
      key($default_status),
      $this->User->role_for_project($this->current_user, $this->_project),
      $issue['Issue']['tracker_id']
    );
    $statuses = $default_status;
    foreach($allowed_statuses as $id => $value) {
      $statuses[$id] = $value;
    }
    $priority_datas = $this->Enumeration->get_values('IPRI');
    $priorities = array();
    foreach($priority_datas as $priority) {
      $priorities[$priority['Enumeration']['id']] = $priority['Enumeration']['name'];
      if(empty($this->data['Issue']['priority_id']) && $priority['Enumeration']['is_default']) {
        $this->data['Issue']['priority_id'] = $priority['Enumeration']['id'];
      }
    }
    $TimeEntry = & ClassRegistry::init('TimeEntry');

    $notes = "";
    if(!empty($this->params['url']['notes'])) {
      $notes = $this->params['url']['notes'];
    }
    if(!empty($this->data['Issue']['notes'])) {
      $notes = $this->data['Issue']['notes'];
      unset($this->data['Issue']['notes']);
    }
    $this->Issue->init_journal($issue, $this->current_user, $notes);
    # User can change issue attributes only if he has :edit permission or if a workflow transition is allowed
    $edit_allowed = $this->User->is_allowed_to($this->current_user, ':edit_issues', $this->_project);
    if($edit_allowed || !empty($allowed_statuses) && (!empty($this->params['url']['issue']) || !empty($this->data['Issue'])) ) {
      $attrs = empty($this->params['url']['issue']) ? $this->data['Issue'] : $this->params['url']['issue'];
      if(!$edit_allowed) {
        foreach($attrs as $k=>$v) {
          if(!in_array($k, $UPDATABLE_ATTRS_ON_TRANSITION)) {
            unset($attrs[$k]);
          }
        }
      }
      if(!empty($attrs['status_id'])) {
        if(!array_key_exists($attrs['status_id'], $allowed_statuses)) {
          unset($attrs['status_id']);
        }
      }
      $issue['Issue'] = array_merge($issue['Issue'], $attrs);
    }
    if($this->RequestHandler->isPost()) {
      $TimeEntry.create();
      $TimeEntry.set(array_merge(array(
        'project_id'=>$this->_project['Project']['id'],
        'issue_id'  =>$issue['Issue']['id'],
        'user_id'   =>$this->current_user['id'],
        'spent_on'  =>new Date('Y-m-d')
      ), $this->data['TimeEntry']));
      // TODO Issue edit attachement :
      // $attachments = attach_files(@issue, params[:attachments])
      // attachments.each {|a| journal.details << JournalDetail.new(:property => 'attachment', :prop_key => a.id, :value => a.filename)}
      
      // call_hook(:controller_issues_edit_before_save, { :params => params, :issue => @issue, :time_entry => @time_entry, :journal => journal})
      
      if((empty($TimeEntry->data['TimeEntry']['hours']) || $TimeEntry->validates()) && $this->Issue->save($issue)) {
        # Log spend time
        if($this->User->is_allowed_to($this->current_user, ':log_time', $this->_project)) {
          $TimeEntry->save();
        }
        // TODO ƒWƒƒ[ƒiƒ‹‚Í•Û—¯
#        if !journal.new_record?
#          # Only send notification if something was actually changed
#          flash[:notice] = l(:notice_successful_update)
#          Mailer.deliver_issue_edit(journal) if Setting.notified_events.include?('issue_updated')
#        end
        if(!empty($this->params['url']['back_to'])) {
          $this->redirect($this->params['url']['back_to']);
        }
        $this->redirect(array('action'=>'show', 'id'=>$issue['Issue']['id']));
      }
    }
    $assignable_users = $this->Project->assignable_users($this->_project['Project']['id']);
    $issue_categories = $this->Issue->Category->find('list', array('conditions'=>array('project_id'=>$this->_project['Project']['id'])));
    $fixed_versions = $this->Project->Version->find('list', array('order'=>array('effective_date', 'name')));
    $custom_field_values = $this->Issue->available_custom_fields(
      $this->_project['Project']['id'],
      $issue['Issue']['tracker_id']
    );
    $time_entry_custom_fields = $TimeEntry->available_custom_fields();
    $time_entry_activity_datas = $this->Enumeration->get_values('ACTI');
    $time_entry_activities = array();
    foreach($time_entry_activity_datas as $time_entry_activity) {
      $time_entry_activities[$time_entry_activity['Enumeration']['id']] = $time_entry_activity['Enumeration']['name'];
    }
    $this->set(compact(
      'statuses', 'priorities', 'assignable_users', 'issue_categories', 'fixed_versions', 
      'custom_field_values', 'time_entry_custom_fields', 'time_entry_activities'));
    if($this->RequestHandler->isAjax()) {
      $this->layout = 'ajax';
    }

#  rescue ActiveRecord::StaleObjectError
#    # Optimistic locking exception
#    flash.now[:error] = l(:notice_locking_conflict)
#  end

  }
  
#
  function reply() {
    if(!$this->RequestHandler->isAjax()) {
      $this->cakeError('error404');
    }
    Configure::write('debug', 0);
    $issue = $this->_find_issue($this->params['issue_id']);
    $Journal = & ClassRegistry::init('Journal');
//    $Journal->bindModel(array('belongsTo'=>array('User'), 'hasMany'=>array('JournalDetail')),false);
    $journal = false;
    if($this->params['named']['journal_id']) {
      $journal = $Journal->read(null, $this->params['named']['journal_id']);
    }
    if(!empty($journal)) {
      $user = $journal['User'];
      $text = $journal['Journal']['notes'];
    } else {
      $user = $this->_issue['Author'];
      $text = $this->_issue['Issue']['description'];
    }
    $this->layout = 'ajax';
    $this->set(compact('user', 'text'));
  }
#  
#  # Bulk edit a set of issues
#  def bulk_edit
#    if request.post?
#      status = params[:status_id].blank? ? nil : IssueStatus.find_by_id(params[:status_id])
#      priority = params[:priority_id].blank? ? nil : Enumeration.find_by_id(params[:priority_id])
#      assigned_to = (params[:assigned_to_id].blank? || params[:assigned_to_id] == 'none') ? nil : User.find_by_id(params[:assigned_to_id])
#      category = (params[:category_id].blank? || params[:category_id] == 'none') ? nil : @project.issue_categories.find_by_id(params[:category_id])
#      fixed_version = (params[:fixed_version_id].blank? || params[:fixed_version_id] == 'none') ? nil : @project.versions.find_by_id(params[:fixed_version_id])
#      
#      unsaved_issue_ids = []      
#      @issues.each do |issue|
#        journal = issue.init_journal(User.current, params[:notes])
#        issue.priority = priority if priority
#        issue.assigned_to = assigned_to if assigned_to || params[:assigned_to_id] == 'none'
#        issue.category = category if category || params[:category_id] == 'none'
#        issue.fixed_version = fixed_version if fixed_version || params[:fixed_version_id] == 'none'
#        issue.start_date = params[:start_date] unless params[:start_date].blank?
#        issue.due_date = params[:due_date] unless params[:due_date].blank?
#        issue.done_ratio = params[:done_ratio] unless params[:done_ratio].blank?
#        call_hook(:controller_issues_bulk_edit_before_save, { :params => params, :issue => issue })
#        # Don't save any change to the issue if the user is not authorized to apply the requested status
#        if (status.nil? || (issue.status.new_status_allowed_to?(status, current_role, issue.tracker) && issue.status = status)) && issue.save
#          # Send notification for each issue (if changed)
#          Mailer.deliver_issue_edit(journal) if journal.details.any? && Setting.notified_events.include?('issue_updated')
#        else
#          # Keep unsaved issue ids to display them in flash error
#          unsaved_issue_ids << issue.id
#        end
#      end
#      if unsaved_issue_ids.empty?
#        flash[:notice] = l(:notice_successful_update) unless @issues.empty?
#      else
#        flash[:error] = l(:notice_failed_to_save_issues, unsaved_issue_ids.size, @issues.size, '#' + unsaved_issue_ids.join(', #'))
#      end
#      redirect_to(params[:back_to] || {:controller => 'issues', :action => 'index', :project_id => @project})
#      return
#    end
#    # Find potential statuses the user could be allowed to switch issues to
#    @available_statuses = Workflow.find(:all, :include => :new_status,
#                                              :conditions => {:role_id => current_role.id}).collect(&:new_status).compact.uniq.sort
#  end
#
  function move() {
    $issue_ids = false;
    if(!empty($this->params['issue_id'])) {
      $issue_ids = $this->params['issue_id'];
    } elseif(!empty($this->params['url']['ids'])) {
      $issue_ids = $this->params['url']['ids'];
    } elseif(!empty($this->data['Issue']['ids'])) {
      $issue_ids = $this->data['Issue']['ids'];
    } else {
      return $this->cakeError('error', array('message'=>"Not exists issue."));
    }

    if(!is_array($issue_ids)) {
      $issue_ids = array($issue_ids);
    }
    $allowed_projects = array();
    $issues = $this->Issue->find('all', array('conditions'=>array('Issue.id'=>$issue_ids)));
    if(empty($issues)) {
      return $this->cakeError('error', array('message'=>"Not exists issue."));
    }
    # find projects to which the user is allowed to move the issue
    if($this->current_user['admin']) {
      # admin is allowed to move issues to any active (visible) project
      $allowed_projects = $this->Project->find('list', array('conditions'=>$this->Project->visible_by($this->current_user), 'order'=>'name'));
    } else  {
      $Role = & ClassRegistry::init('Role');
      foreach($this->current_user['memberships'] as $member) {
        if($Role->is_allowed_to($member, ':move_issues')) {
          $allowed_projects[] = array($member['Project']['id']=>$member['Project']['name']);
        }
      }
    }
    if(!array_key_exists($issues[0]['Issue']['project_id'], $allowed_projects)) {
      return $this->cakeError('error', array('message'=>"Permission deny."));
    }
    if($this->RequestHandler->isPost() && !$this->RequestHandler->isAjax()) {
      $move_count = 0;
      foreach($issues as $issue) {
        $this->Issue->init_journal($issue, $this->current_user);
        if($this->Issue->move_to($this->Setting, $issue, $this->data['Issue']['project_id'], $this->data['Issue']['tracker_id'])) {
          $move_count++;
        }
      }
      if($move_count == count($issues)) {
        $this->Session->setFlash(__('Successful update.', true), 'default', array('class'=>'flash flash_notice'));
      } else {
        $this->Session->setFlash(sprintf(__("\"Failed to save %d issue(s) on %d selected", true), $move_count, count($issues)), 'default', array('class'=>'flash flash_error'));
      }
      if($this->RequestHandler->isAjax()) {
        $this->layout = 'ajax';
        return;
      }
      $this->redirect('/projects/'.$this->_project['Project']['identifier'].'/issues');
    } elseif($this->RequestHandler->isAjax() && !empty($this->data['Issue']['project_id'])) {
      if(!array_key_exists($this->data['Issue']['project_id'], $allowed_projects)) {
        $this->data['Issue']['project_id'] = $issues[0]['Issue']['project_id'];
      }
    } else {
      $this->data['Issue']['project_id'] = $issues[0]['Issue']['project_id'];
    }
    $trackers = $this->Project->ProjectsTracker->find('list', array(
      'conditions'=>array('ProjectsTracker.project_id'=>$this->data['Issue']['project_id']), 
      'fields'=>array('Tracker.id', 'Tracker.name'),
      'recursive'=>0
    ));
    $this->set(compact('allowed_projects', 'trackers'));
    $this->set('issue_datas', $issues);
    if($this->RequestHandler->isAjax()) {
      $this->layout = 'ajax';
    }
  }

  function destroy() {
    $issue_ids = false;
    if(!empty($this->params['issue_id'])) {
      $issue_ids = $this->params['issue_id'];
    } elseif(!empty($this->params['url']['ids'])) {
      $issue_ids = $this->params['url']['ids'];
    } elseif(!empty($this->data['Issue']['ids'])) {
      $issue_ids = $this->data['Issue']['ids'];
    } else {
      return $this->cakeError('error', array('message'=>"Not exists issue."));
    }
    if(!is_array($issue_ids)) {
      $issue_ids = array($issue_ids);
    }
    $issues = $this->Issue->find('all', array('conditions'=>array('Issue.id'=>$issue_ids)));
    if(empty($issues)) {
      return $this->cakeError('error', array('message'=>"Not exists issue."));
    }
    $this->set('issue_datas', $issues);
    $TimeEntry = & ClassRegistry::init('TimeEntry');
    $hours = $TimeEntry->sum('hours', array('issue_id'=>$issue_ids));
    $this->set(compact('hours'));
    if($hours > 0) {
      if(empty($this->data['Issue']['todo'])) {
        # display the destroy form
        $this->data['Issue']['todo'] = 'destroy';
        return;
      }
      switch($this->data['Issue']['todo']) {
      case 'destroy' :
        # nothing to do
        break;
      case 'nullify' :
        $TimeEntry->updateAll(array('issue_id'=>null), array('issue_id'=>$issue_ids));
        break;
      case 'reassign' :
        if(!$this->Issue->hasAny(array('Issue.id'=>$this->data['Issue']['reassign_to_id']))) {
          $this->Session->setFlash(__("'The issue was not found or does not belong to this project'", true), 'default', array('class'=>'flash flash_error'));
          return;
        }
        $TimeEntry->updateAll(array("issue_id"=>$this->data['Issue']['reassign_to_id']), array('issue_id'=>$issue_ids));
        break;
      default :
        # display the destroy form
        return;
      }
    }
    if($this->Issue->deleteAll(array('Issue.id'=>$issue_ids))) {
      $this->Session->setFlash(__('Successful deletion.', true), 'default', array('class'=>'flash flash_notice'));
    } else {
      $this->Session->setFlash(sprintf(__("\"Failed to save %d issue(s) on %d selected", true), 1, 1), 'default', array('class'=>'flash flash_error'));
    }
    $this->redirect('/projects/'.$this->_project['Project']['identifier'].'/issues');
  }
#  
#  def gantt
#    @gantt = Redmine::Helpers::Gantt.new(params)
#    retrieve_query
#    if @query.valid?
#      events = []
#      # Issues that have start and due dates
#      events += Issue.find(:all, 
#                           :order => "start_date, due_date",
#                           :include => [:tracker, :status, :assigned_to, :priority, :project], 
#                           :conditions => ["(#{@query.statement}) AND (((start_date>=? and start_date<=?) or (due_date>=? and due_date<=?) or (start_date<? and due_date>?)) and start_date is not null and due_date is not null)", @gantt.date_from, @gantt.date_to, @gantt.date_from, @gantt.date_to, @gantt.date_from, @gantt.date_to]
#                           )
#      # Issues that don't have a due date but that are assigned to a version with a date
#      events += Issue.find(:all, 
#                           :order => "start_date, effective_date",
#                           :include => [:tracker, :status, :assigned_to, :priority, :project, :fixed_version], 
#                           :conditions => ["(#{@query.statement}) AND (((start_date>=? and start_date<=?) or (effective_date>=? and effective_date<=?) or (start_date<? and effective_date>?)) and start_date is not null and due_date is null and effective_date is not null)", @gantt.date_from, @gantt.date_to, @gantt.date_from, @gantt.date_to, @gantt.date_from, @gantt.date_to]
#                           )
#      # Versions
#      events += Version.find(:all, :include => :project,
#                                   :conditions => ["(#{@query.project_statement}) AND effective_date BETWEEN ? AND ?", @gantt.date_from, @gantt.date_to])
#                                   
#      @gantt.events = events
#    end
#    
#    respond_to do |format|
#      format.html { render :template => "issues/gantt.rhtml", :layout => !request.xhr? }
#      format.png  { send_data(@gantt.to_image, :disposition => 'inline', :type => 'image/png', :filename => "#{@project.identifier}-gantt.png") } if @gantt.respond_to?('to_image')
#      format.pdf  { send_data(gantt_to_pdf(@gantt, @project), :type => 'application/pdf', :filename => "#{@project.nil? ? '' : "#{@project.identifier}-" }gantt.pdf") }
#    end
#  end
#  
#  def calendar
#    if params[:year] and params[:year].to_i > 1900
#      @year = params[:year].to_i
#      if params[:month] and params[:month].to_i > 0 and params[:month].to_i < 13
#        @month = params[:month].to_i
#      end    
#    end
#    @year ||= Date.today.year
#    @month ||= Date.today.month
#    
#    @calendar = Redmine::Helpers::Calendar.new(Date.civil(@year, @month, 1), current_language, :month)
#    retrieve_query
#    if @query.valid?
#      events = []
#      events += Issue.find(:all, 
#                           :include => [:tracker, :status, :assigned_to, :priority, :project], 
#                           :conditions => ["(#{@query.statement}) AND ((start_date BETWEEN ? AND ?) OR (due_date BETWEEN ? AND ?))", @calendar.startdt, @calendar.enddt, @calendar.startdt, @calendar.enddt]
#                           )
#      events += Version.find(:all, :include => :project,
#                                   :conditions => ["(#{@query.project_statement}) AND effective_date BETWEEN ? AND ?", @calendar.startdt, @calendar.enddt])
#                                     
#      @calendar.events = events
#    end
#    
#    render :layout => false if request.xhr?
#  end
#  
  function context_menu()
  {
    $this->layout = 'ajax';
    Configure::write('debug', Configure::read('debug') > 1 ? 1 : 0);
  }
#  def context_menu
#    @issues = Issue.find_all_by_id(params[:ids], :include => :project)
#    if (@issues.size == 1)
#      @issue = @issues.first
#      @allowed_statuses = @issue.new_statuses_allowed_to(User.current)
#    end
#    projects = @issues.collect(&:project).compact.uniq
#    @project = projects.first if projects.size == 1
#
#    @can = {:edit => (@project && User.current.allowed_to?(:edit_issues, @project)),
#            :log_time => (@project && User.current.allowed_to?(:log_time, @project)),
#            :update => (@project && (User.current.allowed_to?(:edit_issues, @project) || (User.current.allowed_to?(:change_status, @project) && @allowed_statuses && !@allowed_statuses.empty?))),
#            :move => (@project && User.current.allowed_to?(:move_issues, @project)),
#            :copy => (@issue && @project.trackers.include?(@issue.tracker) && User.current.allowed_to?(:add_issues, @project)),
#            :delete => (@project && User.current.allowed_to?(:delete_issues, @project))
#            }
#    if @project
#      @assignables = @project.assignable_users
#      @assignables << @issue.assigned_to if @issue && @issue.assigned_to && !@assignables.include?(@issue.assigned_to)
#    end
#    
#    @priorities = Enumeration.get_values('IPRI').reverse
#    @statuses = IssueStatus.find(:all, :order => 'position')
#    @back = request.env['HTTP_REFERER']
#    
#    render :layout => false
#  end
#
#  def update_form
#    @issue = Issue.new(params[:issue])
#    render :action => :new, :layout => false
#  end
#  
#  def preview
#    @issue = @project.issues.find_by_id(params[:id]) unless params[:id].blank?
#    @attachements = @issue.attachments if @issue
#    @text = params[:notes] || (params[:issue] ? params[:issue][:description] : nil)
#    render :partial => 'common/preview'
#  end
#  
#private
  function _find_issue($id)
  {
    if ($this->_issue = $this->Issue->find('first', array(
      'conditions'=>array('Issue.id' => $id),
      'recursive'=>1
    ))) {
      $this->set(array('issue'=>$this->_issue));
      return $this->_issue;
    } else {
      $this->cakeErorr('error404');
    }
  }
#  def find_issue
#    @issue = Issue.find(params[:id], :include => [:project, :tracker, :status, :author, :priority, :category])
#    @project = @issue.project
#  rescue ActiveRecord::RecordNotFound
#    render_404
#  end
#  
#  # Filter for bulk operations
#  def find_issues
#    @issues = Issue.find_all_by_id(params[:id] || params[:ids])
#    raise ActiveRecord::RecordNotFound if @issues.empty?
#    projects = @issues.collect(&:project).compact.uniq
#    if projects.size == 1
#      @project = projects.first
#    else
#      # TODO: let users bulk edit/move/destroy issues from different projects
#      render_error 'Can not bulk edit/move/destroy issues from different projects' and return false
#    end
#  rescue ActiveRecord::RecordNotFound
#    render_404
#  end
#  
#  def find_project
#    @project = Project.find(params[:project_id])
#  rescue ActiveRecord::RecordNotFound
#    render_404
#  end
#  
#  def find_optional_project
#    @project = Project.find(params[:project_id]) unless params[:project_id].blank?
#    allowed = User.current.allowed_to?({:controller => params[:controller], :action => params[:action]}, @project, :global => true)
#    allowed ? true : deny_access
#  rescue ActiveRecord::RecordNotFound
#    render_404
#  end
#  
#  # Retrieve query from session or build a new query
  function _retrieve_query()
  {
    $this->set('force_show_filters', $force_show_filters = $this->Query->show_filters());
    $show_filters = isset($this->params['url']['set_filter']) ? a() : $force_show_filters;
    $available_filters = $this->Query->available_filters($this->_project, $this->current_user);
    $query = a();
    if (!isset($this->data['Filter'])) $this->data['Filter'] = a();
    foreach ($show_filters as $field => $options) {
      $this->data['Filter']['fields_' . $field] = $field;
      $this->data['Filter']['operators_' . $field] = $options['operator'];
      $this->data['Filter']['values_' . $field] = $options['values'];
    }
    if (isset($this->params['query_id'])) {
    } else {
      $query = $this->Query->defaults();
      $query = am($query, $this->_project);
      $query['Query']['filter_cond'][] = array('Issue.project_id' => $this->_project['Project']['id']);
      if (isset($this->params['url']['set_filter'], $this->params['form']['fields'])) {
        foreach ($this->params['form']['fields'] as $field) {
          $operator = $this->params['form']['operators'][$field];
          $value = isset($this->params['form']['values'][$field]) ? $this->params['form']['values'][$field] : null;
          if (isset($available_filters[$field])) {
            $show_filters[$field] = $available_filters[$field];
            $this->data['Filter']['fields_' . $field] = $field;
            $this->data['Filter']['operators_' . $field] = $operator;
            $this->data['Filter']['values_' . $field] = $value;
          }
        }
      }
    }
    foreach ($show_filters as $field => $options) {
      $operator = $this->data['Filter']['operators_' . $field];
      $value = $this->data['Filter']['values_' . $field];
      switch ($field) {
      case 'author_id':
      case 'assigned_to_id':
        if ($value == 'me') {
          if ($this->current_user) {
            $value = $this->current_user['id'];
          } else {
            continue;
          }
        }
        break;
      }
      if ($add_filter_cond = $this->Query->get_filter_cond('Issue', $field, $operator, $value)) {
        $query['Query']['filter_cond'][] = $add_filter_cond;
      }
    }
    $this->set('available_filters', $available_filters);
    $this->set('show_filters', $this->_show_filters = $show_filters);
    $this->set('query', $this->_query = $query);
  }
#  def retrieve_query
#    if !params[:query_id].blank?
#      cond = "project_id IS NULL"
#      cond << " OR project_id = #{@project.id}" if @project
#      @query = Query.find(params[:query_id], :conditions => cond)
#      @query.project = @project
#      session[:query] = {:id => @query.id, :project_id => @query.project_id}
#    else
#      if params[:set_filter] || session[:query].nil? || session[:query][:project_id] != (@project ? @project.id : nil)
#        # Give it a name, required to be valid
#        @query = Query.new(:name => "_")
#        @query.project = @project
#        if params[:fields] and params[:fields].is_a? Array
#          params[:fields].each do |field|
#            @query.add_filter(field, params[:operators][field], params[:values][field])
#          end
#        else
#          @query.available_filters.keys.each do |field|
#            @query.add_short_filter(field, params[field]) if params[field]
#          end
#        end
#        session[:query] = {:project_id => @query.project_id, :filters => @query.filters}
#      else
#        @query = Query.find_by_id(session[:query][:id]) if session[:query][:id]
#        @query ||= Query.new(:name => "_", :project => @project, :filters => session[:query][:filters])
#        @query.project = @project
#      end
#    end
#  end
#end
}