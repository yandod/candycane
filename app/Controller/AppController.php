<?php

App::uses('L10n', 'I18n');
App::import('View', 'CandyView');
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * @package candycane
 */
class AppController extends Controller
{
    public $layout = 'base';

    public $helpers = array('Html', 'Form', 'Candy', 'Session');

    public $components = array(
        'Session',
        'Cookie',
        'MenuManager',
        //'DebugKit.Toolbar'
    );

    public $uses = array('User', 'Setting', 'Project');

    public $current_user; // alternate User.current

    public $per_page;

    public $viewClass = 'Candy';

    public $theme = '';

    public $pure_params = array();

    public $authorize = false;

    /**
     * beforeFilter
     *
     * @todo set_localzation
     */
    function beforeFilter()
    {
        $this->_setUrlParam();
        $this->user_setup();
        $this->setSettings();
        $this->set_localization();
        $this->check_if_login_required();
        $this->_findProject();
        $this->_authorize();
    }

    /**
     * Set URL Parameters
     *
     * @return void
     */
    function _setUrlParam()
    {
        $url_param = $this->request->params;
        foreach (array(
                     'data',
                     'url',
                     'form',
                     'isAjax',
                     'plugin',
                     'models',
                     'pass',
                     'named',
                 ) as $key) {
            unset($url_param[$key]);
        }
        $this->request->params['url_param'] = $url_param;
    }

#  filter_parameter_logging :password
#
#  include Redmine::MenuManager::MenuController
#  helper Redmine::MenuManager::MenuHelper
#
#  REDMINE_SUPPORTED_SCM.each do |scm|
#    require_dependency "repository/#{scm.underscore}"
#  end
#
#  def current_role
#    @current_role ||= User.current.role_for_project(@project)
#  end
#

    /**
     * User Setup
     *
     * @return void
     * @todo Setting.check_cache
     */
    public function user_setup()
    {
        #    # Check the settings cache for each request
        #    Setting.check_cache

        // Find the current user
        $this->current_user = $this->find_current_user();
        $this->set('currentuser', $this->current_user);
    }

    /**
     * Find the current logged in user
     *
     * Returns the current user or nil if no user is logged in
     *
     * @return void
     * @todo Setting.autologin
     * @todo auto_login
     * @todo rss key authentication
     */
    protected function _find_current_user()
    {
        if (!$this->is_api_request()) {
            if ($this->Session->read('user_id')) {
                // existing session
                return $this->User->find_by_id_logged($this->Session->read('user_id'));
                # (User.active.find(session[:user_id]) rescue nil)
            } else if ($this->Cookie->read('autologin')) {
                # elsif cookies[:autologin] && Setting.autologin?
                #      # auto-login feature
                #      User.find_by_autologin_key(cookies[:autologin])
            } elseif (!empty($this->request->params['url']['key'])) {
                // from rss reader
                $user = $this->User->find_by_rss_key($this->request->params['url']['key']);
                if (!empty($user)) {
                    $user = $this->User->find_by_id_logged($user['id']);
                }
                if (empty($user)) {
                    throw new NotFoundException();
                }
                return $user;
            }
        } elseif (isset($this->request->query['key'])) {
            $user = $this->User->find_by_api_key($this->request->query['key']);
            if (empty($user)) {
                throw new NotFoundException();
            }
            return $user;
        }

        $user = $this->User->anonymous();
        $user['User']['logged'] = false;
        $user['User']['name'] = $user['User']['login'];
        $user['User']['memberships'] = array();
        return $user['User'];
    }

    /**
     * Find current user
     *
     * @return mixed Current User
     */
    public function find_current_user()
    {
        return $this->_find_current_user();
    }

    protected function is_api_request()
    {
        if (!isset($this->request->params['ext'])) {
            return false;
        }

        $ext = $this->request->params['ext'];
        return in_array($ext, array('xml', 'json'));
    }

    /**
     * check_if_login_required
     *
     * check if login is globally required to access the application
     *
     * @todo implement Setting.login_required
     * @todo logged?
     */
    /**
     * Check if login is required
     *
     * check if login is globally required to access the application
     *
     * @return mixed
     */
    public function check_if_login_required()
    {
        // no check needed if user is already logged in
        if ($this->current_user['logged']) {
            return true;
        }

        if ($this->Setting->login_required) {
            $this->require_login();
        }
    }

    /**
     * Set Localization
     *
     * @return void
     */
    public function set_localization()
    {
        #    User.current.language = nil unless User.current.logged?
        #    lang = begin
        #      if !User.current.language.blank? && GLoc.valid_language?(User.current.language)
        #        User.current.language
        #      elsif request.env['HTTP_ACCEPT_LANGUAGE']
        #        accept_lang = parse_qvalues(request.env['HTTP_ACCEPT_LANGUAGE']).first.downcase
        #        if !accept_lang.blank? && (GLoc.valid_language?(accept_lang) || GLoc.valid_language?(accept_lang = accept_lang.split('-').first))
        #          User.current.language = accept_lang
        #        end
        #      end
        #    rescue
        #      nil
        #    end || Setting.default_language
        #    set_language_if_valid(lang)
        $lang = null;
        if (!empty($this->current_user['language'])) {
            $lang = $this->current_user['language'];
        } elseif (!empty($this->Setting->default_language)) {
            $lang = $this->Setting->default_language;
        }
        $this->L10n = new L10n();
        $this->L10n->get($lang);
        Configure::write('Config.language', $lang);
    }

    /**
     * Require Login
     *
     * @return boolean
     * @todo set back_url
     */
    public function require_login()
    {
        if (!$this->current_user || !$this->current_user['logged']) {
            $this->redirect(
                '/account/login?back_url=' .
                    urlencode(Router::url($this->request->here(false), true))
            );
            #      redirect_to :controller => "account", :action => "login", :back_url => url_for(params)
            return false;
        }

        return true;
    }

    /**
     * Require Admin
     *
     * @return boolean
     */
    public function require_admin()
    {
        if (!$this->require_login()) {
            return false;
        }

        if ($this->current_user['admin'] != 1) {
            $this->redirect('/', 403);
            return false;
        }

        return true;
    }

    /**
     * Deny Access
     *
     * @return mixed
     */
    public function deny_access()
    {
        if ($this->current_user['logged']) {
            throw new ForbiddenException();
        } else {
            return $this->require_login();
        }
    }

    /**
     * Authorize the user for the requested action
     *
     * @param string $ctrl Controller
     * @param string $action Action
     * @return boolean Allowed access
     */
    protected function _authorize($ctrl = false, $action = false)
    {
        if (!empty($this->request->params['requested'])) {
            return true;
        }
        if ($this->authorize === false) {
            return true;
        }
        if ($ctrl === false) {
            $ctrl = $this->request->params['controller'];
        }
        if ($action === false) {
            $action = $this->request->params['action'];
        }
        if ($action == 'add') {
            $action = 'new';
        }
        $authorize = array_merge(array('only' => array(), 'except' => array()), $this->authorize);
        extract($authorize);

        if ((!empty($only) && !in_array($action, $only)) || (!empty($except) && in_array($action, $except))) {
            return true;
        }
        $allowed = $this->User->is_allowed_to($this->current_user, array('controller' => $ctrl, 'action' => $action), $this->_project);
        return $allowed ? true : $this->deny_access();
    }

#  # make sure that the user is a member of the project (or admin) if project is private
#  # used as a before_filter for actions that do not require any particular permission on the project
#  def check_project_privacy
#    if @project && @project.active?
#      if @project.is_public? || User.current.member_of?(@project) || User.current.admin?
#        true
#      else
#        User.current.logged? ? render_403 : require_login
#      end
#    else
#      @project = nil
#      render_404
#      false
#    end
#  end
#

    /**
     * redirect_back_or_default
     *
     * @param string $default_url
     * @return void
     */
    public function redirect_back_or_default($default_url)
    {
        if (!empty($this->request->data['back_url'])) {
            $back_url = urldecode($this->request->data['back_url']);
            $uri = parse_url($back_url);
            # do not redirect user to another host or to the login or register page
            # TODO relative
            if (($uri['host'] == env('HTTP_HOST')) && !preg_match('/(login|account\/register)$/', $uri['path'])) {
                $this->redirect($back_url);
            }
        }
        $this->redirect($default_url);
    }

    /**
     * Render Feed
     *
     * @param string $event_model
     * @param array $items Items
     * @param array $options Options
     * @return void
     */
    public function render_feed($event_model, $items, $options = array())
    {
        if (!($options['sort'] === false)) {
            usort($items, array($event_model, 'cmp_event_datetime'));
        }
        unset($options['sort']);
        $items = array_reverse($items);
        $items = array_slice($items, 0, $this->Setting->feeds_limit);
        $atom_title = !empty($options['title']) ? $options['title'] : $this->Setting->app_title;
        $this->set(compact('atom_title', 'items'));
        $this->set('EventModel', $event_model);
        $this->helpers = array('Candy', 'Xml', 'Time');
        $this->layout = 'rss/atom';
        $this->render("/common/feed.atom");
    }

#  def render_403
#    @project = nil
#    render :template => "common/403", :layout => !request.xhr?, :status => 403
#    return false
#  end
#
#  def render_404
#    render :template => "common/404", :layout => !request.xhr?, :status => 404
#    return false
#  end
#
#  def render_error(msg)
#    flash.now[:error] = msg
#    render :nothing => true, :layout => !request.xhr?, :status => 500
#  end
#
#  def render_feed(items, options={})
#    @items = items || []
#    @items.sort! {|x,y| y.event_datetime <=> x.event_datetime }
#    @items = @items.slice(0, Setting.feeds_limit.to_i)
#    @title = options[:title] || Setting.app_title
#    render :template => "common/feed.atom.rxml", :layout => false, :content_type => 'application/atom+xml'
#  end
#
#  def self.accept_key_auth(*actions)
#    actions = actions.flatten.map(&:to_s)
#    write_inheritable_attribute('accept_key_auth_actions', actions)
#  end
#
#  def accept_key_auth_actions
#    self.class.read_inheritable_attribute('accept_key_auth_actions') || []
#  end
#
#  # TODO: move to model
#  def attach_files(obj, attachments)
#    attached = []
#    unsaved = []
#    if attachments && attachments.is_a?(Hash)
#      attachments.each_value do |attachment|
#        file = attachment['file']
#        next unless file && file.size > 0
#        a = Attachment.create(:container => obj,
#                              :file => file,
#                              :description => attachment['description'].to_s.strip,
#                              :author => User.current)
#        a.new_record? ? (unsaved << a) : (attached << a)
#      end
#      if unsaved.any?
#        flash[:warning] = l(:warning_attachments_not_saved, unsaved.size)
#      end
#    end
#    attached
#  end

    /**
     * The presence of the parameter is checked in the following order, and the value matched first is returned.
     *
     * - $this->request->params[$name]
     * - $this->request->params['named'][$name]
     * - $this->request->params['url'][$name]
     * - $this->request->data[$this->{$this->modelClass}->name][$name]
     *
     * @param string $name
     * @return null if $name is not found.
     */
    protected function _get_param($name)
    {
        if (array_key_exists($name, $this->request->params)) {
            $value = $this->request->params[$name];
        } elseif (
            array_key_exists('named', $this->request->params) &&
            array_key_exists($name, $this->request->params['named'])
        ) {
            $value = $this->request->params['named'][$name];
        } elseif (
            array_key_exists('query', $this->request) &&
            array_key_exists($name, $this->request->query)
        ) {
            $value = $this->request->query[$name];
        } elseif (
            is_array($this->request->data) &&
            array_key_exists($this->{$this->modelClass}->name, $this->request->data) &&
            array_key_exists($name, $this->request->data[$this->{$this->modelClass}->name])
        ) {
            $value = $this->request->data[$this->{$this->modelClass}->name][$name];
        } elseif (
            array_key_exists('data', $this->request) &&
            array_key_exists($name, $this->request->data)
        ) {
            $value = $this->request->data[$name];
        } else {
            $value = null;
        }
        return $value;
    }

    /**
     * Returns the number of objects that should be displayed
     * on the paginated list
     *
     * @return int Number of objects to be displayed
     */
    protected function _per_page_option()
    {
        if (isset($this->request->query['per_page']) && in_array($this->request->query['per_page'], $this->Setting->per_page_options)) {
            $this->per_page = (int)$this->request->query['per_page'];
            $this->Session->write('per_page', $this->per_page);
        } else if (strlen($this->Session->read('per_page'))) {
            $this->per_page = $this->Session->read('per_page');
        } else {
            $this->per_page = $this->Setting->per_page_options[0];
        }
        return $this->per_page;
    }

    /**
     * Set Settings
     *
     * @return void
     */
    public function setSettings()
    {
        $this->theme = strtolower($this->Setting->ui_theme);
        $this->set('Settings', $this->Setting);
    }

    /**
     * Find Projects
     *
     * @return void
     */
    protected function _findProject()
    {
        $project_id = $this->_get_param('project_id');
        if (!empty($project_id)) {
            if ($this->_project = $this->Project->findMainProject($project_id)) {
                $this->set(array('main_project' => $this->_project));
                $this->set('main_project', $this->_project);
            } else {
                throw new NotFoundException();
            }
            if (!$this->_isVisible($this->_project['Project']['id'])) {
                $this->require_login();
            }
        }
    }

    /**
     * Is Visible
     *
     * @param string $project_id Project ID
     * @return boolean Visible
     */
    protected function _isVisible($project_id)
    {
        $cond = $this->Project->get_visible_by_condition($this->current_user);
        $cond['Project.id'] = $project_id;
        if (in_array($this->request->action, array('unarchive', 'destroy')) && $this->name == 'Projects') {
            $cond['Project.status'] = Project::STATUS_ARCHIVED;
        }

        $visible = $this->Project->find('first', array('conditions' => $cond));
        if ($visible == false) {
            return false;
        }
        return true;
    }

    public function referer($default = null, $local = false)
    {
        return env('HTTP_REFERER');
    }
}
