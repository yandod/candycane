<?php

class SettingsController extends AppController
{
    public $uses = array();

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->require_admin();
    }

    public function index()
    {
        $this->edit();
        $this->render('edit');
    }

    public function edit()
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
        if (!empty($this->request->data)) {
            foreach ($this->request->data['Setting'] as $k => $v) {
                $this->Setting->store($k, $v);
            }
            $this->Session->setFlash(
                __('Successful update.'),
                'default',
                array('class' => 'flash notice')
            );
            $tab = 'general';
            if (isset($this->request->params['url']['tab'])) {
                $tab = $this->request->params['url']['tab'];
            }
            $this->redirect(array(
                'action' => 'edit',
                '?' => 'tab=' . $tab
            ));
            return;
        }

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
    public function _prepareSettingTabs()
    {
        $settingContainer = ClassRegistry::getObject('SettingContainer');
        $tabs = $settingContainer->getSystemSetting();

        $this->set('settings_tabs', $tabs);
        $selected_tab = '';
        foreach ($tabs as $row) {
            $selected_tab = $row['name'];
            break;
        }
        if (isset($this->request->params['url']['tab'])) {
            $selected_tab = $this->request->params['url']['tab'];
        }
        $this->set('selected_tab', $selected_tab);
    }

    protected function _prepareThemes()
    {
        $themeContainer = ClassRegistry::getObject('ThemeContainer');
        $themes = $themeContainer->getThemeLists();
        $this->set('themes', $themes);
    }

    protected function _prepareWikiformatting()
    {
        $text_formattings = array(
            'Textile',
            'Pukiwiki'
        );
        $this->set('text_formattings', $text_formattings);
    }

    protected function _prepareColumns()
    {
        App::uses('Query', 'Model');
        $this->Query = new Query();
        $available_columns = $this->Query->available_columns();
        $this->set('available_columns', $available_columns);
    }
}