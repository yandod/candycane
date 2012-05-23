<?php
App::import('Vendor','candycane/ActionMailer');
class MailerComponent extends ActionMailer {
    var $name = 'Mailer';
    var $layout = 'mail';
    var $subject = '';
    
    function startup($controller){
        $this->controller = $controller;
        $this->setHeader('Content-type', 'text/plain');

        if(extension_loaded('mbstring')){
			switch (Configure::read('Config.language')) {
				case 'jpn':
					$lang = "ja";
					break;
				case 'eng':
					$lang = "en";
					break;
				default:
					$lang = "uni";
			}
            mb_language($lang);
            mb_internal_encoding("UTF-8");
        }
    }

    function  beforeRender() {
      parent::beforeRender();
      $this->setHeader('From', $this->controller->Setting->mail_from);
      $this->set('footer',$this->controller->Setting->emails_footer);
    }
	
	public function setRecipients($emails) {
		if (
			isset($this->controller->current_user['UserPreference']['pref']['no_self_notified']) &&
			$this->controller->current_user['UserPreference']['pref']['no_self_notified']
		) {
			$new_emails = array();
			foreach( $emails as $k => $v ) {
				if ($this->controller->current_user['mail'] == $v) {
					continue;
				}
				$new_emails[$k] = $v;
			}
			$emails = $new_emails;
		}
		parent::setRecipients($emails);
	}
    function issue_add($Issue) {
    #    redmine_headers 'Project' => issue.project.identifier,
    #                    'Issue-Id' => issue.id,
    #                    'Issue-Author' => issue.author.login
    #    redmine_headers 'Issue-Assignee' => issue.assigned_to.login if issue.assigned_to
    #    recipients issue.recipients
    $this->setRecipients($Issue->recipients());
    #    cc(issue.watcher_recipients - @recipients)
    $issue_data = $Issue->findById($Issue->id);
    $s = "{$issue_data['Project']['name']} - {$issue_data['Tracker']['name']} #{$issue_data['Issue']['id']} ";
    $s .= "{$issue_data['Status']['name']} ";
    $s .= "{$issue_data['Issue']['subject']}";
    $this->subject = $s;
    #    body :issue => issue,
    #         :issue_url => url_for(:controller => 'issues', :action => 'show', :id => issue)
    $this->set('issue',$issue_data);
    $this->set('issueurl',Router::url(array(
        'controller' => 'issues',
        'action' => 'show',
        'issue_id' => $Issue->id
        ),
        true
    ));
    }
    
    function issue_edit($Journal,$Issue) {
    #    issue = journal.journalized
    #    redmine_headers 'Project' => issue.project.identifier,
    #                    'Issue-Id' => issue.id,
    #                    'Issue-Author' => issue.author.login
    #    redmine_headers 'Issue-Assignee' => issue.assigned_to.login if issue.assigned_to
    #    @author = journal.user
    #    recipients issue.recipients
    $this->setRecipients($Issue->recipients());
    #    # Watchers in cc
    #    cc(issue.watcher_recipients - @recipients)
    #    s = "[#{issue.project.name} - #{issue.tracker.name} ##{issue.id}] "
    $issue_data = $Issue->findById($Issue->id);
    $s = "{$issue_data['Project']['name']} - {$issue_data['Tracker']['name']} #{$issue_data['Issue']['id']} ";
    $s .= "{$issue_data['Status']['name']} ";
    $s .= "{$issue_data['Issue']['subject']}";
    $this->subject = $s;
    #    body :issue => issue,
    #         :journal => journal,
    #         :issue_url => url_for(:controller => 'issues', :action => 'show', :id => issue)
    #  end
    $journal_data = $Journal->findById($Journal->getLastInsertID());
    $this->set('issue',$issue_data);
    $this->set('journal',$journal_data);
    $this->set('issueurl',Router::url(array(
        'controller' => 'issues',
        'action' => 'show',
        'issue_id' => $Issue->id
        ),
        true
    ));
    }

	public function register($token, $user) {
		#    set_language_if_valid(token.user.language)
		$this->addRecipient($user['User']['mail']);
		$this->setSubject(sprintf(
			__('Your %s account activation'),
			$this->controller->Setting->app_title
		));
		$this->set('token', $token);
		$this->set('url',Router::url(
			array(
				'controller' => 'account',
				'action' => 'activate',
				'?' => array(
					'token' => $token['value']
				)
			),
			true
		));		
	}

	public function account_activation_request($user, $User) {

		//Send the email to all active administrators
		$recipients = $User->find('list',array(
			'fields' => array('id','mail'),
			'conditions' => array(
				'User.admin' => true,
				'User.status' => 1, //active
			)
		));
		$this->setRecipients($recipients);
		$this->setSubject(sprintf(
			__('%s account activation request'),
			$this->controller->Setting->app_title
		));
		$this->set('user',$user);
		$this->set('url',Router::url(
			array(
				'controller' => 'users',
				'action' => 'index',
				'?' => array(
					'status' => 2
				)
			),
			true
		));		
	}

    function lost_password($token, $user)
    {
        # set_language_if_valid(token.user.language)

        $this->addRecipient($user['User']['mail']);
        $this->setSubject(__('Your password'));

        $this->set('token', $token);
        $this->set('user', $user);
        $this->set('url', Router::url(
            array(
                'controller' => 'account',
                'action' => 'lost_password',
                'token' => $token['Token']['value']
            ),
            true
        ));
    }

	public function news_added($news) {
		#    redmine_headers 'Project' => news.project.identifier
		$news->read();
		$news->Project->read();
		$this->setRecipients($news->Project->recipients());
		$this->setSubject(sprintf("[%s] %s: %s",
			$news->Project->data['Project']['name'],
			__('News'),
			$news->data['News']['title']
		));
		$this->set('news', $news->data);
		$this->set('news_url', Router::url(
            array(
                'controller' => 'news',
                'action' => 'show',
                'id' => $news->id
            ),
            true
        ));
	}

	public function test($user) {
		#    set_language_if_valid(user.language)
		$this->setRecipient($user['mail']);
		$this->setSubject('CandyCane test');
		$this->set('url',Router::url(
            array('controller' => 'welcome'),
            true
        ));
	}
}
#class Mailer < ActionMailer::Base
#  helper :application
#  helper :issues
#  helper :custom_fields
#
#  include ActionController::UrlWriter
#
#
#
#  def reminder(user, issues, days)
#    set_language_if_valid user.language
#    recipients user.mail
#    subject l(:mail_subject_reminder, issues.size)
#    body :issues => issues,
#         :days => days,
#         :issues_url => url_for(:controller => 'issues', :action => 'index', :set_filter => 1, :assigned_to_id => user.id, :sort_key => 'due_date', :sort_order => 'asc')
#  end
#
#  def document_added(document)
#    redmine_headers 'Project' => document.project.identifier
#    recipients document.project.recipients
#    subject "[#{document.project.name}] #{l(:label_document_new)}: #{document.title}"
#    body :document => document,
#         :document_url => url_for(:controller => 'documents', :action => 'show', :id => document)
#  end
#
#  def attachments_added(attachments)
#    container = attachments.first.container
#    added_to = ''
#    added_to_url = ''
#    case container.class.name
#    when 'Project'
#      added_to_url = url_for(:controller => 'projects', :action => 'list_files', :id => container)
#      added_to = "#{l(:label_project)}: #{container}"
#    when 'Version'
#      added_to_url = url_for(:controller => 'projects', :action => 'list_files', :id => container.project_id)
#      added_to = "#{l(:label_version)}: #{container.name}"
#    when 'Document'
#      added_to_url = url_for(:controller => 'documents', :action => 'show', :id => container.id)
#      added_to = "#{l(:label_document)}: #{container.title}"
#    end
#    redmine_headers 'Project' => container.project.identifier
#    recipients container.project.recipients
#    subject "[#{container.project.name}] #{l(:label_attachment_new)}"
#    body :attachments => attachments,
#         :added_to => added_to,
#         :added_to_url => added_to_url
#  end
#
#  def news_added(news)
#    redmine_headers 'Project' => news.project.identifier
#    recipients news.project.recipients
#    subject "[#{news.project.name}] #{l(:label_news)}: #{news.title}"
#    body :news => news,
#         :news_url => url_for(:controller => 'news', :action => 'show', :id => news)
#  end
#
#  def message_posted(message, recipients)
#    redmine_headers 'Project' => message.project.identifier,
#                    'Topic-Id' => (message.parent_id || message.id)
#    recipients(recipients)
#    subject "[#{message.board.project.name} - #{message.board.name}] #{message.subject}"
#    body :message => message,
#         :message_url => url_for(:controller => 'messages', :action => 'show', :board_id => message.board_id, :id => message.root)
#  end
#
#  def account_information(user, password)
#    set_language_if_valid user.language
#    recipients user.mail
#    subject l(:mail_subject_register, Setting.app_title)
#    body :user => user,
#         :password => password,
#         :login_url => url_for(:controller => 'account', :action => 'login')
#  end
#
#
#  # Overrides default deliver! method to prevent from sending an email
#  # with no recipient, cc or bcc
#  def deliver!(mail = @mail)
#    return false if (recipients.nil? || recipients.empty?) &&
#                    (cc.nil? || cc.empty?) &&
#                    (bcc.nil? || bcc.empty?)
#    super
#  end
#
#  # Sends reminders to issue assignees
#  # Available options:
#  # * :days     => how many days in the future to remind about (defaults to 7)
#  # * :tracker  => id of tracker for filtering issues (defaults to all trackers)
#  # * :project  => id or identifier of project to process (defaults to all projects)
#  def self.reminders(options={})
#    days = options[:days] || 7
#    project = options[:project] ? Project.find(options[:project]) : nil
#    tracker = options[:tracker] ? Tracker.find(options[:tracker]) : nil
#
#    s = ARCondition.new ["#{IssueStatus.table_name}.is_closed = ? AND #{Issue.table_name}.due_date <= ?", false, days.day.from_now.to_date]
#    s << "#{Issue.table_name}.assigned_to_id IS NOT NULL"
#    s << "#{Project.table_name}.status = #{Project::STATUS_ACTIVE}"
#    s << "#{Issue.table_name}.project_id = #{project.id}" if project
#    s << "#{Issue.table_name}.tracker_id = #{tracker.id}" if tracker
#
#    issues_by_assignee = Issue.find(:all, :include => [:status, :assigned_to, :project, :tracker],
#                                          :conditions => s.conditions
#                                    ).group_by(&:assigned_to)
#    issues_by_assignee.each do |assignee, issues|
#      deliver_reminder(assignee, issues, days) unless assignee.nil?
#    end
#  end
#
#  private
#  def initialize_defaults(method_name)
#    super
#    set_language_if_valid Setting.default_language
#    from Setting.mail_from
#    
#    # URL options
#    h = Setting.host_name
#    h = h.to_s.gsub(%r{\/.*$}, '') unless ActionController::AbstractRequest.relative_url_root.blank?
#    default_url_options[:host] = h
#    default_url_options[:protocol] = Setting.protocol
#    
#    # Common headers
#    headers 'X-Mailer' => 'Redmine',
#            'X-Redmine-Host' => Setting.host_name,
#            'X-Redmine-Site' => Setting.app_title
#  end
#
#  # Appends a Redmine header field (name is prepended with 'X-Redmine-')
#  def redmine_headers(h)
#    h.each { |k,v| headers["X-Redmine-#{k}"] = v }
#  end
#
#  # Overrides the create_mail method
#  def create_mail
#    # Removes the current user from the recipients and cc
#    # if he doesn't want to receive notifications about what he does
#    @author ||= User.current
#    if @author.pref[:no_self_notified]
#      recipients.delete(@author.mail) if recipients
#      cc.delete(@author.mail) if cc
#    end
#    # Blind carbon copy recipients
#    if Setting.bcc_recipients?
#      bcc([recipients, cc].flatten.compact.uniq)
#      recipients []
#      cc []
#    end
#    super
#  end
#
#  # Renders a message with the corresponding layout
#  def render_message(method_name, body)
#    layout = method_name.match(%r{text\.html\.(rhtml|rxml)}) ? 'layout.text.html.rhtml' : 'layout.text.plain.rhtml'
#    body[:content_for_layout] = render(:file => method_name, :body => body)
#    ActionView::Base.new(template_root, body, self).render(:file => "mailer/#{layout}", :use_full_path => true)
#  end
#
#  # for the case of plain text only
#  def body(*params)
#    value = super(*params)
#    if Setting.plain_text_mail?
#      templates = Dir.glob("#{template_path}/#{@template}.text.plain.{rhtml,erb}")
#      unless String === @body or templates.empty?
#        template = File.basename(templates.first)
#        @body[:content_for_layout] = render(:file => template, :body => @body)
#        @body = ActionView::Base.new(template_root, @body, self).render(:file => "mailer/layout.text.plain.rhtml", :use_full_path => true)
#        return @body
#      end
#    end
#    return value
#  end
#
#  # Makes partial rendering work with Rails 1.2 (retro-compatibility)
#  def self.controller_path
#    ''
#  end unless respond_to?('controller_path')
#end
