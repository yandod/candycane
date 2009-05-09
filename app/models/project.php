<?php
## redMine - project management software
## Copyright (C) 2006  Jean-Philippe Lang
##
## This program is free software; you can redistribute it and/or
## modify it under the terms of the GNU General Public License
## as published by the Free Software Foundation; either version 2
## of the License, or (at your option) any later version.
## 
## This program is distributed in the hope that it will be useful,
## but WITHOUT ANY WARRANTY; without even the implied warranty of
## MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
## GNU General Public License for more details.
## 
## You should have received a copy of the GNU General Public License
## along with this program; if not, write to the Free Software
## Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
#

define('PROJECT_STATUS_ACTIVE', 1);
define('PROJECT_ARCHIVED', 9);

class Project extends AppModel
{
  var $name = 'Project';
  var $actsAs = array(
      'ActivityProvider',
      'Event' => array('title' => array('Proc' => '_event_title'),
                      'url'   => array('Proc' => '_event_url')),
  );

//  var $belongsTo = array(
//    'Parent' => array(
//      'className'=>'Project',
//      'foreignKey'=>'parent_id',
//    ),
//  );
  var $hasMany = array(
    'Version',
    'TimeEntry',
    'IssueCategory',
    'EnabledModule',
  );
  var $hasAndBelongsToMany = array(
    'Tracker' => array(
      'with' => 'ProjectsTracker',
    ),
    'User' => array(
      'with' => 'Member',
    ),
  );
  function findById($id)
  {
    return $this->find('first', array('conditions'=>array($this->name.'.id'=>$id)));
  }

  function findByIdentifier($identifier)
  {
    return $this->find('first', array('conditions'=>array($this->name.'.identifier'=>$identifier)));
  }

  function findSubprojects($id)
  {
    return $this->find('all', array('conditions'=>array($this->name.'.parent_id'=>$id)));
  }

#  # Project statuses
#  STATUS_ACTIVE     = 1
#  STATUS_ARCHIVED   = 9
#  
#  has_many :members, :include => :user, :conditions => "#{User.table_name}.status=#{User::STATUS_ACTIVE}"
#  has_many :users, :through => :members
#  has_many :enabled_modules, :dependent => :delete_all
#  has_and_belongs_to_many :trackers, :order => "#{Tracker.table_name}.position"
#  has_many :issues, :dependent => :destroy, :order => "#{Issue.table_name}.created_on DESC", :include => [:status, :tracker]
#  has_many :issue_changes, :through => :issues, :source => :journals
#  has_many :versions, :dependent => :destroy, :order => "#{Version.table_name}.effective_date DESC, #{Version.table_name}.name DESC"
#  has_many :time_entries, :dependent => :delete_all
#  has_many :queries, :dependent => :delete_all
#  has_many :documents, :dependent => :destroy
#  has_many :news, :dependent => :delete_all, :include => :author
#  has_many :issue_categories, :dependent => :delete_all, :order => "#{IssueCategory.table_name}.name"
#  has_many :boards, :dependent => :destroy, :order => "position ASC"
#  has_one :repository, :dependent => :destroy
#  has_many :changesets, :through => :repository
#  has_one :wiki, :dependent => :destroy
#  # Custom field for the project issues
#  has_and_belongs_to_many :issue_custom_fields, 
#                          :class_name => 'IssueCustomField',
#                          :order => "#{CustomField.table_name}.position",
#                          :join_table => "#{table_name_prefix}custom_fields_projects#{table_name_suffix}",
#                          :association_foreign_key => 'custom_field_id'
#                          
#  acts_as_tree :order => "name", :counter_cache => true
#  acts_as_attachable :view_permission => :view_files,
#                     :delete_permission => :manage_files
#
#  acts_as_customizable
#  acts_as_searchable :columns => ['name', 'description'], :project_key => 'id', :permission => nil
#
#  attr_protected :status, :enabled_module_names
#  
#  validates_presence_of :name, :identifier
#  validates_uniqueness_of :name, :identifier
#  validates_associated :repository, :wiki
#  validates_length_of :name, :maximum => 30
#  validates_length_of :homepage, :maximum => 255
#  validates_length_of :identifier, :in => 2..20
#  validates_format_of :identifier, :with => /^[a-z0-9\-]*$/
#  
#  before_destroy :delete_all_members
#
#  named_scope :has_module, lambda { |mod| { :conditions => ["#{Project.table_name}.id IN (SELECT em.project_id FROM #{EnabledModule.table_name} em WHERE em.name=?)", mod.to_s] } }
#  
#  def identifier=(identifier)
#    super unless identifier_frozen?
#  end
#  
#  def identifier_frozen?
#    errors[:identifier].nil? && !(new_record? || identifier.blank?)
#  end
#  
#  def issues_with_subprojects(include_subprojects=false)
#    conditions = nil
#    if include_subprojects
#      ids = [id] + child_ids
#      conditions = ["#{Project.table_name}.id IN (#{ids.join(',')}) AND #{Project.visible_by}"]
#    end
#    conditions ||= ["#{Project.table_name}.id = ?", id]
#    # Quick and dirty fix for Rails 2 compatibility
#    Issue.send(:with_scope, :find => { :conditions => conditions }) do 
#      Version.send(:with_scope, :find => { :conditions => conditions }) do
#        yield
#      end
#    end 
#  end
#
#  # returns latest created projects
#  # non public projects will be returned only if user is a member of those
	function latest($user = array(), $count = 5)
	{
#  def self.latest(user=nil, count=5)
	return $this->find('all',aa('limit',$count));
#    find(:all, :limit => count, :conditions => visible_by(user), :order => "created_on DESC")	
#  end
	}	

#

  function visible_by($user = false) {
    if(empty($user)) {
      return $this->cakeError('error', "Argument Exception.");
    }
    if($user['admin']) {
      return array('Project.status'=>PROJECT_STATUS_ACTIVE);
    } elseif(!empty($user['memberships'])) {
      $allowed_project_ids = array();
      foreach($user['memberships'] as $member) {
        $allowed_project_ids[] = $member['Project']['id'];
      }
      return array('Project.status'=>PROJECT_STATUS_ACTIVE, array('or'=>array('Project.is_public'=>true), array('Project.id'=>$allowed_project_ids))); 
    } else {
      return array('Project.status'=>PROJECT_STATUS_ACTIVE, 'Project.is_public'=>true);
    }
  }  

  function get_visible_by_condition($user = null)
  {
    if ($user == null) {
      return array('status'=>PROJECT_STATUS_ACTIVE, 'is_public'=>true); // @TODO current取れる？
    }

    if ($user['admin']) {
      return array('status'=>PROJECT_STATUS_ACTIVE);
    } else {
      if (isset($user['memberships']) && (count($user['memberships']) > 0)) {
        $ids = array();
        foreach($user['memberships'] as $membership) {
          $ids[] = $membership['project_id'];
        }
        return array('status'=>PROJECT_STATUS_ACTIVE, 'or'=>array('is_public'=>true, 'id'=>$ids));
      } else {
        return array('status'=>PROJECT_STATUS_ACTIVE, 'is_public'=>true);
      }
    }
#    user ||= User.current
#    if user && user.admin?
#      return "#{Project.table_name}.status=#{Project::STATUS_ACTIVE}"
#    elsif user && user.memberships.any?
#      return "#{Project.table_name}.status=#{Project::STATUS_ACTIVE} AND (#{Project.table_name}.is_public = #{connection.quoted_true} or #{Project.table_name}.id IN (#{user.memberships.collect{|m| m.project_id}.join(',')}))"
#    else
#      return "#{Project.table_name}.status=#{Project::STATUS_ACTIVE} AND #{Project.table_name}.is_public = #{connection.quoted_true}"
#    end

  }

  /**
   * @param user : AppController->current_user
   *                  + admin
   *                  + logged
   *                  + memberships
   * @param permission  : 'view_issues'
   * @return find conditions
   */
  function allowed_to_condition($user, $permission, $options=array()) {
    $statements = array();
    $base_statement = array();
    $base_statement[] = array($this->alias.".status" => PROJECT_STATUS_ACTIVE);
    $exists_statement = false;
    $projectTable = $this->alias;
    $Permission = & ClassRegistry::init('Permission');
    $perm = $Permission->findByName($permission);
    if(!empty($perm['project_module'])) {
      # If the permission belongs to a project module, make sure the module is enabled
      $this->bindModel(array('hasMany' => array('EnabledModule')), false);
      $enabledModuleTable =  $this->EnabledModule->tablePrefix . $this->EnabledModule->table;
      $emName = $perm['project_module'];

      $base_statement[] = array("EXISTS (SELECT em.id FROM $enabledModuleTable em WHERE em.name='$emName' AND em.project_id=$projectTable.id)" => true);
    }
    if(isset($options['project'])) {
      $project_statement = array();
      $project_statement[] = array("$projectTable.id" => $options['project']['id']);
      if(isset($options['with_subprojects'])) {
        $project_statement[] = array("$projectTable.parent_id" => $options['project']['id']); 
        $project_statement = array('or'=> array($project_statement));
      }
      $base_statement = array('and' => array($project_statement, $base_statement));
    }
    if($user['admin']) {
      # no restriction
    } else {
      $role = & ClassRegistry::init('Role');
      $statements = array();
      $statements[] = array("1=0");
      if($user['logged']) {
        if($role->non_member_allowed_to($permission)) {
          $statements[] = array("$projectTable.is_public"=>1) ;
        }
        $allowed_project_ids = array();
        foreach($user['memberships'] as $member) {
          $allowed_project_ids[] = $member['Project'][0]['Project']['id'];
        }
        $statements[] = array("$projectTable.id" => $allowed_project_ids);
      } elseif($role->anonymous_allowed_to($permission)) {
        # anonymous user allowed on public project
        $statements[] = array("$projectTable.is_public"=>1);
      } else {
        # anonymous user is not authorized
      }
    }
    if(!empty($statements)) {
      $base_statement['or'] = array($statements);
    }
    return $base_statement;
  }
#  def self.allowed_to_condition(user, permission, options={})
#    statements = []
#    base_statement = "#{Project.table_name}.status=#{Project::STATUS_ACTIVE}"
#    if perm = Redmine::AccessControl.permission(permission)
#      unless perm.project_module.nil?
#        # If the permission belongs to a project module, make sure the module is enabled
#        base_statement << " AND EXISTS (SELECT em.id FROM #{EnabledModule.table_name} em WHERE em.name='#{perm.project_module}' AND em.project_id=#{Project.table_name}.id)"
#      end
#    end
#    if options[:project]
#      project_statement = "#{Project.table_name}.id = #{options[:project].id}"
#      project_statement << " OR #{Project.table_name}.parent_id = #{options[:project].id}" if options[:with_subprojects]
#      base_statement = "(#{project_statement}) AND (#{base_statement})"
#    end
#    if user.admin?
#      # no restriction
#    else
#      statements << "1=0"
#      if user.logged?
#        statements << "#{Project.table_name}.is_public = #{connection.quoted_true}" if Role.non_member.allowed_to?(permission)
#        allowed_project_ids = user.memberships.select {|m| m.role.allowed_to?(permission)}.collect {|m| m.project_id}
#        statements << "#{Project.table_name}.id IN (#{allowed_project_ids.join(',')})" if allowed_project_ids.any?
#      elsif Role.anonymous.allowed_to?(permission)
#        # anonymous user allowed on public project
#        statements << "#{Project.table_name}.is_public = #{connection.quoted_true}" 
#      else
#        # anonymous user is not authorized
#      end
#    end
#    statements.empty? ? base_statement : "((#{base_statement}) AND (#{statements.join(' OR ')}))"
#  end
#  
  function project_condition($with_subprojects)
  {
    $cond = array($this->name.'.id' => $this->id);
    if ($with_subprojects) {
      $temp = array('or' => $cond);
      $temp['or'][$this->name.'.parent_id'] = $this->id;
      $cond = $temp;
    }

    return $cond;
  }
#  def project_condition(with_subprojects)
#    cond = "#{Project.table_name}.id = #{id}"
#    cond = "(#{cond} OR #{Project.table_name}.parent_id = #{id})" if with_subprojects
#    cond
#  end
#  

    function find($conditions = null, $fields = array(), $order = null, $recursive = null)
    {
      if (is_string($conditions) && !preg_match('/^\d*$/', $conditions) &&
          $fields === array() && $order === null && $recursive === null) {
        // 1引数のみで、文字列っぽい場合はプロジェクトの識別子とみなす
        $obj = new Project;
        $project = $obj->findByIdentifier($conditions);
        return $project;
      } else {
        return parent::find($conditions, $fields, $order, $recursive);
      }
    }

#  def self.find(*args)
#    if args.first && args.first.is_a?(String) && !args.first.match(/^\d*$/)
#      project = find_by_identifier(*args)
#      raise ActiveRecord::RecordNotFound, "Couldn't find Project with identifier=#{args.first}" if project.nil?
#      project
#    else
#      super
#    end
#  end
# 
#  def to_param
#    # id is used for projects with a numeric identifier (compatibility)
#    @to_param ||= (identifier.to_s =~ %r{^\d*$} ? id : identifier)
#  end
#  
#  def active?
#    self.status == STATUS_ACTIVE
#  end
  function is_active($project) {
    return $project['Project']['status'] == PROJECT_STATUS_ACTIVE;
  }

#  
#  def archive
#    # Archive subprojects if any
#    children.each do |subproject|
#      subproject.archive
#    end
#    update_attribute :status, STATUS_ARCHIVED
#  end
#  
#  def unarchive
#    return false if parent && !parent.active?
#    update_attribute :status, STATUS_ACTIVE
#  end
#  
#  def active_children
#    children.select {|child| child.active?}
#  end
  /**
   * @param integer $project_id
   */
  function active_children($project_id)
  {
    $conditions = array(
      'parent_id' => $project_id,
       'status' => PROJECT_STATUS_ACTIVE,
    );
    $this->recursive = -1;
    $projects = $this->find('all', compact('conditions'));
    if (!$projects) {
      return $projects; 
    }

    $data = array();
    foreach ($projects as $v) {
      $data[] = array(
        'id' => $v['Project']['id'],
        'name' => $v['Project']['name'],
      );
    }

    return $data;
  }
#  
#  # Returns an array of the trackers used by the project and its sub projects
#  def rolled_up_trackers
#    @rolled_up_trackers ||=
#      Tracker.find(:all, :include => :projects,
#                         :select => "DISTINCT #{Tracker.table_name}.*",
#                         :conditions => ["#{Project.table_name}.id = ? OR #{Project.table_name}.parent_id = ?", id, id],
#                         :order => "#{Tracker.table_name}.position")
#  end
#  
#  # Deletes all project's members
#  def delete_all_members
#    Member.delete_all(['project_id = ?', id])
#  end
#  
#  # Users issues can be assigned to
#  def assignable_users
#    members.select {|m| m.role.assignable?}.collect {|m| m.user}.sort
#  end
  function assignable_users($project_id) {
    $conditions = array(
      'project_id' => $project_id,
      'Role.assignable' => 1,
    );
    $recursive = 1;
    $order = 'User.firstname';
    $fields = array('User.*');
    $users = $this->Member->find('all', compact('conditions', 'recursive', 'order', 'fields'));
    $list = array();
    foreach($users as $user) {
      $list[$user['User']['id']] = $user['User']['firstname'].' '.$user['User']['lastname'];
    }
    return $list;
  }
  function members($project_id) {
    $conditions = array(
      'project_id' => $project_id,
      'User.status' => 1
    );
    $order = 'User.firstname';
    $fields = array('User.*');
    $users = $this->Member->find('all', compact('conditions', 'order', 'fields'));
    $list = array();
    foreach($users as $user) {
      $list[$user['User']['id']] = $user['User']['firstname'].' '.$user['User']['lastname'];
    }
    return $list;
  }

#  
#  # Returns the mail adresses of users that should be always notified on project events
#  def recipients
#    members.select {|m| m.mail_notification? || m.user.mail_notification?}.collect {|m| m.user.mail}
#  end
#  
#  # Returns an array of all custom fields enabled for project issues
#  # (explictly associated custom fields and custom fields enabled for all projects)
#  def all_issue_custom_fields
#    @all_issue_custom_fields ||= (IssueCustomField.for_all + issue_custom_fields).uniq.sort
#  end
#  
#  def project
#    self
#  end
#  
#  def <=>(project)
#    name.downcase <=> project.name.downcase
#  end
#  
#  def to_s
#    name
#  end
#  

	/**
	 * Returns a short description of the projects (first lines)
	 * @param string  $description  Description
	 * @param integer $length short description length
	 * @return string
	 */
	function short_description($description, $length = 255)
	{
		$short_description = '';
		if ($description != '') {
			$short_description = preg_replace("/^(.{$length}[^\n]*).*$/um", "$1", $description);
		}
		return $short_description;
	}

  function afterFind($results, $primary = false)
  {
    if (isset($results['id'])) {
      $results = $this->afterFindOne($results);
    } else {
      foreach($results as $key=>$result) {
        if (isset($result[$this->alias][0])) {
          foreach($result[$this->alias] as $key2=>$version) {
            $results[$key][$this->alias][$key2] = $this->afterFindOne($version);
          }
        } else {
          $results[$key][$this->alias] = $this->afterFindOne($results[$key][$this->alias]);
        }
      }
    }

    return $results;
  }

  function afterFindOne($result)
  {
    if (empty($result)) { return $result; }
    if (isset($result['description'])) {
      $result['short_description'] = $this->short_description($result['description']);
    } else {
      $result['short_description'] = '';
    }
    if (!empty($result['identifier'])) {
      $result['identifier_or_id'] = $result['identifier'];
    } elseif(!empty($result['id'])) {
      $result['identifier_or_id'] = $result['id'];
    } else {
      $result['identifier_or_id'] = '';
    }
    $result['project_id'] = $result['identifier_or_id'];

    return $result;
  }

#  
#  def allows_to?(action)
#    if action.is_a? Hash
#      allowed_actions.include? "#{action[:controller]}/#{action[:action]}"
#    else
#      allowed_permissions.include? action
#    end
#  end
  function is_allows_to($action) {
    // TODO 
    return true;
  }

#  
#  def module_enabled?(module_name)
#    module_name = module_name.to_s
#    enabled_modules.detect {|m| m.name == module_name}
#  end
#  
#  def enabled_module_names=(module_names)
#    enabled_modules.clear
#    module_names = [] unless module_names && module_names.is_a?(Array)
#    module_names.each do |name|
#      enabled_modules << EnabledModule.new(:name => name.to_s)
#    end
#  end
#  
#  # Returns an auto-generated project identifier based on the last identifier used
#  def self.next_identifier
#    p = Project.find(:first, :order => 'created_on DESC')
#    p.nil? ? nil : p.identifier.to_s.succ
#  end
#
#protected
#  def validate
#    errors.add(parent_id, " must be a root project") if parent and parent.parent
#    errors.add_to_base("A project with subprojects can't be a subproject") if parent and children.size > 0
#    errors.add(:identifier, :activerecord_error_invalid) if !identifier.blank? && identifier.match(/^\d*$/)
#  end
#  
#private
#  acts_as_event :title => Proc.new {|o| "#{l(:label_project)}: #{o.name}"},
#                :url => Proc.new {|o| {:controller => 'projects', :action => 'show', :id => o.id}},
#                :author => nil
  function _event_title($data) {
     return __('Project',true).': '.$data['Project']['name'];
  }
  function _event_url($data) {
    return  array('controller'=>'projects', 'action'=>'show', 'id'=>$data['Project']['id']);
  }
#  def allowed_permissions
#    @allowed_permissions ||= begin
#      module_names = enabled_modules.collect {|m| m.name}
#      Redmine::AccessControl.modules_permissions(module_names).collect {|p| p.name}
#    end
#  end
#
#  def allowed_actions
#    @actions_allowed ||= allowed_permissions.inject([]) { |actions, permission| actions += Redmine::AccessControl.allowed_actions(permission) }.flatten
#  end
}
class ProjectsTracker extends AppModel {
  var $name = 'ProjectsTracker';
  var $cacheQueries = false;
  var $useTable = 'projects_trackers';
  var $belongsTo = array('Project', 'Tracker');
}
