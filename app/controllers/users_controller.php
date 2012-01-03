<?php
/**
 * Users Controller
 *
 * @package candycane
 * @subpackage candycane.controllers
 */
class UsersController extends AppController {

/**
 * Helpers
 *
 * @var array
 */
	public $helpers = array('Users', 'Sort', 'Ajax', 'Text');

/**
 * Components
 *
 * @var array
 */
	public $components = array('Sort', 'Users');

	public $uses = array('User','Member','Role');

	#  helper :custom_fields
	#  include CustomFieldsHelper

/**
 * beforeFilter
 *
 * # before_filter :require_admin
 */
	public function beforeFilter() {
		parent::beforeFilter();
		if (empty($this->params['requested'])) {
			$this->require_admin();
		}
	}

/**
 * index
 *
 */
	public function index() {
		return $this->list_(); // unless request.xhr?
	}

/**
 * edit
 *
 */
	public function edit($id = null) {
		if (!empty($this->data)) {
			if (empty($this->data[$this->User->alias]['password'])) {
				unset($this->data[$this->User->alias]['password']);
				unset($this->data[$this->User->alias]['password_confirmation']);
			}
			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('Successful update.', true), 'default', array('class' => 'flash flash_notice'));
				$this->redirect('list');
			}
		}

		$user = $user = $this->User->read(null, $id);

		$tabs = array(
		  array(
			'name' => 'general',
			'partial' => 'users/general',
			'label' => __('General', true)
		  ),
		  array(
			'name' => 'memberships',
			'partial' => 'users/memberships',
			'label' => __('Projects', true)
		  ),
		);

		$this->set('settings_tabs',$tabs);
		$this->set('user', $user);
		$this->set('projects', $this->Project->find('all', array('order' => 'name', 'conditions' => array('Project.status' => PROJECT_STATUS_ACTIVE))));
		$this->set('roles',$this->Role->find_all_givable());
	}

	public function edit_membership($id) {
		$data = array(
				'id' => $this->_get_param('membership_id'),
				'user_id' => $id,
				'role_id' => $this->data['Member']['role_id'],
		);
		if ($this->data['Member']['project_id']) {
			$data['project_id'] = $this->data['Member']['project_id'];
		}
		if ($data['id'] || $this->data['Member']['project_id']) {
			$this->Member->save(array(
				'Member' => $data
			));
		}
		$this->redirect(array(
			'controller' => 'users',
			'action' => 'edit',
			'id' => $id,
			'tab' => 'memberships'
		));
	}

	public function destroy_membership($id) {
		$this->Member->delete($this->_get_param('membership_id'));
		$this->redirect(array(
			'controller' => 'users',
			'action' => 'edit',
			'id' => $id,
			'tab' => 'memberships'
		));
	}

/**
 * List_
 *
 * @return void
 */
	public function list_() {
		$this->Sort->sort_init('login', 'asc');
		$this->Sort->sort_update(
			array('login', 'firstname', 'lastname', 'mail', 'admin', 'created_on', 'last_login_on')
		);

		if (isset($this->params['url']['status'])) {
			$status = (int)$this->params['url']['status'];
		} else {
			$status = 1;
		}

		$this->set('status', $status);

		# @user_count = User.count(:conditions => c.conditions)
		# @user_pages = Paginator.new self, @user_count,
		#								per_page_option,
		#								params['page']

		# @users =  User.find :all,:order => sort_clause,
		#                        :conditions => c.conditions,
		#                        :limit  =>  @user_pages.items_per_page,
		#                        :offset =>  @user_pages.current.offset

		if ($status > 0 && $status < 4) {
			$condition = array('status' => $status);
		} else {
			$condition = array();
		}

		$name = null;
		if (!empty($this->params['url']['name'])) {
			$name = $this->params['url']['name'];
			$q_name = "%{$name}%";
			$condition['LOWER(login) LIKE ? OR LOWER(firstname) LIKE ? OR LOWER(lastname) LIKE ?'] = array($q_name, $q_name, $q_name);
		}

		$this->set('name', $name);

		$users = $this->User->find('all', array(
			'conditions' => $condition
		));
		$this->set('user_list', $users);

		$status_counts = $this->User->find('all', array(
			'group' => array('status'),
			'fields' => array('User.status', 'COUNT(*) as cnt'),
		));

		$counts = array(0, 0, 0, 0);
		foreach ($status_counts as $row) {
			$counts[$row['User']['status']] = $row[0]['cnt'];
		}

		$status_option = array(
			'' => __('all', true),
			1  => __('active', true) . ' (' . (int)$counts[1] . ')',
			2  => __('registered', true) . ' (' . (int)$counts[2] . ')',
			3  => __('locked', true) . ' (' . (int)$counts[3] . ')',
		);

		$this->set('status_option', $status_option);

		if (isset($request->xhr)) {
			$this->layout = false;
		}

		return $this->render('list');
	}

/**
 * Add
 *
 * @return void
 */
	public function add() {
		if (!$this->data) {
			# @user = User.new(:language => Setting.default_language)
		} else {
			# @user = User.new(params[:user])
			# @user.admin = params[:user][:admin] || false

			$this->data['User']['created_on'] = date('Y-m-d H:i:s'); // @todo model de yarubeki
			if ($this->User->save($this->data)) {
				# Mailer.deliver_account_information(@user, params[:password]) if params[:send_information]
				$this->Session->setFlash(__('Successful creation.', true), 'default', array('class' => 'flash flash_notice'));
				$this->redirect('/users/index');
			}
		}

		$user = $this->User->create(array(
			'id' => '',
			'login' => '',
			'firstname' => '',
			'lastname' => '',
			'mail' => '',
			'language' => '',
			));
		$this->set('user', $user);
		# @auth_sources = AuthSource.find(:all)
	}

/**
 * Allowed To
 *
 * @return boolean
 */
	public function allowed_to() {
		if (empty($this->params['requested'])) {
			$this->cakeError('error404');
		}
		return $this->User->is_allowed_to($this->current_user, $this->params['aco'], $this->params['project']);
	}

}
