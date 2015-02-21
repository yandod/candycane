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
#class Journal < ActiveRecord::Base
#  belongs_to :journalized, :polymorphic => true
#  # added as a quick fix to allow eager loading of the polymorphic association
#  # since always associated to an issue, for now
#  belongs_to :issue, :foreign_key => :journalized_id
#  
#  belongs_to :user
#  has_many :details, :class_name => "JournalDetail", :dependent => :delete_all
#  attr_accessor :indice
#  
#  acts_as_event :title => Proc.new {|o| status = ((s = o.new_status) ? " (#{s})" : nil); "#{o.issue.tracker} ##{o.issue.id}#{status}: #{o.issue.subject}" },
#                :description => :notes,
#                :author => :user,
#                :type => Proc.new {|o| (s = o.new_status) ? (s.is_closed? ? 'issue-closed' : 'issue-edit') : 'issue-note' },
#                :url => Proc.new {|o| {:controller => 'issues', :action => 'show', :id => o.issue.id, :anchor => "change-#{o.id}"}}
#
#  acts_as_activity_provider :type => 'issues',
#                            :permission => :view_issues,
#                            :author_key => :user_id,
#                            :find_options => {:include => [{:issue => :project}, :details, :user],
#                                              :conditions => "#{Journal.table_name}.journalized_type = 'Issue' AND" +
#                                                             " (#{JournalDetail.table_name}.prop_key = 'status_id' OR #{Journal.table_name}.notes <> '')"}
#  
#  def save
#    # Do not save an empty journal
#    (details.empty? && notes.blank?) ? false : super
#  end
#  
#  # Returns the new status if the journal contains a status change, otherwise nil
#  def new_status
#    c = details.detect {|detail| detail.prop_key == 'status_id'}
#    (c && c.value) ? IssueStatus.find_by_id(c.value.to_i) : nil
#  end
#  
#  def new_value_for(prop)
#    c = details.detect {|detail| detail.prop_key == prop}
#    c ? c.value : nil
#  end
#  
#  def editable_by?(usr)
#    usr && usr.logged? && (usr.allowed_to?(:edit_issue_notes, project) || (self.user == usr && usr.allowed_to?(:edit_own_issue_notes, project)))
#  end
#  
#  def project
#    journalized.respond_to?(:project) ? journalized.project : nil
#  end
#  
#  def attachments
#    journalized.respond_to?(:attachments) ? journalized.attachments : nil
#  end
#end
class Journal extends AppModel {
  var $name="Journal";
  var $belongsTo = array('User', 'Issue'=>array('foreignKey'=>'journalized_id')); 
  var $hasMany = array('JournalDetail');
  var $available_custom_fields = array();

  var $actsAs = array(
    'ActivityProvider'=> array(
        'type'=>'issues',
        'permission'=>'view_issues',
        'author_key'=>'user_id',
        'find_options'=> array(
            'conditions'=>array('Journal.journalized_type'=>'Issue', 'or'=>array('JournalDetail.prop_key'=>'status_id','Journal.notes <>'=>'')),
            'fields'=> array('Journal.*', 'Issue.*', 'User.*', 'Project.*', 'Tracker.name'),
            // Convert include to manual joins.Becase CakePHP can not specify conditions by recursive over 2.
            // 'include'=>array('Issue'=>array('Project'), 'JournalDetail', 'User'),
            'recursive' =>-1,   // use manual join
            'joins' => array(
              array(
                'type'=>'LEFT',
                'table' => '', // set by construct
                'alias' => 'Issue',
                'conditions'=>'Issue.id=Journal.journalized_id',
              ),
              array(
                'type'=>'LEFT',
                'table' => '', // set by construct
                'alias' => 'Project',
                'conditions'=>'Project.id=Issue.project_id',
              ),
              array(
                'type'=>'LEFT',
                'table' => '', // set by construct
                'alias' => 'Tracker',
                'conditions'=>'Tracker.id=Issue.tracker_id',
              ),
              array(
                'type'=>'LEFT',
                'table' => '', // set by construct
                'alias' => 'JournalDetail',
                'conditions'=>'Journal.id=JournalDetail.journal_id',
              ),
              array(
                'type'=>'LEFT',
                'table' => '', // set by construct
                'alias' => 'User',
                'conditions'=>'User.id=Journal.user_id',
              ),
            ),
        ),
      ),
    'Event' => array('title'       => array('Proc' => '_event_title'),
                      'description' => 'notes',
                      'author'      => array('Proc' => '_event_author'),
                      'type'      => array('Proc' => '_event_type'),
                      'url'         => array('Proc' => '_event_url')),
  );
  
  function __construct($id = false, $table = null, $ds = null) {
    foreach($this->actsAs['ActivityProvider']['find_options']['joins'] as $index=>$join) {
      $this->actsAs['ActivityProvider']['find_options']['joins'][$index]['table'] = $this->tableName($join['alias']);
    }
    parent::__construct($id, $table, $ds);
		$this->Project = ClassRegistry::init('Project');
  }
  
  function _event_title($data) {
    $new_status = $this->__new_status($data);
    if(isset($status['Status']['name'])) {
      $new_status = $status['Status']['name'];
    } else {
      $new_status = '';
    }
    return $data['Tracker']['name'].' #'.$data['Issue']['id'].$new_status.': '.$data['Issue']['subject'];
  }
  function _event_author($data) {
    return $data['User'];
  }
  function _event_type($data) {
    $new_status = $this->__new_status($data);
    if(!empty($new_status)) {
      if($new_status['Status']['is_closed']) {
        return 'issue-closed';
      } else {
        return 'issue-edit';
      }
    }
    return 'issue-note';
  }
  function _event_url($data) {
    return  array(
      'controller' => 'issues',
      'action'     => 'show',
      '0'          => $data['Issue']['id'],
      '#'          => "change-".$data['Journal']['id']
    );
  }
  function __new_status($data) {
    $new_status = array();
    foreach($data['JournalDetail'] as $detail) {
      if($detail['prop_key'] == 'status_id') {
        $new_status = $this->Issue->Status->read(null, $detail['value']);
      }
    }
    return $new_status;
  }

  function is_editable_by($usr) {
    $this->Issue->Project->recursive = -1;
    $project = $this->Issue->Project->find('first', array(
    	'conditions' => array(
		'Project.id' => $this->data['Issue']['project_id']
		),
	'recursive' => 1));
    return $usr 
      && $usr['logged'] 
      && ($this->User->is_allowed_to($usr, ':edit_issue_notes', $project) 
          || ($this->data['User']['id'] == $usr['id'] 
            && $this->User->is_allowed_to($usr, ':edit_own_issue_notes', $project)
             )
         );
  }
  function saveAll($data = null, $options = array()) {
    if (empty($data)) {
      $data = $this->data;
    }
    # Do not save an empty journal
    return (empty($data['JournalDetail']) && empty($data['Journal']['notes'])) ? false : parent::saveAll($data, $options);
  }
}
