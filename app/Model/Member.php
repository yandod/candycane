<?php

#class Member < ActiveRecord::Base
#
#  validates_presence_of :role, :user, :project
#  validates_uniqueness_of :user_id, :scope => :project_id
#
#  def validate
#    errors.add :role_id, :activerecord_error_invalid if role && !role.member?
#  end
#  
#  def name
#    self.user.name
#  end
#  
#  def <=>(member)
#    role == member.role ? (user <=> member.user) : (role <=> member.role)
#  end
#  
#  def before_destroy
#    # remove category based auto assignments for this member
#    IssueCategory.update_all "assigned_to_id = NULL", ["project_id = ? AND assigned_to_id = ?", project.id, user.id]
#  end
#end
class Member extends AppModel
{
    var $belongsTo = array('Project', 'Role', 'User');
}
