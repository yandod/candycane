<?php
class Tracker extends AppModel
{
  var $name = 'Tracker';
  var $actsAs = array('List');
  var $hasMany = array(
    'Issue',
    'Workflow' => array(
      'dependent' => true
    )
  );

  var $validate = array(
    'name' => array(
      'rule' => array('maxLength',30),
      'required' => true,
      'allowEmpty' => false
    )
  );
#class Tracker < ActiveRecord::Base
#  before_destroy :check_integrity  
#  has_many :issues
#  has_many :workflows, :dependent => :delete_all do

  function workflow_copy($tracker){
#      raise "Can not copy workflow from a #{tracker.class}" unless tracker.is_a?(Tracker)
#      raise "Can not copy workflow from/to an unsaved tracker" if proxy_owner.new_record? || tracker.new_record?
#      clear
    $param = array(
      'conditions' => array(
        'tracker_id' => $tracker
      )
    );
    $workflows = $this->Workflow->find('all',$param);
    foreach ($workflows as $workflow) {
      $workflow['Workflow']['id'] = null;
      $workflow['Workflow']['tracker_id'] = $this->id;
      $this->Workflow->create();
      $this->Workflow->save($workflow);
    }
#      connection.insert "INSERT INTO #{Workflow.table_name} (tracker_id, old_status_id, new_status_id, role_id)" +
#                        " SELECT #{proxy_owner.id}, old_status_id, new_status_id, role_id" +
#                        " FROM #{Workflow.table_name}" +
#                        " WHERE tracker_id = #{tracker.id}"
#    end
#  end
  }
#  has_and_belongs_to_many :projects
#  has_and_belongs_to_many :custom_fields, :class_name => 'IssueCustomField', :join_table => "#{table_name_prefix}custom_fields_trackers#{table_name_suffix}", :association_foreign_key => 'custom_field_id'
#  acts_as_list
#
#  validates_presence_of :name
#  validates_uniqueness_of :name
#  validates_length_of :name, :maximum => 30
#  validates_format_of :name, :with => /^[\w\s\'\-]*$/i
#
#  def to_s; name end
#  
#  def <=>(tracker)
#    name <=> tracker.name
#  end
#
#  def self.all
#    find(:all, :order => 'position')
#  end
#  
#private
#  def check_integrity
#    raise "Can't delete tracker" if Issue.find(:first, :conditions => ["tracker_id=?", self.id])
#  end
#end
}