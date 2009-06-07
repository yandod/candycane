<?php
/**
 * my_controller.php
 *
 */

/**
 * MyController
 *
 */
class MyController extends AppController
{
  var $uses = array('User', 'Project');
  #  helper :issues

  /**
   * beforeFilter
   *
   * before_filter :require_login
   */
  function beforeFilter()
  {
    parent::beforeFilter();
    $this->require_login();
  }

  #  BLOCKS = { 'issuesassignedtome' => :label_assigned_to_me_issues,
  #             'issuesreportedbyme' => :label_reported_issues,
  #             'issueswatched' => :label_watched_issues,
  #             'news' => :label_news_latest,
  #             'calendar' => :label_calendar,
  #             'documents' => :label_document_plural,
  #             'timelog' => :label_spent_time
  #           }.freeze
  #
  #  DEFAULT_LAYOUT = {  'left' => ['issuesassignedtome'], 
  #                      'right' => ['issuesreportedbyme'] 
  #                   }.freeze
  #
  #  verify :xhr => true,
  #         :session => :page_layout,
  #         :only => [:add_block, :remove_block, :order_blocks]
  #
  #  # Manage user's password
  #  def password
  #    @user = User.current
  #    flash[:error] = l(:notice_can_t_change_password) and redirect_to :action => 'account' and return if @user.auth_source_id
  #    if request.post?
  #      if @user.check_password?(params[:password])
  #        @user.password, @user.password_confirmation = params[:new_password], params[:new_password_confirmation]
  #        if @user.save
  #          flash[:notice] = l(:notice_account_password_updated)
  #          redirect_to :action => 'account'
  #        end
  #      else
  #        flash[:error] = l(:notice_account_wrong_password)
  #      end
  #    end
  #  end
  #  
  #  # Create a new feeds key
  #  def reset_rss_key
  #    if request.post? && User.current.rss_token
  #      User.current.rss_token.destroy
  #      flash[:notice] = l(:notice_feeds_access_key_reseted)
  #    end
  #    redirect_to :action => 'account'
  #  end
  #
  #  # User's page layout configuration
  #  def page_layout
  #    @user = User.current
  #    @blocks = @user.pref[:my_page_layout] || DEFAULT_LAYOUT.dup
  #    session[:page_layout] = @blocks
  #    %w(top left right).each {|f| session[:page_layout][f] ||= [] }
  #    @block_options = []
  #    BLOCKS.each {|k, v| @block_options << [l(v), k]}
  #  end
  #  
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
  function index()
  {
    $this->page();
    return 'page';
  }

  /**
   * page
   *
   * Show user's page
   */
  function page()
  {
    $this->set('user', $this->current_user);
    #    @blocks = @user.pref[:my_page_layout] || DEFAULT_LAYOUT
  }

  /**
   * account
   *
   * Edit user's account
   */
  function account()
  {
    #    @user = User.current
    #    @pref = @user.pref

    if ($this->data) {
      #      @user.attributes = params[:user]
      #      @user.mail_notification = (params[:notification_option] == 'all')
      #      @user.pref.attributes = params[:pref]
      #      @user.pref[:no_self_notified] = (params[:no_self_notified] == '1')
      $this->data['User']['id'] = $this->current_user['id'];
      if($this->User->save($this->data)) {
        #        @user.pref.save
        #        @user.notified_project_ids = (params[:notification_option] == 'selected' ? params[:notified_project_ids] : [])
        #        set_language_if_valid @user.language
        #        flash[:notice] = l(:notice_account_updated)
        #        redirect_to :action => 'account'
        $this->Session->setFlash(__('Successful update.', true), 'default', array('class'=>'flash flash_notice'));
        $this->redirect('account');
        #        return
      }
    } else {
      $this->data = $this->User->find('first',aa('conditions',aa('User.id',$this->current_user['id'])));
    }
      $notification_options = array();
      $notification_options['all']= __("\"For any event on all my projects\"",true);
       
      
      if ( !empty($this->current_user['memberships'])) {
        $notification_options['selected']= __("\"For any event on the selected projects only...\"",true);
      }
      $notification_options['none']= __("\"Only for things I watch or I'm involved in\"",true);
      
      $project_ids = $this->User->notified_projects_ids($this->current_user['id']);
      $notification_option = empty($project_ids) ? 'none' : 'selected';
      $this->set('notification_options',$notification_options);
      $this->set('notification_option',$notification_option);      
  }
}
