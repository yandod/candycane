<?php
/**
 * account_controller.php
 *
 */

/**
 * AccountController
 *
 * @todo implement yet
 */
class AccountController extends AppController {

	public $uses = array('User', 'Project', 'Setting', 'Event', 'Issue', 'Token');

	public $helpers = array('Text');

	public $components = array('Fetcher', 'RequestHandler', 'Mailer');

  //  helper :custom_fields
  //  include CustomFieldsHelper

  // prevents login action to be filtered by check_if_login_required application scope filter

/**
 * beforeFilter
 *
 * skip_before_filter :check_if_login_required, :only => [:login, :lost_password, :register, :activate]
 */
	public function beforeFilter() {
		$skip_action = array('login', 'lost_password', 'register', 'activate');
		if (!in_array($this->action, $skip_action)) {
			parent::beforeFilter();
		} else {
			$this->setSettings(); // todo: kimoi
			$this->set_localization();
			$this->set('currentuser',aa('logged',false));
		}
	}

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
	#end

/**
 * register
 *
 * User self-registration
 *
 * @todo Email Activation
 * @todo flash
 * @todo Mailer
 */
	public function register() {
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
				$this->logged_user($this->User->findByLogin($this->data['User']['login']));

				# flash[:notice] = l(:notice_account_activated)
				$this->redirect('/my/account');
			}
		} else {
			switch ($this->Setting->self_registration) {
				case '1':
					// Email activation
					#token = Token.new(:user => @user, :action => "register")
					#if @user.save and token.save
					#  Mailer.deliver_register(token)
					#  flash[:notice] = l(:notice_account_register_done)
					#  redirect_to :action => 'login'
					#end
					break;
				case '3':
					// Automatic activation
					$this->data['User']['status'] = 1;
					if ($this->User->save($this->data)) {
						$this->logged_user($this->User->findByLogin($this->data['User']['login']));
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
	public function login() {
		$this->set('setting', $this->Setting);

		if (!$this->data) {
			$this->set('back_url',$this->referer());
			return;
		}
		$this->set('back_url',$this->params['form']['back_url']);
		// validate
		$this->User->set($this->data);
		if (!$this->User->validates()) {
			return;
		}

        // Authenticate user
        $user = $this->User->tryToLogin($this->data['User']['username'], $this->data['User']['password']);

		if ($user === false) {
			// Invalid credentials
			$this->Session->setFlash(__('Invalid user or password',true), 'default', array('class' => 'flash flash_error'));
			return;
		} else if ((bool)$this->User->data) {
			// (bool)$this->User->data == new_record
			// Onthefly creation failed, display the registration form to fill/fix attributes
			#@user = user
			#session[:auth_source_registration] = {:login => user.login, :auth_source_id => user.auth_source_id }
			$this->render('register');
		} else {
			// Valid user
			$this->logged_user($user);

			## generate a key and set cookie if autologin
			#if params[:autologin] && Setting.autologin?
			#  token = Token.create(:user => user, :action => 'autologin')
			#  cookies[:autologin] = { :value => token.value, :expires => 1.year.from_now }
			#end
			#redirect_back_or_default :controller => 'my', :action => 'page'
			App::import('Helper', 'Html');
			$html = new HtmlHelper();
			if (!$this->params['form']['back_url'][0] == '/' ||
				$this->params['form']['back_url'] == $html->url($this->action)
			) {
				$this->params['form']['back_url'] = '/';
			}
			$this->redirect($this->params['form']['back_url']);
		}
	}

/**
 * logout
 *
 * Log out current user and redirect to welcome page
 *
 * @todo implement yet
 */
	public function logout() {
		$this->Session->destroy();
		#cookies.delete :autologin
		#Token.delete_all(["user_id = ? AND action = ?", User.current.id, 'autologin']) if User.current.logged?
		#self.logged_user = nil
		$this->redirect('/');
	}

/**
 * show
 *
 * Show user's account
 *
 * @todo implement yet
 * @todo custom values
 */
	public function show($id) {
        $id = (int)$id;

        $user = $this->User->find('first',
          array(
            'conditions' => array('User.id' => $id),
            'recursive' => 2)
        );

		if (!is_array($user)) {
            $this->cakeError('error', array('message' => "user id {$id} not found."));
		}

        $this->set('user', $user);
        #@custom_values = @user.custom_values
        $this->Fetcher->fetch($this->current_user,array('author' => $user['User']['id']));
        $events = $this->Fetcher->events(null, null, array('limit' => 10));
        $events_by_day_data = $this->Event->group_by($events, 'event_date');
        $this->set('events_by_day_data',$events_by_day_data);
        $this->set('issue_count',$this->Issue->find('count',aa('conditions',aa('author_id',$user['User']['id']))));
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
	public function lost_password() {
		if (!$this->Setting->lost_password) {
			$this->redirect('/');
		}

		if (isset($this->params['named']['token'])) {
			$token = $this->Token->find('first',
				array('conditions' => array('Token.action' => 'recovery', 'Token.value' => $this->params['named']['token']))
			);

			if ($this->Token->isExpired($token)) {
				// @TODO add error message
				$this->redirect('/');
			}
			if ($this->RequestHandler->isPost()) {
				if ($this->data['User']['new_password'] != $this->data['User']['new_password_confirmation']) {
					// @TODO add error message
					$this->render('password_recovery');
					return;
				}

				$user = $this->User->findById($token['Token']['user_id']);
				$user['User']['password'] = $this->data['User']['new_password'];
				$user['User']['password_confirmation'] = $this->data['User']['new_password_confirmation'];

				if ($this->User->save($user)) {
					$this->Token->destroy($user['User']['id'], 'recovery');
					$this->Session->setFlash(__('Password was successfully updated.',true), 'default', array('class' => 'flash flash_notice'));
					$this->redirect('/login');
					return;
				}
			}

			$this->set('token', $token);
			$this->render('password_recovery');
			return;
		}

		if ($this->RequestHandler->isPost()) {
			$user = $this->User->findByMail($this->data['User']['mail']);

			# user not found in db
			if ($user === false) {
				// @TODO add error message
				return;
			}

			# create a new token for password recovery
			$data = array(
				'user_id' => $user['User']['id'],
				'action' => 'recovery',
				'value' => $this->Token->_generate_token_value(), // @TODO more smart
			);

			if ($token = $this->Token->save($data)) {
				$this->Mailer->deliver_lost_password($token, $user);
				$this->Session->setFlash(__('An email with instructions to choose a new password has been sent to you.',true), 'default', array('class' => 'flash flash_notice'));
				$this->redirect('/login');
			}
		}
	}

/**
 * logged_user
 * @access private
 */
	public function logged_user($user) {
		if (isset($user['User'])) {
			$user = $user['User'];
		}

		if (isset($user) && is_array($user)) {
			$this->currentuser = $this->User->findById($user['id']);
			$this->Session->write('user_id', $user['id']);
		} else {
			$this->currentuser = null;
			$this->Session->write('user_id', null);
		}
	}

}
