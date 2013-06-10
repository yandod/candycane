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
#class IssueStatus < ActiveRecord::Base
#  before_destroy :check_integrity  
#  has_many :workflows, :foreign_key => "old_status_id", :dependent => :delete_all
#  acts_as_list
#
#  validates_presence_of :name
#  validates_uniqueness_of :name
#  validates_length_of :name, :maximum => 30
#  validates_format_of :name, :with => /^[\w\s\'\-]*$/i
#
#  def after_save
#    IssueStatus.update_all("is_default=#{connection.quoted_false}", ['id <> ?', id]) if self.is_default?
#  end  
#  
#  # Returns the default status for new issues
#  def self.default
#    find(:first, :conditions =>["is_default=?", true])
#  end
#
#  # Returns an array of all statuses the given role can switch to
#  # Uses association cache when called more than one time
#  def new_statuses_allowed_to(role, tracker)
#    new_statuses = workflows.select {|w| w.role_id == role.id && w.tracker_id == tracker.id}.collect{|w| w.new_status} if role && tracker
#    new_statuses ? new_statuses.compact.sort{|x, y| x.position <=> y.position } : []
#  end
#  
#  # Same thing as above but uses a database query
#  # More efficient than the previous method if called just once
#  def find_new_statuses_allowed_to(role, tracker)  
#    new_statuses = workflows.find(:all, 
#                                   :include => :new_status,
#                                   :conditions => ["role_id=? and tracker_id=?", role.id, tracker.id]).collect{ |w| w.new_status }.compact  if role && tracker
#    new_statuses ? new_statuses.sort{|x, y| x.position <=> y.position } : []
#  end
#  
#  def new_status_allowed_to?(status, role, tracker)
#    status && role && tracker ?
#      !workflows.find(:first, :conditions => {:new_status_id => status.id, :role_id => role.id, :tracker_id => tracker.id}).nil? :
#      false
#  end
#
#  def <=>(status)
#    position <=> status.position
#  end
#  
#  def to_s; name end
#
#private
#  def check_integrity
#    raise "Can't delete status" if Issue.find(:first, :conditions => ["status_id=?", self.id])
#  end
#end
#

// 他に作っている人がいたら消していいです
class IssueStatus extends AppModel
{
    var $name = 'IssueStatus';
    var $actsAs = array('List');
    var $validate = array(
        'name' => array(
            'validates_presence_of'=>array('rule'=>array('notEmpty')),
            'validates_uniqueness_of'=>array('rule'=>array('isUnique')),
            'validates_length_of'=>array('rule'=>array('maxLength', 30)),
        ),
    );

    # Returns the default status for new issues
    function findDefault() {
        return $this->find('list', array('conditions' => array("is_default"=>true), 'limit'=>1));
    }

    /**
     * Same thing as above but uses a database query
     * More efficient than the previous method if called just once
     * @param default_status_id : [default_status] + default_status.find_new_statuses_allowed_to
     * @param role_id : current_user['memberships']['role_id']
     * @param tracker_id : selected tracker id
     */
    function find_new_statuses_allowed_to($default_status_id, $role_id, $tracker_id) {
        $workflow = & ClassRegistry::init('Workflow');
        $workflow->bindModel(array('belongsTo'=>array('Status'=>array('className'=>'IssueStatus', 'foreignKey'=>'new_status_id', 'order'=>'position'))), false);
        $conditions = array();
        if(!empty($role_id) && !empty($tracker_id)) {
            $conditions["old_status_id"] = $default_status_id;
            $conditions["role_id"] = $role_id;
            $conditions["tracker_id"] = $tracker_id;
        }
        $group = array('new_status_id', 'Status.id', 'Status.name');
        $fields = array('Status.id', 'Status.name', 'Workflow.new_status_id');
        $recursive = 0;

        $new_statuses = $workflow->find('all', compact('conditions', 'order', 'group', 'fields', 'recursive'));
        $list = array();
        foreach($new_statuses as $new_status) {
            $list[$new_status['Status']['id']] = $new_status['Status']['name'];
        }
        return $list;
    }

    function is_new_status_allowed_to($status_id, $role_id, $tracker_id) {
        $workflow = & ClassRegistry::init('Workflow');
        $workflow->bindModel(array('belongsTo'=>array('Status'=>array('className'=>'IssueStatus', 'foreignKey'=>'new_status_id', 'order'=>'position'))), false);

        if($status_id && $role_id && $tracker_id) {
            return $workflow->hasAny(array('new_status_id' => $status_id, 'role_id' => $role_id, 'tracker_id' => $tracker_id));
        }
        return false;
    }
    function beforeDelete($cascade = true) {
        return $this->check_integrity();
    }
    function afterSave($created) {
        if(!empty($this->data[$this->alias]['is_default'])) {
            if($created) {
                $id = $this->getLastInsertID();
            } else {
                $id = $this->id;
            }
            $this->updateAll(array('is_default'=>0), array($this->alias.'.id !='=>$id));
        }
    }

    #private
    function check_integrity() {
        $Issue =& ClassRegistry::init('Issue');
        return !$Issue->hasAny(array("status_id"=>$this->id));
    }

}

