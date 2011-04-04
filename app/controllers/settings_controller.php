<?php
class SettingsController extends AppController
{
  var $uses = array();
#  before_filter :require_admin
#
  function index()
  {
	$this->edit();
    $this->render('edit');
  }

  function edit()
  {
	$this->_prepareSettingTabs();
	$this->_prepareThemes();
	$this->_prepareWikiformatting();
	$this->_prepareColumns();
  	
#    @notifiables = %w(issue_added issue_updated news_added document_added file_added message_posted)
#    if request.post? && params[:settings] && params[:settings].is_a?(Hash)
#      settings = (params[:settings] || {}).dup.symbolize_keys
#      settings.each do |name, value|
#        # remove blank values in array settings
#        value.delete_if {|v| v.blank? } if value.is_a?(Array)
#        Setting[name] = value
#      end
    if (!empty($this->data)) {
      foreach ($this->data['Setting'] as $k => $v) {
      	$this->Setting->store($k,$v);
      }
      $this->Session->setFlash(__('Successful update.',true),'default',aa('class','flash notice'));
      $tab = 'general';
      if ( isset($this->params['url']['tab'])) $tab = $this->params['url']['tab'];
      $this->redirect(aa('action','edit','?','tab='.$tab));
      return;
    }
      #      redirect_to :action => 'edit', :tab => params[:tab]
#      return
#    end
#    @options = {}
#    @options[:user_format] = User::USER_FORMATS.keys.collect {|f| [User.current.name(f), f.to_s] }
#    @deliveries = ActionMailer::Base.perform_deliveries
#
#    @guessed_host_and_path = request.host_with_port.dup
#    @guessed_host_and_path << ('/'+ request.relative_url_root.gsub(%r{^\/}, '')) unless request.relative_url_root.blank?
  }
#
#  def plugin
#    @plugin = Redmine::Plugin.find(params[:id])
#    if request.post?
#      Setting["plugin_#{@plugin.id}"] = params[:settings]
#      flash[:notice] = l(:notice_successful_update)
#      redirect_to :action => 'plugin', :id => @plugin.id
#    end
#    @partial = @plugin.settings[:partial]
#    @settings = Setting["plugin_#{@plugin.id}"]
#  rescue Redmine::PluginNotFound
#    render_404
#  
  function _prepareSettingTabs()
  {
    $tabs = array(
      aa('name', 'general', 'partial', 'settings/general', 'label', __('General',true)),
      aa('name', 'authentication', 'partial', 'settings/authentication', 'label', __('Authentication',true)),
      aa('name', 'projects', 'partial', 'settings/projects', 'label', __('Projects',true)),
      aa('name', 'issues', 'partial', 'settings/issues', 'label', __('Issue tracking',true)),
      aa('name', 'notifications', 'partial', 'settings/notifications', 'label', __('Email notifications',true)),
      //aa('name', 'mail_handler', 'partial', 'settings/mail_handler', 'label', __('Incoming emails',true)),
      //aa('name', 'repositories', 'partial', 'settings/repositories', 'label', __('Repositories',true))
    );
    $this->set('settings_tabs',$tabs);
    $selected_tab = $tabs[0]['name'];
    if (isset($this->params['url']['tab'])) {
      $selected_tab = $this->params['url']['tab'];
    }
    $this->set('selected_tab',$selected_tab);
  }
  function _prepareThemes()
  {
    $theme_list = array_map('basename', glob(APP . DS . 'webroot/themed' . DS . '*'));
    $themes = array_combine($theme_list, $theme_list);
    $this->set('themes',$themes);
  }
  function _prepareWikiformatting()
  {
  	$text_formattings = a(
  	  'Textile',
  	  'Pukiwiki'
  	);
  	$this->set('text_formattings',$text_formattings);
  }
  function _prepareColumns()
  {
    App::import('model','Query');
    $this->Query = new Query();
    $available_columns = $this->Query->available_columns();
    $this->set('available_columns',$available_columns);
  }
}

