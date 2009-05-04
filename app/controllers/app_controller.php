<?php
#require 'uri'
#require 'cgi'

App::import('Core', 'l10n');

class AppController extends Controller {

    var $layout = 'base';
    var $helpers = array('Html', 'Form', 'Javascript', 'Candy');
    var $components = array('Cookie','MenuManager');
    var $uses = array('User','Setting','Project');
    var $current_user; // alternate User.current
    var $per_page;
    var $view = 'Theme';
    var $theme = '';
    var $pure_params = array();
    /**
     * beforeFilter
     *
     * @todo set_localzation
     */
    function beforeFilter()
    {
        $this->_setUrlParam();
        $this->user_setup();
        $this->check_if_login_required();
        $this->setSettings();
        $this->_findProject();
        //$this->set_localzation();
    }
    function _setUrlParam()
    {
      $url_param = $this->params;
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
      $this->params['url_param'] = $url_param;
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
     * user_setup
     *
     * @todo Setting.check_cache
     */
    function user_setup()
    {
#    # Check the settings cache for each request
#    Setting.check_cache
        
        // Find the current user
        $this->current_user = $this->find_current_user();
        $this->set('currentuser', $this->current_user);
    }

    /**
     * find_current_user
     *
     * Returns the current user or nil if no user is logged in
     *
     * @todo Setting.autologin
     * @todo auto_login
     * @todo rss key authentication
     */
    function _find_current_user() {
      if ($this->Session->read('user_id')) {
        // existing session
        return $this->User->find_by_id_logged($this->Session->read('user_id'));
#      (User.active.find(session[:user_id]) rescue nil)
      } else if ($this->Cookie->read('autologin')) {
#    elsif cookies[:autologin] && Setting.autologin?
#      # auto-login feature
#      User.find_by_autologin_key(cookies[:autologin])
#    elsif params[:key] && accept_key_auth_actions.include?(params[:action])
#      # RSS key authentication
#      User.find_by_rss_key(params[:key])
      } elseif (!empty($this->params['named']['key'])) {
        // from rss reader
        $user = $this->User->find_by_rss_key($this->params['named']['key']);
        if(!empty($user)) {
          $user = $this->User->find_by_id_logged($user['id']);
        }
        if(empty($user)) {
          $this->cakeError('error404');
        }
      }
      return null;
    }
    function find_current_user() {
      return $this->_find_current_user();
    }

    /**
     * check_if_login_required
     *
     * check if login is globally required to access the application
     *
     * @todo implement Setting.login_required
     * @todo logged?
     */
    function check_if_login_required()
    {
        // no check needed if user is already logged in
        if ($this->current_user['logged']) {
            return true;
        }

        if ($this->Setting->login_required) {
            $this->require_login();
        }
    }

#  def set_localization
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
#  end
#  
    /**
     * require_login
     *
     * @todo set back_url
     */
    function require_login()
    {
        if (!$this->current_user) {
            $this->redirect('/account/login');
#      redirect_to :controller => "account", :action => "login", :back_url => url_for(params)
            return false;
        }

        return true;
    }
    
    /**
     * require_admin
     *
     */
    function require_admin()
    {
        if (!$this->require_login()) {
            return false;
        }

        if ($this->current_user['admin'] != 1) {
            $this->redirect(null, 403);
            return false;
        }

        return true;
    }
#  
#  def deny_access
#    User.current.logged? ? render_403 : require_login
#  end
#
#  # Authorize the user for the requested action
#  def authorize(ctrl = params[:controller], action = params[:action])
#    allowed = User.current.allowed_to?({:controller => ctrl, :action => action}, @project)
#    allowed ? true : deny_access
#  end
#  
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
  function redirect_back_or_default($default_url) {
    if(!empty($this->data['back_url'])) {
      $back_url = urldecode($this->data['back_url']);
      $uri = parse_url($back_url);
      # do not redirect user to another host or to the login or register page
      # TODO relative
      if (($uri['host'] == env('HTTP_HOST')) && !preg_match('/(login|account\/register)$/', $uri['path'])) {
        $this->redirect($back_url);
      }
    }
    $this->redirect($default_url);
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
#
#  # Returns the number of objects that should be displayed
#  # on the paginated list
  function _per_page_option()
  {
    if (isset($this->params['url']['per_page']) && in_array($this->params['url']['per_page'], $this->Setting->per_page_options)) {
      $this->per_page = (int)$this->params['url']['per_page'];
      $this->Session->write('per_page', $this->per_page);
    } else if (strlen($this->Session->read('per_page'))) {
      $this->per_page = $this->Session->read('per_page');
    } else {
      $this->per_page = $this->Setting->per_page_options[0];
    }
    return $this->per_page;
  }
#  def per_page_option
#    per_page = nil
#    if params[:per_page] && Setting.per_page_options_array.include?(params[:per_page].to_s.to_i)
#      per_page = params[:per_page].to_s.to_i
#      session[:per_page] = per_page
#    elsif session[:per_page]
#      per_page = session[:per_page]
#    else
#      per_page = Setting.per_page_options_array.first || 25
#    end
#    per_page
#  end
#
#  # qvalues http header parser
#  # code taken from webrick
#  def parse_qvalues(value)
#    tmp = []
#    if value
#      parts = value.split(/,\s*/)
#      parts.each {|part|
#        if m = %r{^([^\s,]+?)(?:;\s*q=(\d+(?:\.\d+)?))?$}.match(part)
#          val = m[1]
#          q = (m[2] or 1).to_f
#          tmp.push([val, q])
#        end
#      }
#      tmp = tmp.sort_by{|val, q| -q}
#      tmp.collect!{|val, q| val}
#    end
#    return tmp
#  end
#  
#  # Returns a string that can be used as filename value in Content-Disposition header
#  def filename_for_content_disposition(name)
#    request.env['HTTP_USER_AGENT'] =~ %r{MSIE} ? ERB::Util.url_encode(name) : name
#  end
  function setSettings()
  {
  	$this->theme = strtolower($this->Setting->ui_theme);
  	if (!empty($this->Setting->default_language)) {
  	  $this->L10n = new L10n();
  	  $this->L10n->get($this->Setting->default_language);
  	}
  	$this->set('Settings',$this->Setting);
  }
  
  function _findProject()
  {
    if ( isset($this->params['project_id']) ) {
        if ($this->_project = $this->Project->find('first', array(
	      'conditions' => array(
	        'Project.identifier' => $this->params['project_id'],
	      ),
	    ))) {
	      $this->set(array('main_project'=> $this->_project));
	      $this->set('main_project', $this->_project);
	    } else {
	      $this->cakeError('error404');
	    }
    }
  }
  

}
