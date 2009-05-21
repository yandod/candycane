<?php
/**
 * user.php
 *
 */

// Account statuses
define('USER_STATUS_ANONYMOUS', 0);
define('USER_STATUS_ACTIVE',    1);
define('USER_STATUS_REGISTERED',2);
define('USER_STATUS_LOCKED',    3);

/**
 * User
 *
 */
class User extends AppModel
{

#  USER_FORMATS = {
#    :firstname_lastname => '#{firstname} #{lastname}',
#    :firstname => '#{firstname}',
#    :lastname_firstname => '#{lastname} #{firstname}',
#    :lastname_coma_firstname => '#{lastname}, #{firstname}',
#    :username => '#{login}'
#  }
#
#  has_many :memberships, :class_name => 'Member', :include => [ :project, :role ], :conditions => "#{Project.table_name}.status=#{Project::STATUS_ACTIVE}", :order => "#{Project.table_name}.name"
#  has_many :members, :dependent => :delete_all
#  has_many :projects, :through => :memberships
#  has_many :issue_categories, :foreign_key => 'assigned_to_id', :dependent => :nullify
#  has_many :changesets, :dependent => :nullify
#  has_one :preference, :dependent => :destroy, :class_name => 'UserPreference'
  var $hasOne = array(
    'UserPreference',
    'RssToken'=>array('className'=>'Token', 'dependent'=>true, 'conditions'=>"action='feeds'"),
  );
#  has_one :rss_token, :dependent => :destroy, :class_name => 'Token', :conditions => "action='feeds'"
#  belongs_to :auth_source
#  
#  # Active non-anonymous users scope
#  named_scope :active, :conditions => "#{User.table_name}.status = #{STATUS_ACTIVE}"
#  
#  acts_as_customizable
#  
#  attr_accessor :password, :password_confirmation
#  attr_accessor :last_before_login_on
#
#  # Prevents unauthorized assignments
#  attr_protected :login, :admin, :password, :password_confirmation, :hashed_password
#	
#  validates_presence_of :login, :firstname, :lastname, :mail, :if => Proc.new { |user| !user.is_a?(AnonymousUser) }
#  validates_uniqueness_of :login, :if => Proc.new { |user| !user.login.blank? }
#  validates_uniqueness_of :mail, :if => Proc.new { |user| !user.mail.blank? }
#  # Login must contain lettres, numbers, underscores only
#  validates_format_of :login, :with => /^[a-z0-9_\-@\.]*$/i
#  validates_length_of :login, :maximum => 30
#  validates_format_of :firstname, :lastname, :with => /^[\w\s\'\-\.]*$/i
#  validates_length_of :firstname, :lastname, :maximum => 30
#  validates_format_of :mail, :with => /^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i, :allow_nil => true
#  validates_length_of :mail, :maximum => 60, :allow_nil => true
#  validates_length_of :password, :minimum => 4, :allow_nil => true
#  validates_confirmation_of :password, :allow_nil => true
  
#  def reload(*args)
#    @name = nil
#    super
#  end
#  
  function name($user, $formatter = null)
  {
    // @FIXME

    if ($formatter != null) {
    } else {
    }

    return $user['User']['firstname']. ' '.$user['User']['lastname'];
  }
  function name_fields() {
    return array('firstname', 'lastname');
  }
#  # Return user's full name for display
#  def name(formatter = nil)
#    if formatter
#      eval('"' + (USER_FORMATS[formatter] || USER_FORMATS[:firstname_lastname]) + '"')
#    else
#      @name ||= eval('"' + (USER_FORMATS[Setting.user_format] || USER_FORMATS[:firstname_lastname]) + '"')
#    end
#  end
#  
#  def active?
#    self.status == STATUS_ACTIVE
#  end
#
#  def registered?
#    self.status == STATUS_REGISTERED
#  end
#    
#  def locked?
#    self.status == STATUS_LOCKED
#  end
#
#  def check_password?(clear_password)
#    User.hash_password(clear_password) == self.hashed_password
#  end
#  
#  def pref
#    self.preference ||= UserPreference.new(:user => self)
#  end
#  
#  def time_zone
#    @time_zone ||= (self.pref.time_zone.blank? ? nil : ActiveSupport::TimeZone[self.pref.time_zone])
#  end
#  
#  def wants_comments_in_reverse_order?
#    self.pref[:comments_sorting] == 'desc'
#  end
#  
#  # Return user's RSS key (a 40 chars long string), used to access feeds
  function rss_key($user_id) {
    $token = $this->RssToken->find('first', array('conditions'=>array('action'=>'feeds', 'user_id'=>$user_id), 'fields'=>array('value')));
    // TODO �Ȃ�������쐬����B
    return $token['RssToken']['value'];
  }
#  
#  # Return an array of project ids for which the user has explicitly turned mail notifications on
#  def notified_projects_ids
#    @notified_projects_ids ||= memberships.select {|m| m.mail_notification?}.collect(&:project_id)
#  end
#  
#  def notified_project_ids=(ids)
#    Member.update_all("mail_notification = #{connection.quoted_false}", ['user_id = ?', id])
#    Member.update_all("mail_notification = #{connection.quoted_true}", ['user_id = ? AND project_id IN (?)', id, ids]) if ids && !ids.empty?
#    @notified_projects_ids = nil
#    notified_projects_ids
#  end
#  
  function find_by_rss_key($key) {
    $token = $this->RssToken->find('first', array('conditions'=>array('value'=>$key)));
    return (!empty($token) && ($token['User']['status'] == USER_STATUS_ACTIVE)) ? $token['User'] : null;
  }
  function find_by_id_logged($id) {

    $cond = aa('User.id',$id);
    $user = $this->find('first',aa('recursive', 2,'conditions',$cond));
    if(empty($user)) {
      return false;
    }
    $user['User']['logged'] = true; // @todo fixme
    $user['User']['name'] = $user['User']['login']; // @todo fixme
    $user['User']['memberships'] = $user['Membership'];
    return $user['User'];
  }
#  
#  def self.find_by_autologin_key(key)
#    token = Token.find_by_action_and_value('autologin', key)
#    token && (token.created_on > Setting.autologin.to_i.day.ago) && token.user.active? ? token.user : nil
#  end
#  
#  # Makes find_by_mail case-insensitive
#  def self.find_by_mail(mail)
#    find(:first, :conditions => ["LOWER(mail) = ?", mail.to_s.downcase])
#  end
#
#  # Sort users by their display names
#  def <=>(user)
#    self.to_s.downcase <=> user.to_s.downcase
#  end
#  
  function to_string($data=false) {
    if(!$data) {
      $data = $this->data;
    }
    return $this->name($data);
  }
#  
#  def logged?
#    true
#  end
#  
#  def anonymous?
#    !logged?
#  end
#  
#  # Return user's role for project
#  def role_for_project(project)
#    # No role on archived projects
#    return nil unless project && project.active?
#    if logged?
#      # Find project membership
#      membership = memberships.detect {|m| m.project_id == project.id}
#      if membership
#        membership.role
#      else
#        @role_non_member ||= Role.non_member
#      end
#    else
#      @role_anonymous ||= Role.anonymous
#    end
#  end
#  
#  # Return true if the user is a member of project
#  def member_of?(project)
#    role_for_project(project).member?
#  end
#  
#  # Return true if the user is allowed to do the specified action on project
#  # action can be:
#  # * a parameter-like Hash (eg. :controller => 'projects', :action => 'edit')
#  # * a permission Symbol (eg. :edit_project)
#  def allowed_to?(action, project, options={})
#    if project
#      # No action allowed on archived projects
#      return false unless project.active?
#      # No action allowed on disabled modules
#      return false unless project.allows_to?(action)
#      # Admin users are authorized for anything else
#      return true if admin?
#      
#      role = role_for_project(project)
#      return false unless role
#      role.allowed_to?(action) && (project.is_public? || role.member?)
#      
#    elsif options[:global]
#      # authorize if user has at least one role that has this permission
#      roles = memberships.collect {|m| m.role}.uniq
#      roles.detect {|r| r.allowed_to?(action)} || (self.logged? ? Role.non_member.allowed_to?(action) : Role.anonymous.allowed_to?(action))
#    else
#      false
#    end
#  end
  /**
   * @param : $user is current_user. ex.$user['admin']
   * @param : $action is string.
   * @param : $project is Project data. ex.$project['Project']['status']
   */
  var $_map_role = array();
  function is_allowed_to($user, $action, $project, $options=array()) {
    if(!empty($project)) {
      $Project = & ClassRegistry::init('Project');
      # No action allowed on archived projects
      if(!$Project->is_active($project)) return false;
      # No action allowed on disabled modules
      if(!$Project->is_allows_to($action)) return false;
      # Admin users are authorized for anything else
      if($user['admin']) return true ;
      
      $role_id = $this->role_for_project($user, $project);
      if(empty($role_id)) return false;
      $Role = & ClassRegistry::init('Role');
      if(empty($this->_map_role[$role_id])) {
        $role = $Role->read(null, $role_id);
        $this->_map_role[$role_id] = $role;
      } else {
        $role = $this->_map_role[$role_id];
      }
      return $Role->is_allowed_to($role, $action) && ($project['Project']['is_public'] || $Role->is_member($role));
    } elseif(!empty($options['global'])) {
      # authorize if user has at least one role that has this permission
      $Role = & ClassRegistry::init('Role');
      $roles = $this->Membership->find('all', array('fields'=>array('Role.*'), 'group'=>'role_id'));
      foreach($roles as $role) {
        if($Role->is_allowed_to($role, $action)) return true;
      }
      return $user['logged'] ? $Role->non_member_allowed_to($action) : $Role->anonymous_allowed_to($action);
    }
    return false;
  }

#  
#  def self.current=(user)
#    @current_user = user
#  end
#  
#  def self.current
#    @current_user ||= User.anonymous
#  end
#  
#  def self.anonymous
#    anonymous_user = AnonymousUser.find(:first)
#    if anonymous_user.nil?
#      anonymous_user = AnonymousUser.create(:lastname => 'Anonymous', :firstname => '', :mail => '', :login => '', :status => 0)
#      raise 'Unable to create the anonymous user.' if anonymous_user.new_record?
#    end
#    anonymous_user
#  end
#end


  var $hasMany = array(
    'Membership' =>array(
      'className' => 'Member'
      )
  );

  /**
   * no implement:
   * validates_presence_of :login, :firstname, :lastname, :mail, :if => Proc.new { |user| !user.is_a?(AnonymousUser) }
   * validates_uniqueness_of :login, :if => Proc.new { |user| !user.login.blank? }
   * validates_uniqueness_of :mail, :if => Proc.new { |user| !user.mail.blank? }
   * # Login must contain lettres, numbers, underscores only
   * validates_length_of :login, :maximum => 30
   * validates_length_of :firstname, :lastname, :maximum => 30
   * validates_length_of :mail, :maximum => 60, :allow_nil => true
   * validates_length_of :password, :minimum => 4, :allow_nil => true
   * validates_confirmation_of :password, :allow_nil => true
   *
   * implemented:
   * validates_format_of :login, :with => /^[a-z0-9_\-@\.]*$/i
   * validates_format_of :firstname, :lastname, :with => /^[\w\s\'\-\.]*$/i
   * validates_format_of :mail, :with => /^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i, :allow_nil => true
   */
  var $validate = array(
    'login' => array(
      'validates_uniqueness_of' => array(
        'rule' => 'isUnique',
        'onCreate' => true,
        'allowEmpty' => false,
      )
    ),
    'firstname' => array(
      'validates_not_empty' => array(
        'rule' => array('notEmpty')
      ),
    ),
    'lastname' => array(
      'validates_not_empty' => array(
        'rule' => array('notEmpty')
      ),
    ),
    'mail' => array(
      'validates_invalid_of' => array(
        'rule' => array('email', false),
      ),
      'validates_not_empty' => array(
        'rule' => array('notEmpty'),
      )
    ),
    'username' => array(
      'rule' => 'alphaNumeric',
      'allowEmpty' => false
    ),
    'password' => array(
      'minLength' => array(
        'rule' => array('minLength',4)
      ),
    ),
  );

  /**
   * beforeCreate
   *
   */
  function beforeCreate()
  {
    $this->data['User']['mail_notification'] = 0;
    return true;
  }

  /**
   * beforeSave
   *
   */
  function beforeSave()
  {
    if (!empty($this->data['User']['password'])) {
      $this->data['User']['hashed_password'] = $this->hash_password($this->data['User']['password']);
    }

    return true;
  }

  /**
   * hash_password
   *
   * @access private
   */
  function hash_password($password = '')
  {
    return sha1($password);
  }

  /**
   * tryToLogin
   *
   * Returns the user that matches provided login and password, or nil
   *
   * @todo implement auth_source
   */
  function tryToLogin($login, $password)
  {
    // Make sure no one can sign in with an empty password
    if (strlen($password) <= 0) {
      return false;
    }

    $user = $this->findByLogin($login);
    $user = $user['User'];

    if (is_array($user)) {
      // user is already in local database
      # return nil if !user.active?
      if ($user['status'] != 1) {
        return false;
      }

      if (isset($user['auth_source']) && $user['auth_source']) {
        // user has an external authentication method
        # return nil unless user.auth_source.authenticate(login, password)
        //return false;
      } else {
        // authentication with local password
        # return nil unless User.hash_password(password) == user.hashed_password        
        if (Security::hash($password) != $user['hashed_password']) {
          return false;
        }
      }
    } else {
      // user is not yet registered, try to authenticate with available sources
      #      attrs = AuthSource.authenticate(login, password)
      #      if attrs
      #        user = new(*attrs)
      #        user.login = login
      #        user.language = Setting.default_language
      #        if user.save
      #          user.reload
      #          logger.info("User '#{user.login}' created from the LDAP") if logger
      #        end
      #      end
    }

    // user.update_attribute(:last_login_on, Time.now) if user && !user.new_record?
    if ($user) {
      $this->updateAttribute($user, date('Y-m-d H:i:s',time()));
    }

    return $user;
  }

  /**
   * updateAttribute
   *
   */
  function updateAttribute($user, $last_login_on)
  {
    $user['last_login_on'] = $last_login_on;
    $this->save($user);
  }

  /** 
   * Return user's role for project
   */
  function role_for_project($user, $project) {
    $role_id = false;
    $role = & ClassRegistry::init('Role');
    if(!empty($user)) {
      # Find project membership
      $no_member_role = $role->non_member();
      $role_id = $no_member_role['Role']['id'];
      if(!empty($user['memberships'])) {
        foreach($user['memberships'] as $membership) {
          if($membership['project_id'] == $project['Project']['id']) {
            $role_id = $membership['role_id'];
            break;
          }
        }
      }
    } else {
      $anonymous_member_role = $role->anonymous();
      $role_id = $anonymous_member_role['Role']['id'];
    }
    return $role_id;
  }
  
  #  def active?
  function is_active($id=false) {
    if(!$id) {
      return $this->data['User']['status'] == USER_STATUS_ACTIVE;
    } else {
      return $this->hasAny(array('User.id'=>$id, 'User.status'=>USER_STATUS_ACTIVE));
    }
  }

}

/**
 * AnonymousUser
 *
 */
class AnonymousUser extends User
{
#  def validate_on_create
#    # There should be only one AnonymousUser in the database
#    errors.add_to_base 'An anonymous user already exists.' if AnonymousUser.find(:first)
#  end
#  
#  def available_custom_fields
#    []
#  end
#  
#  # Overrides a few properties
#  def logged?; false end
#  def admin; false end
#  def name; 'Anonymous' end
#  def mail; nil end
#  def time_zone; nil end
#  def rss_key; nil end
}

