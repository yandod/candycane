<?php
App::Import('vendor', 'georgious-cakephp-yaml-migrations-and-fixtures/spyc/spyc');

class Role extends AppModel {
  var $name = 'Role';
  var $actsAs = array('List');

#  # Built-in roles
#  BUILTIN_NON_MEMBER = 1
#  BUILTIN_ANONYMOUS  = 2
  var $BUILTIN_NON_MEMBER = 1;
  var $BUILTIN_ANONYMOUS  = 2;
  //  var $validate = array('assignable' => array('required' => array('rule' => VALID_NOT_EMPTY)));

#
#  named_scope :builtin, lambda { |*args|
#    compare = 'not' if args.first == true
#    { :conditions => "#{compare} builtin = 0" }
#  }
#  
#  before_destroy :check_deletable
#  has_many :workflows, :dependent => :delete_all do
#    def copy(role)
#      raise "Can not copy workflow from a #{role.class}" unless role.is_a?(Role)
#      raise "Can not copy workflow from/to an unsaved role" if proxy_owner.new_record? || role.new_record?
#      clear
#      connection.insert "INSERT INTO #{Workflow.table_name} (tracker_id, old_status_id, new_status_id, role_id)" +
#                        " SELECT tracker_id, old_status_id, new_status_id, #{proxy_owner.id}" +
#                        " FROM #{Workflow.table_name}" +
#                        " WHERE role_id = #{role.id}"
#    end
#  end
#  
#  has_many :members
#  acts_as_list
#  
#  serialize :permissions, Array
#  attr_protected :builtin
#
#  validates_presence_of :name
#  validates_uniqueness_of :name
#  validates_length_of :name, :maximum => 30
#  validates_format_of :name, :with => /^[\w\s\'\-]*$/i
  var $validate = array('name' => array('validates_uniqueness_of' => array('rule' => array('isUnique')),
                                        'validates_length_of' => array('rule' => array('maxLength', 30)),
                                        'validates_format_of' => array('rule' => array('custom', '/^[\w\s\'\-]*$/i')),
                                        ));

#
#  def permissions
  function permissions($permissions) {
    $yaml = Spyc::YAMLLoad($permissions);
    $unsymboled = array();
    foreach ($yaml as $val) {
      $unsymboled[] = substr($val,1);
    }
    return $unsymboled;
#    read_attribute(:permissions) || []
#  end
  }

#  
#  def permissions=(perms)
#    perms = perms.collect {|p| p.to_sym unless p.blank? }.compact.uniq if perms
#    write_attribute(:permissions, perms)
#  end
#
#  def add_permission!(*perms)
#    self.permissions = [] unless permissions.is_a?(Array)
#
#    permissions_will_change!
#    perms.each do |p|
#      p = p.to_sym
#      permissions << p unless permissions.include?(p)
#    end
#    save!
#  end
#
#  def remove_permission!(*perms)
#    return unless permissions.is_a?(Array)
#    permissions_will_change!
#    perms.each { |p| permissions.delete(p.to_sym) }
#    save!
#  end
#  
#  # Returns true if the role has the given permission
#  def has_permission?(perm)
#    !permissions.nil? && permissions.include?(perm.to_sym)
#  end
#  
#  def <=>(role)
#    position <=> role.position
#  end
#  
#  # Return true if the role is a builtin role
#  def builtin?
#    self.builtin != 0
#  end
#  
  # Return true if the role is a project member role
  function is_member($role) {
    return !$role['Role']['builtin'];
  }
  function non_member_allowed_to($permission) {
    $non_member = $this->non_member();
    if(empty($non_member)) {
      $this->cakeError('error', array('message'=>'Missing non-member builtin role.'));
    }
    return $this->is_allowed_to($non_member, $permission);
  }
  function anonymous_allowed_to($permission) {
    $anonymous = $this->anonymous();
    if(empty($anonymous)) {
      $this->cakeError('error', array('message'=>'Missing non-member builtin role.'));
    }
    return $this->is_allowed_to($anonymous, $permission);
  }
  # Return true if role is allowed to do the specified action
  # action can be:
  # * a parameter-like Hash (eg. :controller => 'projects', :action => 'edit')
  # * a permission Symbol (eg. :edit_project)
  function is_allowed_to($role, $action) {
    if(is_array($action)) {
      $include = in_array("{$action['controller']}/{$action['action']}", $this->_allowed_actions($role));
      return $include;
    }
    $list = $this->_allowed_permissions($role);
    if(!empty($list)) {
      foreach($list as $item) {
        if(($item == $action) || (':' . $item == $action)) {
          return true;
        }
      }
    }
    return false;
  }
  
# Return all the permissions that can be given to the role
#  def setable_permissions
#  Move to Permission.php

#  # Find all the roles that can be given to a project member
  function find_all_givable()
  {
//    find(:all, :conditions => {:builtin => 0}, :order => 'position')
    return $this->find('all',array(
		'conditions' => array(
			'builtin' => 0
		),
		'order' => 'position')
	);
  }
#
#  # Return the builtin 'non member' role
#  def self.non_member
  function non_member() {
#    find(:first, :conditions => {:builtin => BUILTIN_NON_MEMBER}) || raise('Missing non-member builtin role.')
    $data = $this->find('first', array('conditions' => array('builtin' => $this->BUILTIN_NON_MEMBER)));
    if (empty($data)) {
    } else {
      return $data;
    }
  }
#
#  # Return the builtin 'anonymous' role 
#  def self.anonymous
  function anonymous() {
#    find(:first, :conditions => {:builtin => BUILTIN_ANONYMOUS}) || raise('Missing anonymous builtin role.')
    $data = $this->find('first', array('conditions' => array('builtin' => $this->BUILTIN_ANONYMOUS)));
    if (empty($data)) {
    } else {
      return $data;
    }
  }
#
#  
#private
  function _allowed_permissions($role) {
    $Permission =& ClassRegistry::init('Permission');
    $names = array();
    $publics = $Permission->public_permissions();
    foreach ($publics as $perms) {
      $names = array_merge($names, array_keys($perms));
    }
    $list = array_merge($this->permissions($role['Role']['permissions']), $names);
    
    return $list;
  }

  function _allowed_actions($role = false) {
    if (!$role) {
      $role = $this->data;
    }
    $Permission =& ClassRegistry::init('Permission');
    $actions = array();
    foreach($this->_allowed_permissions($role) as $permission) {
      $actions[] = $Permission->allowed_actions($permission);
    }
    return Set::flatten($actions);
  }
#    
#  def check_deletable
#    raise "Can't delete role" if members.any?
#    raise "Can't delete builtin role" if builtin?
#  end
#end
  function array2yaml($array) {
    $yaml = Spyc::YAMLDump($array);
    return $yaml;
  }

  function toSymbol($data) {
    $tmp = array();
    if (! is_array($data)) {
      return $tmp;
    }
    foreach ($data as $d) {
      $tmp[] = ':' . $d;
    }
    return $tmp;
  }

  function convert_permissions($permissions_array) {
    $tmp = $this->toSymbol($permissions_array);
    $tmp = $this->array2yaml($tmp);
    $permissions_yaml = $tmp;
    return $permissions_yaml;
  }
  
}
