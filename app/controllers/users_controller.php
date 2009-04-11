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
  var $helpers = array('Users', 'Sort', 'Ajax');
  var $components = array('Sort');
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
    $this->require_admin();
  }

  /**
   * index
   *
   */
  function index()
  {
    $this->list_();
    $this->render('list'); // unless request.xhr?
  }

#  def edit
#    @user = User.find(params[:id])
#    if request.post?
#      @user.admin = params[:user][:admin] if params[:user][:admin]
#      @user.login = params[:user][:login] if params[:user][:login]
#      @user.password, @user.password_confirmation = params[:password], params[:password_confirmation] unless params[:password].nil? or params[:password].empty? or @user.auth_source_id
#      if @user.update_attributes(params[:user])
#        flash[:notice] = l(:notice_successful_update)
#        # Give a string to redirect_to otherwise it would use status param as the response code
#        redirect_to(url_for(:action => 'list', :status => params[:status], :page => params[:page]))
#      end
#    end
#    @auth_sources = AuthSource.find(:all)
#    @roles = Role.find_all_givable
#    @projects = Project.find(:all, :order => 'name', :conditions => "status=#{Project::STATUS_ACTIVE}") - @user.projects
#    @membership ||= Member.new
#    @memberships = @user.memberships
#  end
#  
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
   * @todo list is reserved word
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

    #    c = ARCondition.new(@status == 0 ? "status <> 0" : ["status = ?", @status])
    #
    #    unless params[:name].blank?
    #      name = "%#{params[:name].strip.downcase}%"
    #      c << ["LOWER(login) LIKE ? OR LOWER(firstname) LIKE ? OR LOWER(lastname) LIKE ?", name, name, name]
    #    end
    #    
    #    @user_count = User.count(:conditions => c.conditions)
    #    @user_pages = Paginator.new self, @user_count,
    #								per_page_option,
    #								params['page']								

    #    @users =  User.find :all,:order => sort_clause,
    #                        :conditions => c.conditions,
    #                        :limit  =>  @user_pages.items_per_page,
    #                        :offset =>  @user_pages.current.offset
    $users = $this->User->find('all');
    $this->set('user_list', $users);

    $status_counts = $this->User->find('all',
      array(
        'group' => array('status'),
        'fields' => array('COUNT(*) as cnt'),
      )
    );

    foreach (array(1, 2, 3) as $key) {
      if (!isset($status_counts[$key][0]['cnt'])) {
        $status_counts[$key][0]['cnt'] = 0;
      }
    }

    $status_option = array(
      '' => __('label_all', true),
      1  => __('status_active', true) . ' (' . (int)$status_counts[1][0]['cnt'] . ')',
      2  => __('status_registered', true) . ' (' . (int)$status_counts[2][0]['cnt'] . ')',
      3  => __('status_locked', true) . ' (' . (int)$status_counts[3][0]['cnt'] . ')',
    );

    $this->set('status_option', $status_option);

    if (isset($request->xhr)) {
      $this->layout = false;
    }

    return 'list';
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

      if ($this->User->save($this->data)) {
        #        Mailer.deliver_account_information(@user, params[:password]) if params[:send_information]
        #        flash[:notice] = l(:notice_successful_create)
        #        redirect_to :action => 'list'
        $this->redirect('/users/index');
      }
    }
    #    @auth_sources = AuthSource.find(:all)
  }
}
