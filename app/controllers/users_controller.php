<?php
/**
 * users_controller.php
 *
 */

/**
 * UsersController
 *
 */
class UsersController extends AppController
{
  var $helpers = array('Users', 'Sort', 'Ajax', 'Text');
  var $components = array('Sort', 'Users');

#  helper :custom_fields
#  include CustomFieldsHelper   

  /**
   * beforeFilter
   *
   * # before_filter :require_admin
   */
  function beforeFilter()
  {
    parent::beforeFilter();
    if(empty($this->params['requested'])) {
      $this->require_admin();
    }
  }

  /**
   * index
   *
   */
  function index()
  {
    return $this->list_(); // unless request.xhr?
  }

  /**
   * edit
   *
   */
  function edit($id = null)
  {
    if ($this->data) {
      if ($this->User->save($this->data)) {
        $this->Session->setFlash(__('Successful update.', true), 'default', array('class'=>'flash flash_notice'));
        $this->redirect('list');
        return;
      }
    }

    $user = $this->User->find('first', array('conditions' => array('User.id' => (int)$id)));

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
#    @projects = Project.find(:all, :order => 'name', :conditions => "status=#{Project::STATUS_ACTIVE}") - @user.projects

#    @auth_sources = AuthSource.find(:all)
#    @roles = Role.find_all_givable
#    @membership ||= Member.new
#    @memberships = @user.memberships
  }
  
#  def edit_membership
#    @user = User.find(params[:id])
#    @membership = params[:membership_id] ? Member.find(params[:membership_id]) : Member.new(:user => @user)
#    @membership.attributes = params[:membership]
#    @membership.save if request.post?
#    redirect_to :action => 'edit', :id => @user, :tab => 'memberships'
#  end
#  
#  def destroy_membership
#    @user = User.find(params[:id])
#    Member.find(params[:membership_id]).destroy if request.post?
#    redirect_to :action => 'edit', :id => @user, :tab => 'memberships'
#  end

  /**
   * list_
   *
   */
  function list_()
  {
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

    #    @user_count = User.count(:conditions => c.conditions)
    #    @user_pages = Paginator.new self, @user_count,
    #								per_page_option,
    #								params['page']								

    #    @users =  User.find :all,:order => sort_clause,
    #                        :conditions => c.conditions,
    #                        :limit  =>  @user_pages.items_per_page,
    #                        :offset =>  @user_pages.current.offset

    if ($status > 0 && $status < 4) {
      $condition = array('status' => $status);
    } else {
      $condition = array();
    }

    $name = null;
    if(!empty($this->params['url']['name'])) {
      $name = $this->params['url']['name'];
      $q_name = "%{$name}%";
      $condition['LOWER(login) LIKE ? OR LOWER(firstname) LIKE ? OR LOWER(lastname) LIKE ?'] = array($q_name, $q_name, $q_name);
    } 

    $this->set('name', $name);

    $users = $this->User->find(
      'all',
      array('conditions' => $condition)
    );
    $this->set('user_list', $users);

    $status_counts = $this->User->find('all',
      array(
        'group' => array('status'),
        'fields' => array('User.status', 'COUNT(*) as cnt'),
      )
    );
    $counts = array(0,0,0,0);
    foreach ($status_counts as $row) {
      $counts[$row['User']['status']]=$row[0]['cnt'];
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
   * add
   *
   */
  function add()
  {
    if (!$this->data) {
      # @user = User.new(:language => Setting.default_language)
    } else {
      # @user = User.new(params[:user])
      # @user.admin = params[:user][:admin] || false

      $this->data['User']['created_on'] = date('Y-m-d H:i:s'); // @todo model de yarubeki
      if ($this->User->save($this->data)) {
        #        Mailer.deliver_account_information(@user, params[:password]) if params[:send_information]
        $this->Session->setFlash(__('Successful creation.', true), 'default', array('class'=>'flash flash_notice'));
        $this->redirect('/users/index');
      }
    }
    $user = $this->User->create(array(
        'id' => '',
        'login' => '',
        'firstname' => '',
        'lastname' => '',
        'mail' => '',
    ));
    $this->set('user',$user);
    #    @auth_sources = AuthSource.find(:all)
  }
  function allowed_to() {
    if(empty($this->params['requested'])) {
      $this->cakeError('error404');
    }
    return $this->User->is_allowed_to($this->current_user, $this->params['aco'], $this->params['project']);
  }
}
