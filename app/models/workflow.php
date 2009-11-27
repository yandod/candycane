<?php
class Workflow extends AppModel {
  var $name = 'Workflow';
  //  var $belongsTo = array('Role' => array('className' => 'Role',
  //                                         'foreignKey' => 'id'));

  
#  belongs_to :role
#  belongs_to :old_status, :class_name => 'IssueStatus', :foreign_key => 'old_status_id'
#  belongs_to :new_status, :class_name => 'IssueStatus', :foreign_key => 'new_status_id'
#
#  validates_presence_of :role, :old_status, :new_status
#  
#  # Returns workflow transitions count by tracker and role
#  def self.count_by_tracker_and_role
  function count_by_tracker_and_role() {
#    counts = connection.select_all("SELECT role_id, tracker_id, count(id) AS c FROM #{Workflow.table_name} GROUP BY role_id, tracker_id")
//		$counts = $this->query("SELECT role_id, tracker_id, count(id) AS c FROM workflows GROUP BY role_id, tracker_id");
    $counts = $this->find('all' ,  array('group' => 'role_id, tracker_id',
                                         'fields' => array('Workflow.role_id', 'Workflow.tracker_id', 'count(id) AS "Workflow__c"')));

    
#    roles = Role.find(:all, :order => 'builtin, position')

#    $roles = $this->Role->find('all', array('order' => array('builtin','position')));
#    pr($roles);
    
#    trackers = Tracker.find(:all, :order => 'position')
#    
#    result = []
#    trackers.each do |tracker|
#      t = []
#      roles.each do |role|
#        row = counts.detect {|c| c['role_id'] == role.id.to_s && c['tracker_id'] == tracker.id.to_s}
#        t << [role, (row.nil? ? 0 : row['c'].to_i)]
#      end
#      result << [tracker, t]
#    end
#    
#    result
#  end
#end
  }
}
?>