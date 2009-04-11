<?php
#class AccountController < ApplicationController
#  helper :custom_fields
#  include CustomFieldsHelper   
#  
#  # prevents login action to be filtered by check_if_login_required application scope filter
#
#  # Show user's account
#  def show
#    @user = User.active.find(params[:id])
#    @custom_values = @user.custom_values
#    
#    # show only public projects and private projects that the logged in user is also a member of
#    @memberships = @user.memberships.select do |membership|
#      membership.project.is_public? || (User.current.member_of?(membership.project))
#    end
#    
#    events = Redmine::Activity::Fetcher.new(User.current, :author => @user).events(nil, nil, :limit => 10)
#    @events_by_day = events.group_by(&:event_date)
#    
#  rescue ActiveRecord::RecordNotFound
#    render_404
#  end
#
#  # Token based account activation
#  def activate
#    redirect_to(home_url) && return unless Setting.self_registration? && params[:token]
#    token = Token.find_by_action_and_value('register', params[:token])
#    redirect_to(home_url) && return unless token and !token.expired?
#    user = token.user
#    redirect_to(home_url) && return unless user.status == User::STATUS_REGISTERED
#    user.status = User::STATUS_ACTIVE
#    if user.save
#      token.destroy
#      flash[:notice] = l(:notice_account_activated)
#    end
#    redirect_to :action => 'login'
#  end
#  
#private
#  def logged_user=(user)
#    if user && user.is_a?(User)
#      User.current = user
#      session[:user_id] = user.id
#    else
#      User.current = User.anonymous
#      session[:user_id] = nil
#    end
#  end
#end

/**
 * AccountController
 *
 * @todo implement yet
 */
class AccountController extends AppController {

    var $uses = array('User', 'Project', 'Setting');

    /**
     * beforeFilter
     *
     * skip_before_filter :check_if_login_required, :only => [:login, :lost_password, :register, :activate]
     */
    function beforeFilter()
    {
        $skip_action = array('login', 'lost_password', 'register', 'activate');
        if (!in_array($this->action, $skip_action)) {
            parent::beforeFilter();
        } else {
        	 $this->setSettings(); // todo: kimoi
        	 $this->set('currentuser',aa('logged',false));
        }
    }

  /**
   * register
   *
   * User self-registration
   *
   * @todo Email Activation
   * @todo flash
   * @todo logged_user
   * @todo Mailer
   */
  function register()
  {
    if (!$this->Setting->self_registration || $this->Session->read('auth_source_registration')) {
      $this->redirect('/');
      return;
    }

    if (!$this->data) {
      $this->Session->write('auth_source_registration', null);
      $this->data['User']['language'] = $this->Setting->default_language;
      return;
    }

    $this->data['User']['admin']  = 0;
    $this->data['User']['status'] = 2; // registred

    if ($this->Session->read('auth_source_registration')) {
      $this->data['User']['status'] = 1; // active

      $auth_source_registration = $this->Session->read('auth_source_registration');
      $this->data['User']['login'] = $auth_source_registration['login'];

      $this->data['User']['auth_source_id'] = $auth_source_registration['auth_source_id'];
      if ($this->User->save($this->data)) {
        $this->Session->write('auth_source_registration', null);
        # self.logged_user = @user

        # flash[:notice] = l(:notice_account_activated)
        $this->redirect('/my/account');
      }
    } else {
      switch ($this->Setting->self_registration) {
      case '1':
        // Email activation
#          token = Token.new(:user => @user, :action => "register")
#          if @user.save and token.save
#            Mailer.deliver_register(token)
#            flash[:notice] = l(:notice_account_register_done)
#            redirect_to :action => 'login'
#          end
        break;
      case '3':
        // Automatic activation
        $this->User->set(array('User' => array('status' => 1)));
        if ($this->User->save()) {
          # self.logged_user = @user
          # flash[:notice] = l(:notice_account_activated)
          $this->redirect('/my/account');
        }
        break;
      default:
        // Manual activation by the administrator
        if ($this->User->save($this->data)) {
          // Sends an email to the administrators
          # Mailer.deliver_account_activation_request(@user)
          # flash[:notice] = l(:notice_account_pending)
          $this->redirect('/account/login');
        }
      }
    }
  }

    /**
     * login
     *
     * Login request and validation
     *
     * @todo implement yet
     */
    function login()
    {
        $this->set('setting', $this->Setting);

        if (!$this->data) {
          return;
        }

        // validate
        $this->User->set($this->data);
        if (!$this->User->validates()) {
          return;
        }

        // Authenticate user
        $user = $this->User->tryToLogin($this->data['User']['username'], $this->data['User']['password']);

        if ($user === false) {
          // Invalid credentials
          $this->flash('Invalid credentials', '/account/login');
          return;
          # flash.now[:error] = l(:notice_account_invalid_creditentials)
          $this->cakeError('error', array('message' => 'notice_account_invalid_creditentials'));
        } else if ((bool)$this->User->data) {
            // (bool)$this->User->data == new_record
            // Onthefly creation failed, display the registration form to fill/fix attributes
#        @user = user
#        session[:auth_source_registration] = {:login => user.login, :auth_source_id => user.auth_source_id }
#        render :action => 'register'
            return 'register';
        } else {
            // Valid user
#        self.logged_user = user

#        # generate a key and set cookie if autologin
#        if params[:autologin] && Setting.autologin?
#          token = Token.create(:user => user, :action => 'autologin')
#          cookies[:autologin] = { :value => token.value, :expires => 1.year.from_now }
#        end
#        redirect_back_or_default :controller => 'my', :action => 'page'
            $this->Session->write('user_id', $user['id']); // @todo atode kesu
            $this->redirect('/');        
        }
    }

    /**
     * logout
     *
     * Log out current user and redirect to welcome page
     *
     * @todo implement yet
     */
    function logout()
    {
        $this->Session->destroy();
        #cookies.delete :autologin
        #Token.delete_all(["user_id = ? AND action = ?", User.current.id, 'autologin']) if User.current.logged?
        #self.logged_user = nil
        $this->redirect('/');
        exit;
    }

    /**
     * show
     *
     * Show user's account
     *
     * @todo implement yet
     * @todo custom values
     */
    function show($id)
    {
        $id = (int)$id;

        $user = $this->User->findById($id);
        if (!is_array($user)) {
            $this->cakeError('error', array('message' => "user id {$id} not found."));
        }

        $this->set('user', $user);
        #@custom_values = @user.custom_values
        
        # show only public projects and private projects that the logged in user is also a member of
        #    @memberships = @user.memberships.select do |membership|
        #      membership.project.is_public? || (User.current.member_of?(membership.project))
        #    end
        
        #    events = Redmine::Activity::Fetcher.new(User.current, :author => @user).events(nil, nil, :limit => 10)
        #    @events_by_day = events.group_by(&:event_date)
        #  rescue ActiveRecord::RecordNotFound
        #    render_404
    }

    /**
     * lost_password
     *
     * Enable user to choose a new password
     */
    function lost_password()
    {
      if (!$this->Setting->lost_password) {
        $this->redirect('/');
      }

      if ($this->data['token']) {
#      @token = Token.find_by_action_and_value("recovery", params[:token])
#      redirect_to(home_url) && return unless @token and !@token.expired?
#      @user = @token.user
#      if request.post?
#        @user.password, @user.password_confirmation = params[:new_password], params[:new_password_confirmation]
#        if @user.save
#          @token.destroy
#          flash[:notice] = l(:notice_account_password_updated)
#          redirect_to :action => 'login'
#          return
#        end 
#      end
#      render :template => "account/password_recovery"
#      return
#    else
#      if request.post?
#        user = User.find_by_mail(params[:mail])
#        # user not found in db
#        flash.now[:error] = l(:notice_account_unknown_email) and return unless user
#        # user uses an external authentification
#        flash.now[:error] = l(:notice_can_t_change_password) and return if user.auth_source_id
#        # create a new token for password recovery
#        token = Token.new(:user => user, :action => "recovery")
#        if token.save
#          Mailer.deliver_lost_password(token)
#          flash[:notice] = l(:notice_account_lost_email_sent)
#          redirect_to :action => 'login'
#          return
#        end
#      end
      }
    }

}
