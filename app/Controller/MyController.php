<?php

/**
 * MyController
 *
 */
class MyController extends AppController
{
    var $uses = array('User', 'Project');
    #  helper :issues

    var $BLOCKS;
    var $DEFAULT_LAYOUT;

    /**
     * beforeFilter
     *
     * before_filter :require_login
     */
    function beforeFilter()
    {
        parent::beforeFilter();
        $this->require_login();
        $this->defineBlocks();
    }

    function defineBlocks()
    {
        $this->BLOCKS = array(
            'issuesassignedtome' => __('Issues assigned to me'),
            #             'issuesreportedbyme' => :label_reported_issues,
            #             'issueswatched' => :label_watched_issues,
            #             'news' => :label_news_latest,
            #             'calendar' => :label_calendar,
            #             'documents' => :label_document_plural,
            #             'timelog' => :label_spent_time
            #           }.freeze
        );

        $this->DEFAULT_LAYOUT = array(
            'left' => array('issuesassignedtome'),
            'right' => array('issuesreportedbyme')
        );
    }

    #  verify :xhr => true,
    #         :session => :page_layout,
    #         :only => [:add_block, :remove_block, :order_blocks]
    #

    public function password()
    {
        // cann't change password ,user has auth_source_id
        if ($this->current_user['auth_source_id']) {
            $this->Session->setFlash(__('This account uses an external authentication source. Impossible to change the password.'), 'default', array('class' => 'flash flash_notice'));
            $this->redirect('account');
        }
        if (!empty($this->request->data)) {
            if ($this->User->check_password($this->request->data['User']['password'], $this->current_user)) {
                $data = $this->request->data;
                $data['User']['id'] = $this->current_user['id'];
                $data['User']['password'] = $this->request->data['User']['new_password'];
                $data['User']['password_confirmation'] = $this->request->data['User']['new_password_confirmation'];
                if ($this->User->save($data)) {
                    $this->Session->setFlash(__('Password was successfully updated.'), 'default', array('class' => 'flash flash_notice'));
                    $this->redirect('account');
                }
            } else {
                $this->Session->setFlash(__('Wrong password'), 'default', array('class' => 'flash flash_error'));
            }
        }
    }

    public function reset_rss_key()
    {
        //TODO: POST check
        $this->User->RssToken->destroy($this->current_user['id'], 'feeds');
        $this->Session->setFlash(__('Your RSS access key was reset.'), 'default', array('class' => 'flash flash_notice'));
        $this->redirect('account');
    }

    public function reset_api_key()
    {
        //TODO: POST check
        $this->User->ApiToken->destroy($this->current_user['id'], 'api');
        $this->Session->setFlash(__('Your API access key was reset.'), 'default', array('class' => 'flash flash_notice'));
        $this->redirect('account');
    }

    public function page_layout()
    {
        #    @user = User.current
        #    @blocks = @user.pref[:my_page_layout] || DEFAULT_LAYOUT.dup
        #    session[:page_layout] = @blocks
        #    %w(top left right).each {|f| session[:page_layout][f] ||= [] }
        #    @block_options = []
        #    BLOCKS.each {|k, v| @block_options << [l(v), k]}
    }

    #  # Add a block to user's page
    #  # The block is added on top of the page
    #  # params[:block] : id of the block to add
    #  def add_block
    #    block = params[:block]
    #    render(:nothing => true) and return unless block && (BLOCKS.keys.include? block)
    #    @user = User.current
    #    # remove if already present in a group
    #    %w(top left right).each {|f| (session[:page_layout][f] ||= []).delete block }
    #    # add it on top
    #    session[:page_layout]['top'].unshift block
    #    render :partial => "block", :locals => {:user => @user, :block_name => block}
    #  end
    #
    #  # Remove a block to user's page
    #  # params[:block] : id of the block to remove
    #  def remove_block
    #    block = params[:block]
    #    # remove block in all groups
    #    %w(top left right).each {|f| (session[:page_layout][f] ||= []).delete block }
    #    render :nothing => true
    #  end
    #
    #  # Change blocks order on user's page
    #  # params[:group] : group to order (top, left or right)
    #  # params[:list-(top|left|right)] : array of block ids of the group
    #  def order_blocks
    #    group = params[:group]
    #    group_items = params["list-#{group}"]
    #    if group_items and group_items.is_a? Array
    #      # remove group blocks if they are presents in other groups
    #      %w(top left right).each {|f|
    #        session[:page_layout][f] = (session[:page_layout][f] || []) - group_items
    #      }
    #      session[:page_layout][group] = group_items
    #    end
    #    render :nothing => true
    #  end
    #
    #  # Save user's page layout
    #  def page_layout_save
    #    @user = User.current
    #    @user.pref[:my_page_layout] = session[:page_layout] if session[:page_layout]
    #    @user.pref.save
    #    session[:page_layout] = nil
    #    redirect_to :action => 'page'
    #  end
    #end

    /**
     * index
     *
     */
    public function index()
    {
        $this->page();
        return 'page';
    }

    /**
     * page
     *
     * Show user's page
     */
    public function page()
    {
        $this->set('user', $this->current_user);
        $this->set('blocks', $this->DEFAULT_LAYOUT);
        #    @blocks = @user.pref[:my_page_layout] || DEFAULT_LAYOUT
    }

    /**
     * account
     *
     * Edit user's account
     */
    public function account()
    {
        #    @user = User.current
        #    @pref = @user.pref

        if ($this->request->data) {
            #      @user.attributes = params[:user]
            $this->request->data['User']['mail_notification'] = $this->request->data['User']['notification_option'] == 'all' ? 1 : 0;
            #      @user.pref.attributes = params[:pref]
            $this->request->data['UserPreference']['pref']['no_self_notified'] = ($this->request->data['UserPreference']['pref']['no_self_notified'] == '1');
            $this->request->data['User']['id'] = $this->current_user['id'];
            if ($this->User->save($this->request->data)) {
                $this->request->data['UserPreference']['user_id'] = $this->User->id;
                if (isset($this->current_user['UserPreference']['id'])) {
                    $this->request->data['UserPreference']['id'] = $this->current_user['UserPreference']['id'];
                }
                $this->User->UserPreference->save($this->request->data);
                $notified_project_ids = array();
                if ($this->request->data['User']['notification_option'] === 'selected') {
                    $notified_project_ids = array_filter($this->request->data['User']['notified_project_ids']);
                }
                $this->User->set_notified_project_ids($notified_project_ids, $this->current_user['id']);
                #        set_language_if_valid @user.language
                $this->Session->setFlash(__('Successful update.'), 'default', array('class' => 'flash flash_notice'));
                $this->redirect('account');
                #        return
            }
        } else {
            $this->request->data = $this->User->find(
                'first',
                array(
                    'conditions' => array(
                        'User.id' => $this->current_user['id']
                    )
                )
            );
        }
        $notification_options = array();
        $notification_options['all'] = __("\"For any event on all my projects\"");

        if (!empty($this->current_user['memberships'])) {
            $notification_options['selected'] = __("\"For any event on the selected projects only...\"");
        }
        $notification_options['none'] = __("\"Only for things I watch or I'm involved in\"");

        $project_ids = $this->User->notified_projects_ids($this->current_user['id']);
        $this->request->data['User']['notified_project_ids'] = $project_ids;
        $notification_option = empty($project_ids) ? 'none' : 'selected';
        if ($this->current_user['mail_notification']) {
            $notification_option = 'all';
        }
        $this->set('notification_options', $notification_options);
        $this->set('notification_option', $notification_option);
    }
}