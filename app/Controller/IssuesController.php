<?php

/**
 * Issues Controller
 *
 * @package candycane
 * @subpackage candycane.controllers
 * @property Issue $Issue
 */
class IssuesController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Issues';

    /**
     * Models to use
     *
     * @var array
     */
    public $uses = array(
        'Issue',
        'User',
        'Query',
        'Version'
    );

    /**
     * Helpers
     *
     * @var array
     */
    public $helpers = array(
        'Issues',
        'Queries',
        'QueryColumn',
        'Paginator',
        'CustomField',
        'Number',
        'Watchers',
        'Journals',
        'Js' => array('Prototype')
    );

    /**
     * Components
     *
     * @var array
     */
    public $components = array(
        'RequestHandler',
        'Queries',
        'Mailer'
    );

    var $_query;
    var $_project;
    var $_issues;

    /**
     * AUthorize
     *
     * @var array
     */
    public $authorize = array('except' => array('index', 'changes', 'gantt', 'calendar', 'preview', 'update_form', 'context_menu'));

#class IssuesController < ApplicationController
#  menu_item :new_issue, :only => :new
#  
#  before_filter :find_issue, :only => [:show, :edit, :reply]
#  before_filter :find_issues, :only => [:bulk_edit, :move, :destroy]
#  before_filter :find_project, :only => [:new, :update_form, :preview]
#  before_filter :authorize, :except => [:index, :changes, :gantt, :calendar, :preview, :update_form, :context_menu]
#  before_filter :find_optional_project, :only => [:index, :changes, :gantt, :calendar]

    /**
     * beforeFilter callback
     *
     * @return boolean
     */
    public function beforeFilter()
    {
        $this->MenuManager->menu_item('issues', array('only' => array('show', 'edit', 'move', 'destroy')));

        $issue_id = false;
        $issue_ids = array();
        if (!empty($this->request->params['issue_id'])) {
            $issue_id = $this->request->params['issue_id'];
            $issue_ids[] = $issue_id;
        } elseif (!empty($this->request->query['ids'])) {
            $issue_id = $this->request->query['ids'][0];
            $issue_ids = $this->request->query['ids'];
        } elseif (!empty($this->request->data['Issue']['ids'])) {
            $issue_ids = $this->request->data['Issue']['ids'];
        }

        switch ($this->request->action) {
            case 'show':
            case 'changes':
            case 'edit':
            case 'reply':
                if ($issue_id) {
                    $this->_find_issue($issue_id);
                    $this->request->params['project_id'] = $this->Issue->data['Project']['identifier'];
                }
                break;
            case 'bulk_edit':
            case 'move':
            case 'destroy':
                $this->_issues = $this->_find_issues($issue_ids);
                $this->request->params['project_id'] = $this->_issues[0]['Project']['identifier'];
        }
        return parent::beforeFilter();
    }

    /**
     * beforeRender callback
     *
     * @return void
     */
    public function beforeRender()
    {
        $this->set('url_param', $this->request->params['url_param']);
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

    /**
     * Index action
     *
     * @return void
     */
    public function index()
    {
        if (!isset($this->request->query['query_id'])) {
            $this->request->query['query_id'] = 0;
        }
        $this->Queries->retrieve_query($this->request->query['query_id']);
        $limit = $this->_per_page_option();
        if (empty($this->request->params['named']['sort'])) {
            $this->request->params['named']['sort'] = 'Issue.id';
            $this->request->params['named']['direction'] = 'desc';
        }
        $this->paginate = array('Issue' => array(
            'conditions' => $this->Queries->query_filter_cond,
            'limit' => $limit,
        ));
        $this->sidebar_queries();
        if (empty($this->Query->data)) {
            $this->set('query', array('Query' => $this->Query->defaults()));
        } else {
            $this->set('query', $this->Query->data);
        }
        $this->Issue->unbindModel(array('hasMany' => array('TimeEntry')), false);
        $this->set('issue_list', $this->paginate('Issue'));
        $this->set('params', $this->request->params);
        if ($this->RequestHandler->isAjax()) {
            $this->layout = 'ajax';
        }
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

    /**
     * Changes action
     *
     * Single Issue RSS/Atom feed.
     * The method can access without login.
     * Call from only issue/show.
     *
     * @return void
     */
    public function changes()
    {
        Configure::write('debug', 2);
        $journals = $this->Issue->findRssJournal();
        $atom_title = $this->_project['Project']['name'];
        $rss_token = $this->User->rss_key($this->current_user['id']);
        $this->set(compact('journals', 'atom_title', 'rss_token'));
        $this->layout = 'rss/atom';
        $this->helpers = array('Candy', 'Issues', 'Time');
        return $this->render('changes');
    }

    /**
     * Show action
     *
     * @return void
     */
    public function show()
    {
        if ($this->_get_param('format') == 'atom') {
            return $this->changes();
        }

        $this->set(array('journal_list' => $this->Issue->findAllJournal($this->current_user)));

        // For Edit values
        $this->request->data = $this->Issue->data;

        $statuses = $this->Issue->findStatusList($this->User->role_for_project($this->current_user, $this->_project));
        if (empty($statuses)) {
            $this->Session->setFlash(
                __('No default issue status is defined. Please check your configuration (Go to "Administration -> Issue statuses").'),
                'default',
                array('class' => 'flash flash_error'));
            $this->redirect('index');
        }
        $this->set(compact('statuses'));
        $this->_set_edit_form_values();
        $allowed_statuses = $this->Issue->findStatusList(
            $this->User->role_for_project($this->current_user, $this->_project)
        );
        $this->set('allowed_statuses', $allowed_statuses);

        if (!empty($this->Issue->data['CustomValue'])) {
            foreach ($this->Issue->data['CustomValue'] as $value) {
                $this->request->data['custom_field_values'][$value['CustomField']['id']] = $value['value'];
            }
        }

        switch ($this->_get_param('format')) {
            case 'pdf':
                if (!class_exists('TCPDF')) {
                    $this->Session->setFlash(__('Missing TCPDF library, please install on your server.'), 'default', array('class' => 'flash flash_error'));
                    $this->render('missing_tcpdf');
                    return;
                }
                $this->layout = 'pdf';
                $this->helpers = array('Candy', 'CustomField', 'Issues', 'Number', 'Tcpdf' => array());
                $this->render('issue_to_pdf');
                break;
            case 'atom':
                break;

        }
        $this->sidebar_queries();
    }

    /**
     * Detail Values action
     *
     * JournalDetail values of history.
     * This called from IssuesHelper#show_detail on history.ctp
     *
     * @return void
     */
    public function detail_values()
    {
        if (empty($this->request->params['requested'])) {
            throw new NotFoundException();
        }
        return $this->Issue->findValuesByJournalDetail($this->request->params['detail']);
    }

    /**
     * Watched By action
     *
     * @return void
     */
    public function watched_by()
    {
        if (empty($this->request->params['requested'])) {
            throw new NotFoundException();
        }
        return $this->Issue->watched_by(array('User' => $this->current_user), $this->Issue->data);
    }

    /**
     * Add a new issue
     *
     * The new issue will be created from an existing one if copy_from parameter is given
     * Enter URLs :
     *    /projects/test/issues/add/copy_from:30
     *    /projects/test/issues/add/tracker_id:30
     *
     * @return void
     */
    public function add()
    {
        if ($this->_get_param('copy_from')) {
            $issue = $this->Issue->copy_from($this->_get_param('copy_from'));
            $this->request->data = $issue;
        }

        // Tracker must be set before custom field values
        $trackers = $this->Issue->findProjectsTrackerList($this->_project['Project']['id']);
        if (empty($trackers)) {
            $this->Session->setFlash(__("No tracker is associated to this project. Please check the Project settings."), 'default', array('class' => 'flash flash_error'));
            $this->redirect('index');
        }
        if ($this->_get_param('tracker_id')) {
            $this->request->data['Issue']['tracker_id'] = $this->_get_param('tracker_id');
        } elseif (empty($this->request->data['Issue']['tracker_id'])) {
            $this->request->data['Issue']['tracker_id'] = key($trackers);
        }
        $statuses = $this->Issue->findStatusList($this->User->role_for_project($this->current_user, $this->_project), $this->request->data['Issue']['tracker_id']);
        if (empty($statuses)) {
            $this->Session->setFlash(
                __('No default issue status is defined. Please check your configuration (Go to "Administration -> Issue statuses").'),
                'default',
                array('class' => 'flash flash_error'));
            return $this->redirect('index');
        }
        $this->set(compact('trackers', 'statuses'));
        $this->_set_edit_form_values();

        if (!empty($this->request->data) && $this->RequestHandler->isPost() && !$this->RequestHandler->isAjax()) {
            $save_data = array();
            $save_data['Issue'] = $this->request->data['Issue'];
            $save_data['Issue']['project_id'] = $this->_project['Project']['id'];
            $save_data['Issue']['author_id'] = $this->current_user['id'];
            if (array_key_exists('custom_field_values', $this->request->data)) {
                $save_data['Issue']['custom_field_values'] = $this->Issue->filterCustomFieldValue($this->request->data['custom_field_values']);
            }

            $event = new CakeEvent(
                'Controller.Candy.issuesNewBeforeSave',
                $this,
                array(
                    'issue' => $save_data
                )
            );
            $this->getEventManager()->dispatch($event);
            $save_data = $event->data['issue'];

            if (!$this->Issue->save($save_data) && empty($this->Issue->validationErrors)) {
                return $this->cakeError('error', array('message' => 'Can not save Issue.'));
            }

            if (empty($this->Issue->validationErrors)) {
                if (!empty($this->request->data['Attachments'])) {
                    $this->Issue->attach_files($this->request->data['Attachments'], $this->current_user);
                }

                $event = new CakeEvent(
                    'Controller.Candy.issuesNewAfterSave',
                    $this,
                    array(
                        'project' => $this->_project,
                        'issue' => $this->Issue,
                        'save_data' => $save_data
                    )
                );
                $this->getEventManager()->dispatch($event);

                $this->Session->setFlash(__('Successful update.'), 'default', array('class' => 'flash flash_notice'));
                $this->Mailer->deliver_issue_add($this->Issue);
                # Mailer.deliver_issue_add(@issue) if Setting.notified_events.include?('issue_added')
                if (!empty($this->request->data['continue'])) {
                    $this->redirect('/projects/' . $this->_project['Project']['identifier'] . '/issues/add/tracker_id:' . $this->request->data['Issue']['tracker_id']);
                }
                $this->redirect(array('action' => 'show', 'issue_id' => $this->Issue->getLastInsertID()));
            }
        } elseif (!$this->RequestHandler->isAjax() && empty($this->request->data['Issue']['start_date'])) {
            $this->request->data['Issue']['start_date'] = date('Y-m-d');
        }

        $this->render('new');
        if ($this->RequestHandler->isAjax()) {
            $this->layout = 'ajax';
        }
    }

    /**
     * Edit action
     *
     * Attributes that can be updated on workflow transition (without :edit permission)
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
     *
     * @return void
     * @todo Make it configurable (at least per role)
     */
    public function edit()
    {
        static $UPDATABLE_ATTRS_ON_TRANSITION = array('status_id', 'assigned_to_id', 'fixed_version_id', 'done_ratio');
        if (empty($this->request->params['issue_id'])) {
            return $this->cakeError('error', array('message' => "Not exists issue."));
        }
        $issue = $this->_find_issue($this->request->params['issue_id']);
        if (empty($this->_project)) {
            $this->request->params['project_id'] = $issue['Project']['identifier'];
            parent::_findProject();
        }
        $statuses = $this->Issue->findStatusList($this->User->role_for_project($this->current_user, $this->_project));
        if (empty($statuses)) {
            $this->Session->setFlash(__('No default issue status is defined. Please check your configuration (Go to "Administration -> Issue statuses").'), 'default', array('class' => 'flash flash_error'));
            $this->redirect('index');
        }
        $this->request->data['Issue']['tracker_id'] = $issue['Issue']['tracker_id'];

        $this->set(compact('statuses'));
        $this->_set_edit_form_values();

        $notes = "";
        if ($this->_get_param('notes')) {
            $notes = $this->_get_param('notes');
        }
        unset($this->request->data['Issue']['notes']);
        $journal = $this->Issue->init_journal($issue, $this->current_user, $notes);
        $this->Issue->Journal->available_custom_fields = $this->Issue->cached_available_custom_fields();
        # User can change issue attributes only if he has :edit permission or if a workflow transition is allowed
        $edit_allowed = $this->User->is_allowed_to($this->current_user, ':edit_issues', $this->_project);
        if ($edit_allowed || !empty($statuses) && (!empty($this->request->params['url']['issue']) || !empty($this->request->data['Issue']))) {
            $attrs = empty($this->request->params['url']['issue']) ? $this->request->data['Issue'] : $this->request->params['url']['issue'];
            if (!$edit_allowed) {
                foreach ($attrs as $k => $v) {
                    if (!in_array($k, $UPDATABLE_ATTRS_ON_TRANSITION)) {
                        unset($attrs[$k]);
                    }
                }
            }
            if (!empty($attrs['status_id'])) {
                if (!array_key_exists($attrs['status_id'], $statuses)) {
                    unset($attrs['status_id']);
                }
            }
            $issue['Issue'] = array_merge($issue['Issue'], $attrs);
        }
        if (($this->RequestHandler->isPost() || $this->RequestHandler->isPut()) && !empty($this->request->data)) {
            $this->Issue->TimeEntry->create();
            $time_entry = array(
                'project_id' => $this->_project['Project']['id'],
                'issue_id' => $issue['Issue']['id'],
                'user_id' => $this->current_user['id'],
                'spent_on' => date('Y-m-d')
            );

            if (isset($this->request->data['TimeEntry'])) {
                $time_entry = array_merge($time_entry, $this->request->data['TimeEntry']);
            }

            $this->Issue->TimeEntry->set($time_entry);

            if (!empty($this->request->data['custom_field_values'])) {
                $this->Issue->TimeEntry->data['TimeEntry']['custom_field_values'] = $this->Issue->TimeEntry->filterCustomFieldValue($this->request->data['custom_field_values']);
            }
            $save_data = array();
            $save_data['Issue'] = $this->request->data['Issue'];
            $save_data['Issue']['id'] = $issue['Issue']['id'];
            $save_data['Issue']['project_id'] = $this->_project['Project']['id'];
            $save_data['Issue']['tracker_id'] = $issue['Issue']['tracker_id'];
            if (!empty($this->request->data['custom_field_values'])) {
                $save_data['Issue']['custom_field_values'] = $this->Issue->filterCustomFieldValue($this->request->data['custom_field_values']);
            }
            if ($this->User->is_allowed_to($this->current_user, ':log_time', $this->_project)
                && !empty($this->Issue->TimeEntry->data['TimeEntry']['hours']) && $this->Issue->TimeEntry->validates()
            ) {
                # Log spend time
                $save_data['TimeEntry'] = array($this->Issue->TimeEntry->data['TimeEntry']);
            }
            if (!empty($this->request->data['Attachments'])) {
                $attachments = $this->Issue->attach_files($this->request->data['Attachments'], $this->current_user);
                if (!empty($attachments['unsaved'])) {
                    $this->Session->setFlash(sprintf(__("%d file(s) could not be saved."), count($attachments['unsaved'])), 'default', array('class' => 'flash flash_warning'));
                }
                foreach ($attachments['attached'] as $a) {
                    $this->Issue->attachJournalDetails[] = array(
                        'property' => 'attachment',
                        'prop_key' => $a['id'],
                        'value' => $a['filename']
                    );
                }
            }
            $event = new CakeEvent(
                'Controller.Candy.issuesEditBeforeSave',
                $this,
                array(
                    'issue' => $this->Issue,
                    'journal' => $journal
                )
            );
            $this->getEventManager()->dispatch($event);


            if ($this->Issue->saveAll($save_data)) {
                if ($this->Issue->actually_changed) {

                    $event = new CakeEvent(
                        'Controller.Candy.issuesEditAfterSave',
                        $this,
                        array(
                            'project' => $this->_project,
                            'issue' => $this->Issue,
                            'save_data' => $save_data,
                            'journal' => $journal,
                            'notes' => $notes
                        )
                    );
                    $this->getEventManager()->dispatch($event);

                    # Only send notification if something was actually changed
                    $this->Session->setFlash(__('Successful update.'), 'default', array('class' => 'flash flash_notice'));
                    $this->Mailer->deliver_issue_edit($journal, $this->Issue);
                }
                if (!empty($this->request->params['url']['back_to'])) {
                    $this->redirect($this->request->params['url']['back_to']);
                }
                $this->redirect(array(
                    'action' => 'show',
                    $issue['Issue']['id']
                ));
            }
            $this->request->data['Issue']['notes'] = $notes;
        } else {
            $this->request->data = $issue;
        }
        if ($this->RequestHandler->isAjax()) {
            $this->layout = 'ajax';
        }

#  rescue ActiveRecord::StaleObjectError
#    # Optimistic locking exception
#    flash.now[:error] = l(:notice_locking_conflict)
#  end

    }

    /**
     * Reply action
     *
     * @return void
     */
    public function reply()
    {
        if (!$this->RequestHandler->isAjax()) {
            throw new NotFoundException();
        }
        Configure::write('debug', 0);
        $issue = $this->_find_issue($this->request->params['issue_id']);
        $Journal =& ClassRegistry::init('Journal');
        // $Journal->bindModel(array('belongsTo'=>array('User'), 'hasMany'=>array('JournalDetail')),false);
        $journal = false;
        if (isset($this->request->params['pass'][0]) && $this->request->params['pass'][0]) {
            $journal = $Journal->read(null, $this->request->params['pass'][0]);
        }
        if (!empty($journal)) {
            $user = $journal['User'];
            $text = $journal['Journal']['notes'];
        } else {
            $user = $this->Issue->data['Author'];
            $text = $this->Issue->data['Issue']['description'];
        }
        $this->layout = 'ajax';
        $this->set(compact('user', 'text'));
    }

    /**
     * Bulk edit a set of issues
     *
     * @return void
     */
    function bulk_edit()
    {
        $role_id = $this->User->role_for_project($this->current_user, $this->_project);
        if ($this->RequestHandler->isPost() && !empty($this->request->data)) {
            $status_id = $this->_get_param('status_id');
            $priority_id = $this->_get_param('priority_id');
            $assigned_to_id = $this->_get_param('assigned_to_id');
            $category_id = $this->_get_param('category_id');
            $fixed_version_id = $this->_get_param('fixed_version_id');
            $status = empty($status_id) ? null : $this->Issue->Status->findById($status_id);
            $priority = empty($priority_id) ? null : $this->Issue->Priority->findById($priority_id);
            $assigned_to = (empty($assigned_to_id) || $assigned_to_id == 'none') ? null : $this->User->findById($assigned_to_id);
            $category = (empty($category_id) || $category_id == 'none') ? null : $this->Issue->Category->findById($category_id);
            $fixed_version = (empty($fixed_version_id) || $fixed_version_id == 'none') ? null : $this->Issue->FixedVersion->findById($fixed_version_id);

            $unsaved_issue_ids = array();
            foreach ($this->_issues as $issue) {
                $journal = $this->Issue->init_journal($issue, $this->current_user, $this->_get_param('notes'));
                if ($priority) {
                    $issue['Issue']['priority_id'] = $priority_id;
                }
                if ($assigned_to) {
                    $issue['Issue']['assigned_to_id'] = $assigned_to_id;
                }
                if ($assigned_to_id == 'none') {
                    $issue['Issue']['assigned_to_id'] = null;
                }
                if ($category) {
                    $issue['Issue']['category_id'] = $category_id;
                }
                if ($category_id == 'none') {
                    $issue['Issue']['category_id'] = null;
                }
                if ($fixed_version) {
                    $issue['Issue']['fixed_version_id'] = $fixed_version_id;
                }
                if ($fixed_version_id == 'none') {
                    $issue['Issue']['fixed_version_id'] = null;
                }
                if ($this->_get_param('start_date') != null) {
                    $issue['Issue']['start_date'] = $this->_get_param('start_date');
                }
                if ($this->_get_param('due_date') != null) {
                    $issue['Issue']['due_date'] = $this->_get_param('due_date');
                }
                if ($this->_get_param('done_ratio') != null) {
                    $issue['Issue']['done_ratio'] = $this->_get_param('done_ratio');
                }

                # call_hook(:controller_issues_bulk_edit_before_save, { :params => params, :issue => issue })
                // Don't save any change to the issue if the user is not authorized to apply the requested status
                $result = true;
                if ($status) {
                    if ($this->Issue->Status->is_new_status_allowed_to($status_id, $role_id, $issue['Issue']['tracker_id'])) {
                        $issue['Issue']['status_id'] = $status_id;
                    } else {
                        $result = false;
                    }
                }
                if ($result) {
                    $result = $this->Issue->save($issue);
                    $this->Issue->Journal = null; //unset Journal for next loop
                }
                # Send notification for each issue (if changed)
                if ($result) {
                    $this->Mailer->deliver_issue_edit($journal, $this->Issue);
                } else {
                    // Keep unsaved issue ids to display them in flash error
                    $unsaved_issue_ids[] = $issue['Issue']['id'];
                }
            }
            if (empty($unsaved_issue_ids)) {
                if (!empty($issues)) {
                    $this->Session->setFlash(__('Successful update.'), 'default', array('class' => 'flash flash_notice'));
                }
            } else {
                $this->Session->setFlash(sprintf(__("\"Failed to save %d issue(s) on %d selected"), count($unsaved_issue_ids), count($issues)) . '#' . join(', #', $unsaved_issue_ids), 'default', array('class' => 'flash flash_error'));
            }
            if ($this->_get_param('back_to') != null) {
                $this->redirect($this->_get_param('back_to'));
            } else {
                $this->redirect(array('controller' => 'issues', 'action' => 'index', 'project_id' => $this->_project['Project']['identifier']));
            }
            return;
        }

        // Find potential statuses the user could be allowed to switch issues to
        $workflow = & ClassRegistry::init('Workflow');
        $workflow->bindModel(array('belongsTo' => array('Status' => array('className' => 'IssueStatus', 'foreignKey' => 'new_status_id', 'order' => 'position'))), false);
        $available_statuses = $workflow->find('all', array('conditions' => array('role_id' => $role_id), 'fields' => 'Status.*'));
        $this->set('available_statuses', $available_statuses);
        $this->set('_issues', $this->_issues);
        $this->set('priorities', $this->Issue->Priority->get_values('IPRI'));

        $assignable_users = $this->Issue->Project->assignable_users($this->_project['Project']['id']);
        $issue_categories = $this->Issue->Category->find('list', array('conditions' => array('project_id' => $this->_project['Project']['id'])));
        $fixed_versions = $this->Issue->Project->Version->find('list', array('order' => array('effective_date', 'name')));
        $this->set(compact('assignable_users', 'issue_categories', 'fixed_versions'));
    }

    /**
     * Move action
     *
     * @return void
     */
    function move()
    {
        $issue_ids = false;
        if (!empty($this->request->params['issue_id'])) {
            $issue_ids = $this->request->params['issue_id'];
        } elseif (!empty($this->request->params['url']['ids'])) {
            $issue_ids = $this->request->params['url']['ids'];
        } elseif (!empty($this->request->data['Issue']['ids'])) {
            $issue_ids = $this->request->data['Issue']['ids'];
        } else {
            return $this->cakeError('error', array('message' => "Not exists issue."));
        }

        if (!is_array($issue_ids)) {
            $issue_ids = array($issue_ids);
        }
        $allowed_projects = array();
        $issues = $this->Issue->find('all', array('conditions' => array('Issue.id' => $issue_ids)));
        if (empty($issues)) {
            return $this->cakeError('error', array('message' => "Not exists issue."));
        }

        // find projects to which the user is allowed to move the issue
        if ($this->current_user['admin']) {
            // admin is allowed to move issues to any active (visible) project
            $allowed_projects = $this->Issue->Project->find('list', array('conditions' => $this->Issue->Project->visible_by($this->current_user), 'order' => 'name'));
        } else {
            $Role = & ClassRegistry::init('Role');
            foreach ($this->current_user['memberships'] as $member) {
                if ($Role->is_allowed_to($member, ':move_issues')) {
                    $allowed_projects[$member['Project']['id']] = $member['Project']['name'];
                }
            }
        }
        if (!array_key_exists($issues[0]['Issue']['project_id'], $allowed_projects)) {
            return $this->cakeError('error', array('message' => "Permission deny."));
        }
        if ($this->RequestHandler->isPost() && !$this->RequestHandler->isAjax()) {
            $move_count = 0;
            foreach ($issues as $issue) {
                $this->Issue->init_journal($issue, $this->current_user);
                if ($this->Issue->move_to($this->Setting, $issue, $this->request->data['Issue']['project_id'], $this->request->data['Issue']['tracker_id'])) {
                    $move_count++;
                }
            }
            if ($move_count == count($issues)) {
                $this->Session->setFlash(__('Successful update.'), 'default', array('class' => 'flash flash_notice'));
            } else {
                $this->Session->setFlash(sprintf(__("\"Failed to save %d issue(s) on %d selected"), $move_count, count($issues)), 'default', array('class' => 'flash flash_error'));
            }
            if ($this->RequestHandler->isAjax()) {
                $this->layout = 'ajax';
                return;
            }
            $this->redirect(array(
                'controller' => 'issues',
                'project_id' => $this->_project['Project']['identifier'],
                'action' => 'index'
            ));
        } elseif ($this->RequestHandler->isAjax() && !empty($this->request->data['Issue']['project_id'])) {
            if (!array_key_exists($this->request->data['Issue']['project_id'], $allowed_projects)) {
                $this->request->data['Issue']['project_id'] = $issues[0]['Issue']['project_id'];
            }
        } else {
            $this->request->data['Issue']['project_id'] = $issues[0]['Issue']['project_id'];
        }
        $this->request->params['project_id'] = $issues[0]['Project']['identifier'];
        $trackers = $this->Issue->findProjectsTrackerList($this->request->data['Issue']['project_id']);
        $this->set(compact('allowed_projects', 'trackers'));
        $this->set('issue_datas', $issues);
        if ($this->RequestHandler->isAjax()) {
            $this->layout = 'ajax';
        }
    }

    /**
     * Destroy action
     *
     * @return void
     */
    public function destroy()
    {
        $issue_ids = false;
        if (!empty($this->request->params['issue_id'])) {
            $issue_ids = $this->request->params['issue_id'];
        } elseif (!empty($this->request->query['ids'])) {
            $issue_ids = $this->request->query['ids'];
        } elseif (!empty($this->request->data['Issue']['ids'])) {
            $issue_ids = $this->request->data['Issue']['ids'];
        } else {
            return $this->cakeError('error', array('message' => "Not exists issue."));
        }

        if (!is_array($issue_ids)) {
            $issue_ids = array($issue_ids);
        }
        $issues = $this->Issue->find('all', array('conditions' => array('Issue.id' => $issue_ids)));
        if (empty($issues)) {
            return $this->cakeError('error', array('message' => "Not exists issue."));
        }

        $this->request->params['project_id'] = $issues[0]['Project']['identifier'];
        $this->set('issue_datas', $issues);
        $TimeEntry = & ClassRegistry::init('TimeEntry');
        $hours = $TimeEntry->sum('hours', array('issue_id' => $issue_ids));
        $this->set(compact('hours'));
        if ($hours > 0) {
            if (empty($this->request->data['Issue']['todo'])) {
                // display the destroy form
                $this->request->data['Issue']['todo'] = 'destroy';
                return;
            }

            switch ($this->request->data['Issue']['todo']) {
                case 'destroy':
                    // Nothing to do
                    break;
                case 'nullify':
                    $TimeEntry->updateAll(array('issue_id' => null), array('issue_id' => $issue_ids));
                    break;
                case 'reassign':
                    if (!$this->Issue->hasAny(array('Issue.id' => $this->request->data['Issue']['reassign_to_id']))) {
                        $this->Session->setFlash(__("'The issue was not found or does not belong to this project'"), 'default', array('class' => 'flash flash_error'));
                        return;
                    }
                    $TimeEntry->updateAll(array("issue_id" => $this->request->data['Issue']['reassign_to_id']), array('issue_id' => $issue_ids));
                    break;
                default:
                    // Display the destroy form
                    return;
            }
        }

        if ($this->Issue->deleteAll(array('Issue.id' => $issue_ids))) {
            $this->Session->setFlash(__('Successful deletion.'), 'default', array('class' => 'flash flash_notice'));
        } else {
            $this->Session->setFlash(sprintf(__("\"Failed to save %d issue(s) on %d selected"), 1, 1), 'default', array('class' => 'flash flash_error'));
        }
        $this->redirect('/projects/' . $this->_project['Project']['identifier'] . '/issues/index');
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

    /**
     * Context menu
     *
     * @return void
     */
    function context_menu()
    {
        $this->layout = 'ajax';
        Configure::write('debug', Configure::read('debug') > 1 ? 1 : 0);

        $params = $this->request->data;
        $issues = $this->Issue->find('all', array('conditions' => array('Issue.id' => $params['ids']), 'recursive' => -1));
        $this->set('issue_list', $issues);
        $allowed_statuses = array();
        $issue = false;
        if (count($issues) == 1) {
            $issue = $this->Issue->data = $issues[0];
            $allowed_statuses = $this->Issue->findStatusList($this->User->role_for_project($this->current_user, $this->_project));
        }
        $this->set('issue', $issue);
        $this->set('allowed_statuses', $allowed_statuses);

        $project_ids = array_unique(Set::extract("/Issue/project_id", $issues));
        $project = $this->Issue->Project->find('first', array('conditions' => array('Project.id' => $project_ids[0]), 'recursive' => 1));
        $this->set('project', $project);
        $ProjectsTracker = ClassRegistry::init('ProjectsTracker');
        $project_trackers = Set::extract("/ProjectsTracker/tracker_id",
            $ProjectsTracker->find('all', array('conditions' => array('project_id' => $project_ids[0])))
        );
        $this->set('can', array(
            'edit' => (!empty($project) && $this->User->is_allowed_to($this->current_user, 'edit_issues', $project)),
            'log_time' => (!empty($project) && $this->User->is_allowed_to($this->current_user, 'log_time', $project)),
            'update' => (!empty($project) && ($this->User->is_allowed_to($this->current_user, 'edit_issues', $project) || ($this->User->is_allowed_to($this->current_user, 'change_status', $project) && !empty($allowed_statuses)))),
            'move' => (!empty($project) && $this->User->is_allowed_to($this->current_user, 'move_issues', $project)),
            'copy' => ($issue && in_array($issue['Issue']['tracker_id'], $project_trackers) && $this->User->is_allowed_to($this->current_user, 'add_issues', $project)),
            'delete' => (!empty($project) && $this->User->is_allowed_to($this->current_user, 'delete_issues', $project))
        ));
        $assignables = array();
        if (!empty($project)) {
            $assignables = $this->Issue->Project->assignable_users($project['Project']['id']);
            if ($issue && $issue['Issue']['assigned_to_id'] && !array_key_exists($issue['Issue']['assigned_to_id'], $assignables)) {
                $user = $this->User->read(null, $issue['Issue']['assigned_to_id']);
                $assignables[$user['User']['id']] = $user['User']['firstname'] . ' ' . $user['User']['lastname'];
            }
        }
        $this->set('assignables', $assignables);

        $this->set('priorities', $this->Issue->Priority->get_values('IPRI', 'DESC'));
        $this->set('statuses', $this->Issue->Status->find('all', array('order' => 'position')));
        $this->set('back', $this->referer());
    }

    public function update_form()
    {
        $statuses = $this->Issue->findStatusList($this->User->role_for_project($this->current_user, $this->_project), $this->request->data['Issue']['tracker_id']);
        $this->set(compact('statuses'));
        $this->_set_edit_form_values();

        $this->layout = 'ajax';
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

    /**
     * Preview
     *
     * @return void
     * @todo Attachments
     */
    function preview()
    {
        if ($this->RequestHandler->isAjax()) {
            Configure::write('debug', 0);
            $this->layout = 'ajax';
        }

        // TODO attachement
        // @issue = @project.issues.find_by_id(params[:id]) unless params[:id].blank?
        // $attachements = $issue.attachments if @issue
        if (array_key_exists('notes', $this->request->data['Issue'])) {
            $text = $this->request->data['Issue']['notes'];
        } elseif (array_key_exists('description', $this->request->data['Issue'])) {
            $text = $this->request->data['Issue']['description'];
        } else {
            $text = '';
        }
        $this->set(compact('text'));
        $this->render('/common/_preview');
    }

    /**
     * Find an issue
     *
     * @param string $id Issue ID
     * @return array Issue data
     * @access private
     */
    function _find_issue($id)
    {
        $this->Issue->recursive = 1;
        if ($this->Issue->read(null, $id) === false) {
            throw new NotFoundException();
        }
        $this->set(array('issue' => $this->Issue->data));
        return $this->Issue->data;
    }

    /**
     * Set edit form values
     *
     * @return void
     * @access protected
     */
    function _set_edit_form_values()
    {
        if (empty($this->Issue->data)) {
            $priorities = $this->Issue->findPriorities($this->request->data['Issue']['priority_id']);
        } else {
            $a = 0;
            //reference hack. To be refactored.
            $priorities = $this->Issue->findPriorities($a);
        }

        $assignable_users = $this->Issue->Project->assignable_users($this->_project['Project']['id']);

        // Issue categories
        $issue_categories = $this->Issue->Category->find('list', array(
            'conditions' => array('project_id' => $this->_project['Project']['id'])
        ));

        // Issue Versions
        $fixed_versions = $this->Version->find('list', array(
            'conditions' => array('project_id' => $this->_project['Project']['id']),
            'order' => array('effective_date', 'name')
        ));

        $custom_field_values = $this->Issue->available_custom_fields($this->_project['Project']['id'], $this->request->data['Issue']['tracker_id']);

        $this->set(compact('priorities', 'assignable_users', 'issue_categories', 'fixed_versions', 'custom_field_values'));
        if ($this->request->action == 'add') {
            $members = $this->Issue->Project->members($this->_project['Project']['id']);
            $this->set(compact('members'));
        } else {
            $time_entry_custom_fields = $this->Issue->TimeEntry->available_custom_fields();
            $time_entry_activities = $this->Issue->findTimeEntryActivities();
            $rss_token = $this->User->rss_key($this->current_user['id']);
            $attachments = $this->Issue->findAttachments($this->Issue->data['Issue']['id']);
            $attachments_deletable = $this->Issue->is_attachments_deletable($this->current_user, $this->_project);

            $IssueRelation = & ClassRegistry::init('IssueRelation');
            $issue_relations = $IssueRelation->findRelations($this->Issue->data);

            $this->set(compact(
                'time_entry_custom_fields', 'time_entry_activities', 'rss_token',
                'attachments', 'attachments_deletable', 'issue_relations'
            ));
        }
    }

    /**
     * Filter for bulk operations
     *
     * @param array $ids Array of issue IDs
     * @return array Issues
     * @access protected
     * @todo Let users bulk edit/move/destroy issues from different projects
     */
    function _find_issues($ids)
    {
        $issues = $this->Issue->find('all', array(
            'conditions' => array('Issue.id' => $ids)
        ));
        if (empty($issues)) {
            throw new NotFoundException();
        }
        $project_ids = array_unique(Set::extract("/Issue/project_id", $issues));
        $projects = $this->Issue->Project->find('all', array('conditions' => array('Project.id' => $project_ids)));
        if (count($projects) == 1) {
            $project = $projects[0];
        } else {
            // @TODO: let users bulk edit/move/destroy issues from different projects
            $this->cakeError('Can not bulk edit/move/destroy issues from different projects');
        }
        return $issues;
    }

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

    /**
     * Move from IssuesHelper
     *
     * @return void
     */
    public function sidebar_queries()
    {
        // User can see public queries and his own queries
        $user_id = 0;
        if ($this->current_user && $this->current_user['logged']) {
            $user_id = $this->current_user['id'];
        }
        $visible = array();
        $visible[] = array(
            'OR' => array(
                'Query.is_public' => true,
                'Query.user_id' => $user_id
            )
        );

        // Project specific queries and global queries
        if (empty($this->_project)) {
            $visible[] = array("Query.project_id" => null);
        } else {
            $visible[] = array(
                'OR' => array(
                    'Query.project_id' => null,
                    'Query.project_id' => $this->_project['Project']['id']
                )
            );
        }
        $sidebar_queries = $this->Query->find('all', array(
            'order' => "Query.name ASC",
            'conditions' => $visible
        ));
        $this->set('sidebar_queries', $sidebar_queries);
    }
}
