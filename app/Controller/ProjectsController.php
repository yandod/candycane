<?php
/**
 * Projects Controller
 *
 * @package candycane
 * @subpackage candycane.controllers
 */
class ProjectsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Projects';

    /**
     * Models to use
     *
     * @var array
     */
    public $uses = array(
        'Attachment',
        'CustomFieldsProject',
        'CustomValue',
        'EnabledModule',
        'Event',
        'Issue',
        'IssueCategory',
        'IssueCustomField',
        'News',
        'Permission',
        'Project',
        'TimeEntry',
        'Tracker',
        'User',
        'Version',
    );

    /**
     * View helpers
     *
     * @var array
     */
    public $helpers = array(
        'AppAjax',
        'CustomField',
        'Number',
        'Project',
        'Time',
        'Wiki',
        'Js' => array('Prototype')
    );

    /**
     * Components
     *
     * @var array
     */
    public $components = array(
        'Fetcher',
        'RequestHandler',
    );

#  menu_item :overview
#  menu_item :activity, :only => :activity
#  menu_item :roadmap, :only => :roadmap
#  menu_item :files, :only => [:list_files, :add_file]
#  menu_item :settings, :only => :settings
#  menu_item :issues, :only => [:changelog]

    /**
     * beforeFilter Callback
     *
     * before_filter :find_project, :except => [ :index, :list, :add, :activity ]
     * before_filter :find_optional_project, :only => :activity
     * before_filter :authorize, :except => [ :index, :list, :add, :archive, :unarchive, :destroy, :activity ]
     * before_filter :require_admin, :only => [ :add, :archive, :unarchive, :destroy ]
     *
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();

        $except = array('index', 'list', 'add', 'activity');
        if (!in_array($this->request->action, $except)) {
            $this->find_project();
        }

        /*
        if ($this->request->action == 'activity') {
            $this->find_optional_project();
        }

        $except = array('index', 'list', 'add', 'archive', 'unarchive', 'destroy', 'activity');
        if (!in_array($this->request->action, $except)) {
            $this->authorize();
        }
        */

        $only = array('add', 'archive', 'unarchive', 'destroy', 'settings');
        if (in_array($this->request->action, $only)) {
            $this->require_admin();
        }
    }

#  accept_key_auth :activity
#  
#  helper :sort
#  include SortHelper
#  helper :custom_fields
#  include CustomFieldsHelper   
#  helper :issues
#  helper IssuesHelper
#  helper :queries
#  include QueriesHelper
#  helper :repositories
#  include RepositoriesHelper
#  include ProjectsHelper
#  
#  # Lists visible projects
#  def index
#    projects = Project.find :all,
#                            :conditions => Project.visible_by(User.current),
#                            :include => :parent
#    respond_to do |format|
#      format.html { 
#        @project_tree = projects.group_by {|p| p.parent || p}
#        @project_tree.keys.each {|p| @project_tree[p] -= [p]} 
#      }
#      format.atom {
#        render_feed(projects.sort_by(&:created_on).reverse.slice(0, Setting.feeds_limit.to_i), 
#                                  :title => "#{Setting.app_title}: #{l(:label_project_latest)}")
#      }
#    end
#  end

    /**
     * Index action
     *
     * @return void
     */
    public function index()
    {
        $cond = $this->Project->get_visible_by_condition($this->current_user);
        $projects = $this->Project->find('all', array(
            'conditions' => $cond
        ));
        $sub_project_tree = array();
        foreach ($projects as $key => $val) {
            foreach ($val as $key2 => $val2) {
                if ($key2 == 'Project') {
                    if (empty($val2['parent_id'])) {
                        $project_tree[] = $val2;
                    } else {
                        $sub_project_tree[$val2['parent_id']][] = $val2;
                    }
                }
            }
        }
        $this->set('project_tree', $project_tree);
        $this->set('sub_project_tree', $sub_project_tree);
    }

    /**
     * Add action
     *
     * @return void
     */
    public function add()
    {
        $trackers = $this->Tracker->find('all');
        $this->set('trackers', $trackers);

        #    @issue_custom_fields = IssueCustomField.find(:all, :order => "#{CustomField.table_name}.position")
        $issue_custom_fields = $this->IssueCustomField->find('all', array('order' => $this->IssueCustomField->name . ".position"));
        $this->set('issue_custom_fields', $issue_custom_fields);

        $root_project_inputs = $this->Project->find('all', array(
            'conditions' => array(
                $this->Project->name . '.parent_id' => null,
                $this->Project->name . '.status' => PROJECT_STATUS_ACTIVE),
            'order' => $this->Project->name . '.name'));

        $root_projects = array(null => '');
        foreach ($root_project_inputs as $project) {
            $root_projects[$project['Project']['id']] = $project['Project']['name'];
        }
        $this->set('root_projects', $root_projects);

        #      @project.enabled_module_names = Redmine::AccessControl.available_project_modules
        $enabled_module_names = $this->Permission->available_project_modules();
        $this->set('enabled_module_names', $enabled_module_names);

        if (!empty($this->request->data)) {
            $this->request->data['Tracker']['Tracker'] = array_filter($this->request->data['Project']['Tracker']);
            if ($this->Project->save($this->request->data, true, array('name', 'description', 'parent_id', 'identifier', 'homepage', 'is_public'))) {
                if (isset($this->request->data['Project']['issue_custom_field_ids'])) {
                    foreach ($this->request->data['Project']['issue_custom_field_ids'] as $custom_field_id) {
                        $this->CustomFieldsProject->save(array('custom_field_id' => $custom_field_id, 'project_id' => $this->request->data->id));
                    }
                }
                foreach (array_filter($this->request->data['Project']['EnabledModule']) as $enabledModule) {
                    $this->EnabledModule->create();
                    $this->EnabledModule->save(array('name' => $enabledModule, 'project_id' => $this->Project->id));
                }
                $this->Session->setFlash(__('Successful creation.'), 'default', array('class' => 'flash notice'));
                $this->redirect(array('controller' => 'admin', 'action' => 'projects'));
            }
        }
    }

#  # Add a new project
#  def add
#    @issue_custom_fields = IssueCustomField.find(:all, :order => "#{CustomField.table_name}.position")
#    @trackers = Tracker.all
#    @root_projects = Project.find(:all,
#                                  :conditions => "parent_id IS NULL AND status = #{Project::STATUS_ACTIVE}",
#                                  :order => 'name')
#    @project = Project.new(params[:project])
#    if request.get?
#      @project.identifier = Project.next_identifier if Setting.sequential_project_identifiers?
#      @project.trackers = Tracker.all
#      @project.is_public = Setting.default_projects_public?
#      @project.enabled_module_names = Redmine::AccessControl.available_project_modules
#    else
#      @project.enabled_module_names = params[:enabled_modules]
#      if @project.save
#        flash[:notice] = l(:notice_successful_create)
#        redirect_to :controller => 'admin', :action => 'projects'
#	  end		
#    end	
#  end
#	
#  # Show @project
#  def show
#    if params[:jump]
#      # try to redirect to the requested menu item
#      redirect_to_project_menu_item(@project, params[:jump]) && return
#    end
#    
#    @members_by_role = @project.members.find(:all, :include => [:user, :role], :order => 'position').group_by {|m| m.role}
#    @subprojects = @project.children.find(:all, :conditions => Project.visible_by(User.current))
#    @news = @project.news.find(:all, :limit => 5, :include => [ :author, :project ], :order => "#{News.table_name}.created_on DESC")
#    @trackers = @project.rolled_up_trackers
#    
#    cond = @project.project_condition(Setting.display_subprojects_issues?)
#    Issue.visible_by(User.current) do
#      @open_issues_by_tracker = Issue.count(:group => :tracker,
#                                            :include => [:project, :status, :tracker],
#                                            :conditions => ["(#{cond}) AND #{IssueStatus.table_name}.is_closed=?", false])
#      @total_issues_by_tracker = Issue.count(:group => :tracker,
#                                            :include => [:project, :status, :tracker],
#                                            :conditions => cond)
#    end
#    TimeEntry.visible_by(User.current) do
#      @total_hours = TimeEntry.sum(:hours, 
#                                   :include => :project,
#                                   :conditions => cond).to_f
#    end
#    @key = User.current.rss_key
#  end

    /**
     * Show action
     *
     * @return void
     * @todo 足さなくていいのか？
     */
    public function show()
    {
        $subprojects = $this->Project->findSubprojects($this->request->data['Project']['id']);
        $this->set('subprojects', $subprojects);

        $cond = $this->Project->project_condition($this->Setting->display_subprojects_issues, $this->request->data['Project']);

        foreach ($this->request->data['Tracker'] as $key => $tracker) {
            $cond_open = $cond_all = $cond;
            $cond_open['Status.is_closed'] = false;
            $cond_open['Issue.tracker_id'] = $tracker['id'];
            $open_issues_by_tracker = $this->Issue->find('count', array(
                'conditions' => $cond_open,
            ));

            $cond_all['Issue.tracker_id'] = $tracker['id'];
            $total_issues_by_tracker = $this->Issue->find('count', array(
                'conditions' => $cond_all,
            ));

            $tracker['open_issues_by_tracker'] = intval($open_issues_by_tracker);
            $tracker['total_issues_by_tracker'] = intval($total_issues_by_tracker);
            $this->request->data['Tracker'][$key] = $tracker;
        }
        $parent_project = $this->Project->findById($this->request->data['Project']['parent_id']);
        $this->set('parent_project', $parent_project);

        $members_by_role = $this->_get_members_by_role();
        $this->set('members_by_role', $members_by_role);

        $news = $this->News->find('all', array(
            'conditions' => array('project_id' => $this->id),
            'order' => 'News.created_on DESC',
            'limit' => 5,
        ));
        $this->set('news', $news);

        $this->TimeEntry->_customFieldAfterFindDisable = true;
        $total_hours = $this->TimeEntry->sum('hours',$cond);
        $this->set('total_hours', $total_hours);

        #    TimeEntry.visible_by(User.current) do
        #      @total_hours = TimeEntry.sum(:hours,
        #                                   :include => :project,
        #                                   :conditions => cond).to_f
        #    end
        #    @key = User.current.rss_key
        #    @news = @project.news.find(:all, :limit => 5, :include => [ :author, :project ], :order => "#{News.table_name}.created_on DESC")
        #    @members_by_role = @project.members.find(:all, :include => [:user, :role], :order => 'position').group_by {|m| m.role}

        #      @open_issues_by_tracker = Issue.count(:group => :tracker,
        #                                            :include => [:project, :status, :tracker],
        #                                            :conditions => ["(#{cond}) AND #{IssueStatus.table_name}.is_closed=?", false])
        #      @total_issues_by_tracker = Issue.count(:group => :tracker,
        #                                            :include => [:project, :status, :tracker],
        #                                            :conditions => cond)

        $this->set('rss_title', 'Atom');
        $this->set('rss_token', $this->Project->User->rss_key($this->current_user['id']));
    }

#  def settings
#    @root_projects = Project.find(:all,
#                                  :conditions => ["parent_id IS NULL AND status = #{Project::STATUS_ACTIVE} AND id <> ?", @project.id],
#                                  :order => 'name')
#    @issue_custom_fields = IssueCustomField.find(:all, :order => "#{CustomField.table_name}.position")
#    @issue_category ||= IssueCategory.new
#    @member ||= @project.members.new
#    @trackers = Tracker.all
#    @repository ||= @project.repository
#    @wiki ||= @project.wiki
#  end

    /**
     * Edit action
     *
     * @return void
     * @author Predominant
     */
    public function edit()
    {
        #    if request.post?
        #      @project.attributes = params[:project]
        #      if @project.save
        #        flash[:notice] = l(:notice_successful_update)
        #        redirect_to :action => 'settings', :id => @project
        #      else
        #        settings
        #        render :action => 'settings'
        #      end
        #    end

        $fields = array('name', 'description', 'homepage', 'is_public', 'parent_id');
        $this->request->data['Tracker']['Tracker'] = array_filter($this->request->data['Project']['Tracker']);
        if (
            isset($this->request->data['Project']['issue_custom_field_ids']) &&
            is_array($this->request->data['Project']['issue_custom_field_ids'])
        ) {
            $this->request->data['CustomField']['CustomField'] = array_filter($this->request->data['Project']['issue_custom_field_ids']);
        }

        $this->request->data['Project']['custom_field_values'] = $this->request->data['custom_field_values'];

        if ($this->Project->save($this->request->data, true, $fields)) {
            $this->Session->setFlash(__('Successful update.'), 'default', array('class' => 'flash notice'));
            $this->redirect(
                array(
                    'action' => 'settings',
                    'project_id' => $this->request->params['project_id']
                )
            );
        }
        $this->settings();
        $this->render('settings');
    }

    /**
     * Modules action
     *
     * @return void
     */
    public function modules()
    {
        if (!empty($this->request->data)) {
            $modules = array_filter($this->request->data['Project']['EnabledModule']);
            $data = array();
            foreach ($modules as $v) {
                $data[] = array(
                    'id' => null,
                    'project_id' => $this->id,
                    'name' => $v
                );
            }
            $this->Project->EnabledModule->deleteAll(array('project_id' => $this->id));
            $this->Project->EnabledModule->saveAll($data);
            $this->Session->setFlash(__('Successful update.'), 'default', array('class' => 'flash notice'));
        }
        $this->redirect(
            array(
                'action' => 'settings',
                '?' => 'tab=modules',
                'project_id' => $this->request->params['project_id']
            )
        );
    }

    /**
     * Archive action
     *
     * @return void
     */
    public function archive()
    {
        $this->Project->archive($this->_project['Project']['id']);
        $this->redirect(array('controller' => 'admin', 'action' => 'projects'));
    }

    /**
     * Unarchive action
     *
     * @return void
     */
    public function unarchive()
    {
        $this->Project->unarchive($this->_project['Project']['id']);
        $this->redirect(array('controller' => 'admin', 'action' => 'projects'));
    }

    /**
     * Destroy action
     *
     * @return void
     */
    public function destroy()
    {
        $subprojects = $this->Project->findSubprojects($this->_project['Project']['id']);
        $this->set('subprojects', $subprojects);
        if ($this->RequestHandler->isPost()) {
            if ($this->request->data['Project']['confirm'] == 1) {
                $this->Project->delete($this->request->data['Project']['id']);
                foreach ($subprojects as $row) {
                    $this->Project->delete($row['Project']['id']);
                }
                $this->Session->setFlash(__('Successful deletion.'), 'default', array('class' => 'flash flash_notice'));
                $this->redirect(array('controller' => 'admin', 'action' => 'projects'));
            } else {
                // Nothing
            }
        }
    }

#  # Delete @project
#  def destroy
#    @project_to_destroy = @project
#    if request.post? and params[:confirm]
#      @project_to_destroy.destroy
#      redirect_to :controller => 'admin', :action => 'projects'
#    end
#    # hide project in layout
#    @project = nil
#  end


    function add_issue_category()
    {
        if ($this->RequestHandler->isAjax()) {
            Configure::write('debug', 0);
            $data = $this->request->query['data'];
        } else {
            $data = $this->request->data;
        }
        $members = $this->Project->Member->find('all', array(
            'conditions' => array(
                'project_id' => $this->request->data['Project']['id'],
            ),
        ));
        $project_users = array(null => '');
        foreach ($members as $member) {
            $project_users[$member['User']['id']] = $this->User->name($member);
        }

        if ($this->RequestHandler->isPost()) {
            $data['IssueCategory']['project_id'] = $this->_project['Project']['id'];
            $this->IssueCategory->create();
            $this->IssueCategory->set($data);
            if ($this->IssueCategory->save(null, true, array('project_id', 'name', 'assigned_to_id'))) {
                if (!$this->RequestHandler->isAjax()) {
                    $this->Session->setFlash(__('Successful creation.'), 'default', array('class' => 'flash flash_notice'));
                    $this->redirect(
                        array(
                            'controller' => 'projects',
                            'action' => 'settings',
                            'project_id' => $this->request->data['Project']['project_id'],
                            '?' => 'tab=categories'
                        )
                    );
                } else {
                    $this->layout = 'ajax';
                    $issue_categories = $this->IssueCategory->find('list', array(
                        'conditions' => array('project_id' => $this->request->data['Project']['id']),
                        'order' => "IssueCategory.name",
                    ));
                    $this->set(compact('issue_categories'));

                    // Force Content-type
                    $this->response->type('text/javascript');
                    $this->response->send();

                    $this->render('add_issue_category_ajax');
                }
            }
        }
        $this->set('project_users', $project_users);
    }

#  # Add a new issue category to @project
#  def add_issue_category
#    @category = @project.issue_categories.build(params[:category])
#    if request.post? and @category.save
#  	  respond_to do |format|
#        format.html do
#          flash[:notice] = l(:notice_successful_create)
#          redirect_to :action => 'settings', :tab => 'categories', :id => @project
#        end
#        format.js do
#          # IE doesn't support the replace_html rjs method for select box options
#          render(:update) {|page| page.replace "issue_category_id",
#            content_tag('select', '<option></option>' + options_from_collection_for_select(@project.issue_categories, 'id', 'name', @category.id), :id => 'issue_category_id', :name => 'issue[category_id]')
#          }
#        end
#      end
#    end
#  end
#	
#  # Add a new version to @project
#  def add_version
#  	@version = @project.versions.build(params[:version])
#  	if request.post? and @version.save
#  	  flash[:notice] = l(:notice_successful_create)
#      redirect_to :action => 'settings', :tab => 'versions', :id => @project
#  	end
#  end
    function add_version()
    {
        if ($this->RequestHandler->isPost()) {
            $this->request->data['Version']['project_id'] = $this->_project['Project']['id'];
            if (empty($this->request->data['Version']['effective_date'])) {
                $this->request->data['Version']['effective_date'] = null;
            }
            if ($this->Version->save($this->request->data, true, array('project_id', 'name', 'description', 'wiki_page_title', 'effective_date'))) {
                $this->Session->setFlash(
                    __('Successful creation.'),
                    'default',
                    array('class' => 'flash notice')
                );
                $this->redirect(
                    array(
                        'controller' => 'projects',
                        'action' => 'settings',
                        'project_id' => $this->request->data['Project']['project_id'],
                        '?' => 'tab=versions'
                    )
                );
            }
        }
    }

#
#  def add_file
#    if request.post?
#      container = (params[:version_id].blank? ? @project : @project.versions.find_by_id(params[:version_id]))
#      attachments = attach_files(container, params[:attachments])
#      if !attachments.empty? && Setting.notified_events.include?('file_added')
#        Mailer.deliver_attachments_added(attachments)
#      end
#      redirect_to :controller => 'projects', :action => 'list_files', :id => @project
#      return
#    end
#    @versions = @project.versions.sort
#  end
    function add_file()
    {
        $versions = $this->Version->find('all', array(
            'conditions' => array(
                'project_id' => $this->request->data['Project']['id'],
            )
        ));
        $version_select = array(null => '');
        foreach ($versions as $version) {
            $version_select[$version['Version']['id']] = $version['Version']['name'];
        }
        $this->set('versions', $version_select);

        if ($this->RequestHandler->isPost()) {
            $version_id = $this->request->data['Attachment']['version_id'];
            // @FIXME magic number
            $container_type = 'Project';
            $container_id = $this->request->data['Project']['id'];
            if (!empty($version_id)) {
                $container_type = 'Version';
                $container_id = $version_id;
            }
            $this->request->data['Attachment']['container_type'] = $container_type;
            $this->request->data['Attachment']['container_id'] = $container_id;

            $file = $this->request->data['Attachment']['file'];
            $upload_dir = $this->Setting->file_upload_dir;
            $this->request->data['Attachment']['filename'] = $file['name'];

            $disk_filename = $this->Attachment->disk_filename($file['name']);
            $digest = $this->Attachment->disk_filename($file['tmp_name']);
            $content_type = $file['type'];

            $this->request->data['Attachment']['disk_filename'] = $disk_filename;
            $this->request->data['Attachment']['digest'] = $digest;
            $this->request->data['Attachment']['content_type'] = $content_type;
            $this->request->data['Attachment']['filesize'] = filesize($file['tmp_name']);
            $this->request->data['Attachment']['downloads'] = 0;
            $this->request->data['Attachment']['author_id'] = $this->current_user['id'];

            // @TODO
            if (move_uploaded_file($file['tmp_name'], $upload_dir . DS . $disk_filename)) {
                if ($this->Attachment->save($this->request->data)) {
                    $this->redirect(array('controller' => 'projects', 'action' => 'list_files', 'project_id' => $this->request->data['Project']['project_id']));
                }
            }
        }
    }

#
#  def list_files
#    sort_init 'filename', 'asc'
#    sort_update 'filename' => "#{Attachment.table_name}.filename",
#                'created_on' => "#{Attachment.table_name}.created_on",
#                'size' => "#{Attachment.table_name}.filesize",
#                'downloads' => "#{Attachment.table_name}.downloads"
#                
#    @containers = [ Project.find(@project.id, :include => :attachments, :order => sort_clause)]
#    @containers += @project.versions.find(:all, :include => :attachments, :order => sort_clause).sort.reverse
#    render :layout => !request.xhr?
#  end
    function list_files()
    {
        $containers = array();

        // @FIXME magic number
        $container = $this->request->data;
        $container['Attachment'] = $this->Attachment->find('all', array(
            'conditions' => array(
                'container_id' => $this->request->data['Project']['id'],
                'container_type' => 'Project',
            )
        ));
        $containers[] = $container;

        $versions = $this->Version->find('all', array(
            'conditions' => array(
                'project_id' => $this->request->data['Project']['id'],
            )
        ));
        foreach ($versions as $version) {
            $container = $version;
            $container['Attachment'] = $this->Attachment->find('all', array(
                'conditions' => array(
                    'container_id' => $version['Version']['id'],
                    'container_type' => 'Version',
                )
            ));
            $containers[] = $container;
        }

        $this->set('containers', $containers);

        $delete_allowed = true;
        $this->set('delete_allowed', $delete_allowed);
    }

#
#  # Show changelog for @project
#  def changelog
#    @trackers = @project.trackers.find(:all, :conditions => ["is_in_chlog=?", true], :order => 'position')
#    retrieve_selected_tracker_ids(@trackers)    
#    @versions = @project.versions.sort
#  end
    function changelog()
    {
        $trackers = $this->Tracker->find('all', array(
            'conditions' => array('is_in_chlog' => true),
            'order' => 'Tracker.position',
        ));
        $this->set('trackers', $trackers);

        $tracker_ids = $this->_retrieve_selected_tracker_ids($trackers);
        foreach ($this->request->data['Version'] as $key => $version) {
            $issues = $this->Issue->find('all', array(
                'conditions' => array(
                    'Status.is_closed' => true,
                    'Issue.tracker_id' => $tracker_ids,
                    'Issue.fixed_version_id' => $version['id'],
                ),
                'order' => 'Tracker.position',
            ));
            $this->request->data['Version'][$key]['Issue'] = $issues;
        }

        $this->set('versions', $this->request->data['Version']);
    }

#
#  def roadmap
#    @trackers = @project.trackers.find(:all, :conditions => ["is_in_roadmap=?", true])
#    retrieve_selected_tracker_ids(@trackers)
#    @versions = @project.versions.sort
#    @versions = @versions.select {|v| !v.completed? } unless params[:completed]
#  end
    function roadmap()
    {
        usort($this->request->data['Version'], array($this->Version, 'sort'));
        $trackers = $this->Tracker->find('all', array(
            'conditions' => array('is_in_roadmap' => true)
        ));
        $this->set('trackers', $trackers);
        if (isset($this->request->data['Version'])) {
            foreach ($this->request->data['Version'] as $key => $version) {
                $this->request->data['Version'][$key]['Issue'] = $this->Issue->find(
                    'all',
                    array('conditions' =>
                    array(
                        'fixed_version_id' => $version['id']
                    )
                    )
                );
            }
        }
        // $issues = $this->Version->FixedIssue->find('all',
        $this->set('issues', array());

        /*
        <% issues = version.fixed_issues.find(:all,
                                              :include => [:status, :tracker],
                                              :conditions => ["tracker_id in (#{@selected_tracker_ids.join(',')})"],
                                              :order => "#{Tracker.table_name}.position, #{Issue.table_name}.id") unless @selected_tracker_ids.empty?
           issues ||= []
        %>
         */

    }

    function activity()
    {
        if ($this->RequestHandler->isAjax() || $this->_get_param('format')) {
            Configure::write('debug', 0);
        }
        $days = $this->Setting->activity_days_default;

        if ($this->_get_param('from')) {
            $date_to = strtotime('+1 day', strtotime($this->_get_param('from')));
        }
        if (!isset($date_to)) {
            $date_to = strtotime('+1 day');
        }
        $date_from = strtotime("-{$days} day", $date_to);
        $date_from = strtotime(date('Y-m-d 0:0:0', $date_from));
        $date_to = strtotime(date('Y-m-d 0:0:0', $date_to)) - 1;

        $this->set('date_from', $date_from);
        $this->set('date_to', $date_to);
        $this->set('days', $days);

        $author = empty($this->_get_param['user_id']) ? null : $this->User->find('first', array(
            'conditions' => array('id' => $this->parmas['user_id'], 'status' => USER_STATUS_ACTIVE)
        ));
        $this->set('author', $author);

        $with_subprojects = ($this->_get_param('with_subprojects') != null) ? $this->Setting->display_subprojects_issues : ($this->_get_param('with_subprojects') == '1');
        $condition = array(
            //'project' => $this->_project,
            'with_subprojects' => $with_subprojects,
            'author' => $author['User'],
        );
        if (isset($this->_project)) {
            $condition['project'] = $this->_project;
        }
        $this->Fetcher->fetch($this->current_user, $condition);
        $scope = $this->Fetcher->scope_select('_callback_activity_scope_select');
        if (empty($scope)) {
            $this->Fetcher->set_scope(empty($author) ? 'default' : 'all');
        }
        $events = $this->Fetcher->events($date_from, $date_to);
        $events_by_day = $this->Event->group_by($events, 'event_date');
        krsort($events_by_day);
        foreach ($events_by_day as $day => $events_by) {
            usort($events_by, array($this->Fetcher, 'cmp_event_datetime'));
            $events_by_day[$day] = $events_by;
        }
        $this->set('events_by_day', $events_by_day);

        $this->set('activity_event_types', $this->Fetcher->event_types());
        $this->set('activity_scope', $this->Fetcher->scope);
        if (isset($this->_project)) {
            $this->set('active_children', $this->Project->active_children($this->_project['Project']['id']));
        }
        $this->set('with_subprojects', $with_subprojects);
        $this->set('param_user_id', $this->_get_param('user_id'));
        $this->set('rss_token', $this->Project->User->rss_key($this->current_user['id']));

        $title = __('Activity');
        if ($author) {
            $title = $this->User->to_string($author);
        } elseif (count($this->Fetcher->scope) == 1) {
            $title = __(Inflector::classify($this->Fetcher->scope[0]));
        }
        if (empty($this->_project)) {
            $title = $this->Setting->app_title . ": $title";
        } else {
            $title = $this->Project->to_string($this->_project) . ": $title";
        }
        $this->set('rss_title', $title);

        switch ($this->_get_param('format')) {
            case 'atom' :
                usort($events, array($this->Fetcher, 'cmp_event_datetime'));
                $this->render_feed($this->Event, $events, array('title' => $title, 'sort' => false));
                break;
            default :
                if ($this->RequestHandler->isAjax()) {
                    $this->layout = 'ajax';
                }
        }
    }

    function _callback_activity_scope_select($scope)
    {
        return $this->_get_param("show_{$scope}") == 1;
    }

#
#private
#  # Find project of id params[:id]
#  # if not found, redirect to project list
#  # Used as a before_filter
#  def find_project
#    @project = Project.find(params[:id])
#  rescue ActiveRecord::RecordNotFound
#    render_404
#  end
    function find_project()
    {
        $data = null;
        if (!empty($this->request->data)) {
            $data = $this->request->data;
            $this->request->data = null;
        }
        if (!empty($this->request->params['project_id'])) {
            $this->request->data = $this->Project->findByIdentifier($this->request->params['project_id']);
            if (!$this->request->data) {
                $this->request->data = $this->Project->findById($this->request->params['project_id']);
            }
            $this->id = $this->request->data['Project']['id'];
        } else if (!empty($this->request->params['id'])) {
            $this->id = $this->request->params['id'];
            $this->request->data = $this->Project->read();
        } else if (isset($this->request->data['Project'])) {
            if (isset($this->request->data['Project']['id'])) {
                $this->id = $this->request->data['Project']['id'];
                $this->request->data = $this->Project->read();
            }
        }

        if (empty($this->request->data)) {
            throw new NotFoundException();
            return;
        }
        if (!$this->_isVisible($this->id)) {
            throw new NotFoundException();
            return;
        }
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $key2 => $value2) {
                        $this->request->data[$key][$key2] = $value2;
                    }
                } else {
                    $this->request->data[$key] = $value;
                }
            }
        }
        if (isset($this->request->data['CustomValue']) && is_array($this->request->data['CustomValue'])) {
            foreach ($this->request->data['CustomValue'] as $row) {
                $this->request->data['custom_field_values'][$row['custom_field_id']] = $row['value'];
            }
        }

    }

#
#  def find_optional_project
#    return true unless params[:id]
#    @project = Project.find(params[:id])
#    authorize
#  rescue ActiveRecord::RecordNotFound
#    render_404
#  end
#
#  def retrieve_selected_tracker_ids(selectable_trackers)
#    if ids = params[:tracker_ids]
#      @selected_tracker_ids = (ids.is_a? Array) ? ids.collect { |id| id.to_i.to_s } : ids.split('/').collect { |id| id.to_i.to_s }
#    else
#      @selected_tracker_ids = selectable_trackers.collect {|t| t.id.to_s }
#    end
#  end
#end

    function settings()
    {
        $trackers = $this->Tracker->find('all');
        $this->set('trackers', $trackers);

        $issue_custom_fields = $this->IssueCustomField->find('all', array('order' => $this->IssueCustomField->name . ".position"));
        $this->set('issue_custom_fields', $issue_custom_fields);

        $root_project_inputs = $this->Project->find('all', array('conditions' => array($this->Project->name . '.parent_id' => null, $this->Project->name . '.status' => PROJECT_STATUS_ACTIVE), 'order' => $this->Project->name . '.name'));
        $root_projects = array(null => '');
        foreach ($root_project_inputs as $project) {
            // Check it's not the project itself
            if ($project['Project']['id'] != $this->_project['Project']['id'])
                $root_projects[$project['Project']['id']] = $project['Project']['name'];
        }
        $this->set('root_projects', $root_projects);

        $available_project_modules = $this->Permission->available_project_modules();
        $this->set('available_custom_fields', $this->Project->available_custom_fields());
        $this->set('available_project_modules', $available_project_modules);

        // for members tab start
        $members = $this->Project->Member->find('all', array(
                'conditions' => array(
                    'project_id' => $this->_project['Project']['id']
                ),
                'order' => 'Role.position')
        );
        $this->set('members', $members);

        $roles = $this->Project->Member->Role->find_all_givable();
        $this->set('roles_data', $roles);

        $users = $this->Project->Member->User->find('all', array(
            'conditions' => array(
                'status' => USER_STATUS_ACTIVE
            ),
            'recursive' => -1
        ));
        $this->set('users_data', $users);
        // for members tab end

        // for issue categories tab start
        $issue_categories = $this->IssueCategory->find('all', array(
            'conditions' => array(
                'project_id' => $this->_project['Project']['id']
            )
        ));
        $this->set('issue_categories_data', $issue_categories);
        // for issue categories tab end

        $versions = $this->Version->find('all', array(
            'conditions' => array(
                'project_id' => $this->_project['Project']['id']
            )
        ));
        $this->set('versions_data', $versions);

        $menuContainer = ClassRegistry::getObject('MenuContainer');
        $tabs = $menuContainer->getProjectSettingMenu();
        $selected_tab = $tabs[0]['name'];
        if (isset($this->params['url']['tab'])) {
            $selected_tab = $this->params['url']['tab'];
        }
        $this->set('selected_tab', $selected_tab);
        $this->set('tabs', $tabs);
    }

    function list_members()
    {
        $this->set('members_by_role', $this->_get_members_by_role());
    }

    function _get_members_by_role()
    {
        $members = $this->Project->Member->find('all', array(
            'order' => 'Role.position',
            'conditions' => array(
                'project_id' => $this->id,
                'User.status' => USER_STATUS_ACTIVE
            ),
        ));
        $members_by_role = array();
        foreach ($members as $member) {
            if (!isset($members_by_role[$member['Role']['name']])) {
                $members_by_role[$member['Role']['name']] = array();
            }
            $members_by_role[$member['Role']['name']][] = $member;
        }
        ksort($members_by_role);

        return $members_by_role;
    }

    function _retrieve_selected_tracker_ids($selectable_trackers)
    {
        if (isset($this->parmas['tracker_ids'])) {
            $ids = $this->parmas['tracker_ids'];
            if (is_array($ids)) {
            } else {
                $ids = explode('/', $ids);
            }
        } else {
            $ids = array();
            foreach ($selectable_trackers as $tracker) {
                $ids[] = $tracker['Tracker']['id'];
            }
        }

        return $ids;
    }
#  def retrieve_selected_tracker_ids(selectable_trackers)
#    if ids = params[:tracker_ids]
#      @selected_tracker_ids = (ids.is_a? Array) ? ids.collect { |id| id.to_i.to_s } : ids.split('/').collect { |id| id.to_i.to_s }
#    else
#      @selected_tracker_ids = selectable_trackers.collect {|t| t.id.to_s }
#    end
#  end
}
