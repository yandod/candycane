<?php
class SettingsController extends AppController
{
  var $uses = array();
#  before_filter :require_admin
#
  function index()
  {
#  def index
#    edit
#    render :action => 'edit'
#  end
	$this->_prepateSettingTabs();
	$this->_prepateThemes();
	$this->_prepareWikiformatting();
    $this->render('edit');
  }

  function edit()
  {
	$this->_prepateSettingTabs();
	$this->_prepateThemes();
	$this->_prepareWikiformatting();
  	
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
      if ( isset($this->params['tab'])) $tab = $this->params['tab'];
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
  function _prepateSettingTabs()
  {
  	$tabs = array(
  	  aa('name', 'general', 'partial', 'settings/general', 'label', __('General',true)),
      //{:name => 'authentication', :partial => 'settings/authentication', :label => :label_authentication},
      aa('name', 'projects', 'partial', 'settings/projects', 'label', __('Projects',true)),
      //{:name => 'issues', :partial => 'settings/issues', :label => :label_issue_tracking},
      //{:name => 'notifications', :partial => 'settings/notifications', :label => l(:field_mail_notification)},
      //{:name => 'mail_handler', :partial => 'settings/mail_handler', :label => l(:label_incoming_emails)},
      //{:name => 'repositories', :partial => 'settings/repositories', :label => :label_repository_plural}
  	);
  	$this->set('settings_tabs',$tabs);
  }
  function _prepateThemes()
  {
  	//TODO; scan real status on tehemes
  	$themes = aa(
  	  'Alternate','Alternate',
  	  'Classic','Classic'
  	);
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
}

