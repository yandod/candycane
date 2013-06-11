<?php
class Issue extends AppModel
{
  var $name = 'Issue';
  var $actsAs = array(
    'ActivityProvider'=>
      array('find_options'=>
        array('include'=>array('Project', 
                                 'Author'=>array('className'=>'User','foreignKey'=>'author_id'),
                                 'Tracker')),
             'author_key'=>'author_id'
      ),
    'Watchable',
    'Customizable',
    'Attachable',
    'Event' => array('title' => array('Proc' => '_event_title'),
                      'url'   => array('Proc' => '_event_url')),
    'Searchable'
  );
  var $filterArgs = array(
    array('name' => 'subject', 'type' => 'like'),
    array('name' => 'description', 'type' => 'like'),
    //array('name' => 'Journal.notes', 'type' => 'like'),
  );
#  acts_as_attachable :after_remove => :attachment_removed
#  acts_as_customizable
#  acts_as_watchable
#  acts_as_searchable :columns => ['subject', "#{table_name}.description", "#{Journal.table_name}.notes"],
#                     :include => [:project, :journals],
#                     # sort by id so that limited eager loading doesn't break with postgresql
#                     :order_column => "#{table_name}.id"
#  acts_as_event :title => Proc.new {|o| "#{o.tracker.name} ##{o.id}: #{o.subject}"},
#                :url => Proc.new {|o| {:controller => 'issues', :action => 'show', :id => o.id}}                
#  
#  acts_as_activity_provider :find_options => {:include => [:project, :author, :tracker]},
#                            :author_key => :author_id
  
  var $belongsTo = array(
    'Project',
    'Tracker',
    'Status' => array(
      'className'  => 'IssueStatus',
      'foreignKey' => 'status_id',
    ),
    'Author' => array(
      'className'  => 'User',
      'foreignKey' => 'author_id',
    ),
    'AssignedTo' => array(
      'className'  => 'User',
      'foreignKey' => 'assigned_to_id',
    ),
    'FixedVersion' => array(
      'className'  => 'Version',
      'foreignKey' => 'fixed_version_id',
    ),
    'Priority' => array(
      'className'  => 'Enumeration',
      'foreignKey' => 'priority_id',
    ),
    'Category' => array(
      'className'  => 'IssueCategory',
      'foreignKey' => 'category_id',
    ),
  );
  
  var $Journal = false;
  var $attachJournalDetails = array();

#  has_many :journals, :as => :journalized, :dependent => :destroy
  var $hasMany = array(
    'TimeEntry'=>array('dependent'=>true),
  );
  var $hasAndBelongsToMany = array(
    'Changeset'=>array('order'=>"Changeset.committed_on ASC, Changeset.id ASC"),
  );
#  
#  has_many :relations_from, :class_name => 'IssueRelation', :foreign_key => 'issue_from_id', :dependent => :delete_all
#  has_many :relations_to, :class_name => 'IssueRelation', :foreign_key => 'issue_to_id', :dependent => :delete_all
#  
#  
  var $validate = array(
    'subject' => array(
      'validates_presence_of'=>array('rule'=>array('notEmpty')),
      'validates_length_of'=>array('rule'=>array('maxLength', 255)),
    ),
    'priority_id' => array(
      'validates_presence_of'=>array('rule'=>array('notEmpty')),
    ),
    'project_id' => array(
      'validates_presence_of'=>array('rule'=>array('notEmpty')),
    ),
    'tracker_id' => array(
      'validates_presence_of'=>array('rule'=>array('notEmpty')),
    ),
    'author_id' => array(
      'validates_presence_of'=>array('rule'=>array('notEmpty')),
    ),
    'status_id' => array(
      'validates_presence_of'=>array('rule'=>array('notEmpty')),
    ),
    'done_ratio' => array(
      'validates_inclusion_of'=>array('rule'=>array('range', -1, 101)),
    ),
    'estimated_hours' => array(
      'validates_numericality_of'=>array('rule'=>array('numeric'), 'allowEmpty'=>true),
    )
  );
#
#  def after_initialize
#    if new_record?
#      # set default values for new records only
#      self.status ||= IssueStatus.default
#      self.priority ||= Enumeration.default('IPRI')
#    end
#  end
#  
#  # Overrides Redmine::Acts::Customizable::InstanceMethods#available_custom_fields
#  def available_custom_fields
#    (project && tracker) ? project.all_issue_custom_fields.select {|c| tracker.custom_fields.include? c } : []
#  end
#  
  function copy_from($arg) {
    if(is_array($arg) && !empty($arg['Issue'])) {
      $issue = $arg;
    } elseif(is_string($arg)) {
      $issue = $this->find('first', array('conditions'=>array('id'=>$arg), 'recursive'=>-1));
    } else {
      $issue = false;
    }
    if(!empty($issue['Issue']['id'])) {
      unset($issue['Issue']['id']);
    }
    if(!empty($issue['CustomValue'])) {
      $issue['Issue']['custom_field_values'] = array();
      foreach($issue['CustomValue'] as $customValue) {
        $issue['Issue']['custom_field_values'][$customValue['custom_field_id']] = $customValue['value'];
      }
      unset($issue['CustomValue']);
    }
    return $issue;
  }
#  
#  # Move an issue to a new project and tracker
  function move_to($Setting, $issue, $project_id, $tracker_id=false) {
    $db =& ConnectionManager::getDataSource($this->useDbConfig);
    $db->begin($this);
    if(!empty($project_id) && $issue['Issue']['project_id'] != $project_id) {
      # delete issue relations (because moveing to difference project is denied by Setting)
      if(empty($Setting->cross_project_issue_relations)) {
        $IssueRelation = & ClassRegistry::init('IssueRelation');
        if(!$IssueRelation->deleteAll(array('or'=>
          array('issue_from_id'=>$issue['Issue']['id']),
          array('issue_to_id'=>$issue['Issue']['id'])
        ))) {
          $db->rollback($this);
          return false;
        }
      }
      # issue is moved to another project
      # reassign to the category with same name if any
      $new_category = empty($issue['Issue']['category_id']) ? null : $this->Category->find('first', array('conditions'=>array('Category.project_id'=>$project_id, 'Category.name'=>$issue['Category']['name'])));
      if(!empty($new_category)) {
        $issue['Issue']['category_id'] = $new_category['Category']['id'];
        $issue['Category'] = $new_category['Category'];
      }
      $issue['Issue']['fixed_version_id'] = null;
      $issue['Issue']['project_id'] = $project_id;
    }
    if(!empty($tracker_id)) {
      $issue['Issue']['tracker_id'] = $tracker_id;
    }
    if($this->save($issue)) {
      # Manually update project_id on related time entries
      $TimeEntry = & ClassRegistry::init('TimeEntry');
      if($TimeEntry->updateAll(array("project_id"=>$project_id), array('issue_id'=>$issue['Issue']['id']))) {
        $db->commit($this);
      } else {
        $db->rollback($this);
      }
    } else {
      $db->rollback($this);
    }
    return true;
  }
#  
#  def priority_id=(pid)
#    self.priority = nil
#    write_attribute(:priority_id, pid)
#  end
#  
  function estimated_hours() {
    if(array_key_exists('estimated_hours', $this->data[$this->name])) {
      $this->data[$this->name]['estimated_hours'] = $this->to_hours($this->data[$this->name]['estimated_hours']);
    }
  }
#  
  function validates() {
    // convert database format.
    $this->estimated_hours();

#    if self.due_date.nil? && @attributes['due_date'] && !@attributes['due_date'].empty?
#      errors.add :due_date, :activerecord_error_not_a_date
#    end
#    
#    if self.due_date and self.start_date and self.due_date < self.start_date
#      errors.add :due_date, :activerecord_error_greater_than_start_date
#    end
#    
#    if start_date && soonest_start && start_date < soonest_start
#      errors.add :start_date, :activerecord_error_invalid
#    end
    return parent::validates();
  }
#  
#  def validate_on_create
#    errors.add :tracker_id, :activerecord_error_invalid unless project.trackers.include?(tracker)
#  end
#  

#  def before_create => move before save

#  
#  def before_save  
#    if @current_journal
#      # attributes changes
#      (Issue.column_names - %w(id description)).each {|c|
#        @current_journal.details << JournalDetail.new(:property => 'attr',
#                                                      :prop_key => c,
#                                                      :old_value => @issue_before_change.send(c),
#                                                      :value => send(c)) unless send(c)==@issue_before_change.send(c)
#      }
#      # custom fields changes
#      custom_values.each {|c|
#        next if (@custom_values_before_change[c.custom_field_id]==c.value ||
#                  (@custom_values_before_change[c.custom_field_id].blank? && c.value.blank?))
#        @current_journal.details << JournalDetail.new(:property => 'cf', 
#                                                      :prop_key => c.custom_field_id,
#                                                      :old_value => @custom_values_before_change[c.custom_field_id],
#                                                      :value => c.value)
#      }      
#      @current_journal.save
#    end
#    # Save the issue even if the journal is not saved (because empty)
#    true
#  end
  function beforeSave($options = array()) {
    $result = parent::beforeSave($options);
    if(!$this->__exists) {
      if(empty($this->data['Issue']['assigned_to_id']) && !empty($this->data['Issue']['category_id'])) {
        $category = $this->Category->find('first', array('conditions'=>array('id'=>$this->data['Issue']['category_id']), 'recursive'=>-1));
        if(!empty($category['Category']['assigned_to_id'])) {
          $this->data['Issue']['assigned_to_id'] = $category['Category']['assigned_to_id'];
        }
      }
    }
    // empty must be null
    if(empty($this->data['Issue']['due_date'])) {
      unset($this->data['Issue']['due_date']);
    }
    if(empty($this->data['Issue']['start_date'])) {
      unset($this->data['Issue']['start_date']);
    }
    
    if($this->Journal) {
      # attributes changes
      $issue_column_names = array_keys($this->data['Issue']);
      $JournalDetail = & ClassRegistry::init('JournalDetail');
      $journalDetails = array();
      foreach ($this->data['Issue'] as $c=>$v) {
        if(in_array($c, array('id', 'description', 'created_on', 'updated_on', 'custom_field_values'))) {
          continue;
        }
        if (array_key_exists($c, $this->issue_before_change['Issue']) 
        && ($this->issue_before_change['Issue'][$c]==$v)) { 
          continue;
        }
        $detail = array('property' =>'attr', 'prop_key' =>$c, 'value'=>$v);
        if($this->data['Issue'][$c] != $this->issue_before_change['Issue'][$c]) {
          $detail['old_value'] = $this->issue_before_change['Issue'][$c];
        }
        $journalDetails[] = $detail;
      }
      # custom fields changes
      if(!empty($this->data[$this->alias]['custom_field_values'])) {
        foreach($this->data[$this->alias]['custom_field_values'] as $field_id => $field_value) {
          if (array_key_exists($field_id, $this->custom_values_before_change) 
          && ($this->custom_values_before_change[$field_id]==$field_value)) { 
            continue;
          }
          foreach($this->Journal->available_custom_fields as $custom_field) {
            if($custom_field['CustomField']['id'] == $field_id) {
              $journalDetails[] = array(
                'property' => 'cf', 
                'prop_key' => $field_id,
                'old_value'=> array_key_exists($field_id, $this->custom_values_before_change) ? $this->custom_values_before_change[$field_id] : null,
                'value' => $field_value
              );
              break;
            }
          }
        }
      }
      $journalDetails = array_merge($this->attachJournalDetails, $journalDetails);
      if(!empty($journalDetails)) {
        $this->Journal->set(array('JournalDetail' => $journalDetails));
        // Transaction already stated at IssueController#edit
        $result = $this->Journal->saveAll(null, array('atomic'=>false));
        $this->actually_changed = true;
      } elseif(!empty($this->Journal->data['Journal']['notes'])) {
        $result = $this->Journal->save();
        $this->actually_changed = true;
      }
    }
    return $result;
  }

  function afterSave($created) {
    parent::afterSave($created);
    # Reload is needed in order to get the right status
    if($created) {
      $id = $this->getLastInsertID();
    } else {
      $id = $this->id;
    }
    $issue = $this->find('first', array('conditions'=>array('Issue.id'=>$id)));
    
    # Update start/due dates of following issues
    $IssueRelation =& ClassRegistry::init('IssueRelation');
    $relations = $IssueRelation->find('list', array('conditions'=>array('issue_from_id'=>$issue['Issue']['id']), 'fields'=>array('id','id')));
    foreach($relations as $relation_id) {
      $IssueRelation->read(null, $relation_id);
      $IssueRelation->set_issue_to_dates();
    }
    
    # Close duplicates if the issue was closed
    if(!empty($this->issue_before_change['Status']) && !$this->issue_before_change['Status']['is_closed'] && $issue['Status']['is_closed']) {
      foreach($this->duplicates($issue) as $duplicate) {
        $Duplicate =& ClassRegistry::init('Issue');
        # Reload is need in case the duplicate was updated by a previous duplicate
        $Duplicate->read(null, $duplicate['IssueFrom']['id']);
        # Don't re-close it if it's already closed
        if($Duplicate->is_closed()) {
          continue;
        }
        # Same user and notes
        $Duplicate->init_journal($Duplicate->data, $this->current_journal_user, $this->current_journal_notes);
        $Duplicate->saveField('status_id', $issue['Issue']['status_id']);
      }
    }
  }
  
  var $issue_before_change = false;
  var $issue_before_change_status = false;
  var $custom_values_before_change = array();
  var $current_journal_user = false;
  var $current_journal_notes = false;
  var $actually_changed = false;
  /**
   * @param : $issue  ex.$issue['Issue']['id']
   * @param : $user   ex.$user['id']
   * @param : $notes is any string
   */
  function init_journal($issue, $user, $notes = "") {
    if(empty($this->Journal)) {
      $this->Journal = & ClassRegistry::init('Journal');
      $this->Journal->create();
      $defaults = array(
        'journalized_id'=>$issue['Issue']['id'], 
        'journalized_type'=>$this->name,
        'user_id'=>$user['id'], 
        'notes' => $notes);
      $this->Journal->set($defaults);
    }
    $this->current_journal_user = $user;
    $this->current_journal_notes = $notes;
    $this->issue_before_change = $issue;
    $this->issue_before_change_status = $issue['Issue']['status_id'];
    $this->custom_values_before_change = array();
    if(!empty($issue['CustomValue'])) {
      foreach($issue['CustomValue'] as $custom_value) {
        $this->custom_values_before_change[$custom_value['custom_field_id']] = $custom_value['value'];
      }
    }
#    updated_on_will_change!
    return $this->Journal;
  }
  function findValuesByJournalDetail($detail) {
    $label = '';
    $value = '';
    $old_value = '';
    $field_format = '';
    $attachment = false;

    switch($detail['property']) {
    case 'attr' :
      $options = array(
        'fields'=>array(),
        'conditions'=>array('id'=>0),
        'recursive'=>-1);
      foreach($this->belongsTo as $alias=>$assoc) {
        $options['fields'] = method_exists($this->$alias, 'name_fields') ? $this->$alias->name_fields() : array('name');
        if($detail['prop_key'] == $assoc['foreignKey']) {
          if(!empty($detail['value'])) {
            $options['conditions']['id'] = $detail['value'];
            $result = $this->$alias->find('first', $options);
            $value = method_exists($this->$alias, 'name') ? $this->$alias->name(array('User'=>$result[$alias])) : $result[$alias]['name'];
          }
          if(!empty($detail['old_value'])) {
            $options['conditions']['id'] = $detail['old_value'];
            $result = $this->$alias->find('first', $options);
            $old_value = method_exists($this->$alias, 'name') ? $this->$alias->name(array('User'=>$result[$alias])) : $result[$alias]['name'];
          }
          break;
        }
      }
      break;
    case 'cf' :
      $custom_field = $this->findCustomFieldById($detail['prop_key']);
      if(!empty($custom_field)) {
        $label = $custom_field['CustomField']['name'];
        $field_format = $custom_field['CustomField']['field_format'];
      }
      break;
    case 'attachment' :
      $attachment = $this->findAttachableById($detail['prop_key']);
      break;
    }
    return compact('label', 'value', 'old_value', 'field_format', 'attachment');
  }

  function findRssJournal() {
    $Journal = & ClassRegistry::init('Journal');
    $conditions = array('journalized_type'=>'Issue', 'journalized_id'=>$this->data['Issue']['id']);
    $journals = $Journal->find('all', array('conditions'=>$conditions, 'limit'=>25, 'recursive'=>1, 'order'=>'Journal.created_on DESC'));
    $journals = array_reverse($journals);
    return $journals;
  }
  function findAllJournal($current_user) {
    $Journal = & ClassRegistry::init('Journal');

    $conditions = array('journalized_type'=>'Issue', 'journalized_id'=>$this->data['Issue']['id']);

    $order = 'ASC';
    if (isset($current_user['UserPreference'])) {
      $user_pref = $current_user['UserPreference']['pref'];
      if (isset($user_pref['comments_sorting']) && $user_pref['comments_sorting'] === 'desc') {
        $order = 'DESC';
      }
    }

    $journal_list = $Journal->find('all', array(
      'conditions' => $conditions,
      'recursive'  => 1,
      'order'      => "Journal.created_on $order"
    ));
    if(!empty($journal_list) && !empty($current_user['wants_comments_in_reverse_order'])) {
      $journal_list = array_reverse($journal_list);
    }
    return $journal_list;
  }

  function findStatusList($role_for_project, $tracker_id = false) {
      if (!$tracker_id) {
          $tracker_id = $this->data['Issue']['tracker_id'];
      }

      $default_status = null;
      if (!$this->id) {
          $default_status = $this->Status->findDefault();
      } else {
          $default_status = $this->Status->find('list', array(
              'conditions' => array('id' => $this->data['Issue']['status_id']),
              'limit' => 1,
          ));
      }
      if (empty($default_status)) {
          return false;
      }

      $allowed_statuses = $this->Status->find_new_statuses_allowed_to(key($default_status), $role_for_project, $tracker_id);
      $statuses = $default_status;
      foreach ($allowed_statuses as $id => $value) {
          $statuses[$id] = $value;
      }
      ksort($statuses);

      return $statuses;
  }

  function findPriorities(&$default_set) {
    $priority_datas = $this->Priority->get_values('IPRI');
    $priorities = array();
    foreach($priority_datas as $priority) {
      $priorities[$priority['Priority']['id']] = $priority['Priority']['name'];
      if(empty($default_set) && $priority['Priority']['is_default']) {
        $default_set = $priority['Priority']['id'];
      }
    }
    return $priorities;
  }
  function findTimeEntryActivities() {
    $time_entry_activity_datas = $this->Priority->get_values('ACTI');
    $time_entry_activities = array();
    foreach($time_entry_activity_datas as $time_entry_activity) {
      $time_entry_activities[$time_entry_activity['Priority']['id']] = $time_entry_activity['Priority']['name'];
    }
    return $time_entry_activities;
  }
  function findProjectsTrackerList($project_id=false) {
    if(!$project_id) {
      $project_id = $this->data['Project']['id'];
    }
    $trackers = $this->Project->ProjectsTracker->find('list', array(
      'conditions'=>array('ProjectsTracker.project_id' => $project_id),
      'fields'=>'Tracker.id, Tracker.name', 
      'recursive'=>0, 
      'order'=>'Tracker.position'
    ));
    return $trackers;
  }

  # Return true if the issue is closed, otherwise false
  function is_closed($data = false) {
    if(!$data) {
      $data = $this->data;
    }
    return !empty($data['Status']['is_closed']);
  }
  
  # Returns true if the issue is overdue
  function is_overdue($data = false) {
    if(!$data) {
      $data = $this->data;
    }
    return !empty($data['Issue']['due_date']) && (strtotime($data['Issue']['due_date']) < mktime());
  }
#  
#  # Users the issue can be assigned to
#  def assignable_users
#    project.assignable_users
#  end
#  
# Returns an array of status that user is able to apply
#  def new_statuses_allowed_to(user)
#    statuses = status.find_new_statuses_allowed_to(user.role_for_project(project), tracker)
#    statuses << status unless statuses.empty?
#    statuses.uniq.sort
#  end
# 
  // Returns the mail adresses of users that should be notified for the issue
  function recipients() {
    $data = $this->read('project_id',$this->id);
    $this->Project->id = $data['Issue']['project_id'];
    $recipients = $this->Project->recipients();
    # Author and assignee are always notified unless they have been locked
    $data = $this->findById($this->id);
    $recipients[] = $data['Author']['mail'];
    $recipients[] = $data['AssignedTo']['mail'];
    return array_filter(array_unique($recipients)); 
  }
  
  function spent_hours() {
    // Move to IssuesHelper
  }
#  
#  def relations
#    (relations_from + relations_to).sort
#  end
#  
  function all_dependent_issues() {
    // Move to IssueRelation
    $this->cakeError('error404');
  }
#  
  # Returns an array of issues that duplicate this one
  function duplicates($data = false) {
    if(!$data) {
      $data = $this->data;
    }
    $IssueRelation =& ClassRegistry::init('IssueRelation');
    $conditions = array(
      'relation_type' => ISSUERELATION_TYPE_DUPLICATES,
      'issue_to_id' => $data['Issue']['id'],
    );
    $fields = array('IssueFrom.*');
    # relations_to.select {|r| r.relation_type == IssueRelation::TYPE_DUPLICATES}.collect {|r| r.issue_from}
    return $IssueRelation->find('all', compact('conditions', 'fields'));
  }
#  
#  # Returns the due date or the target due date if any
#  # Used on gantt chart
#  def due_before
#    due_date || (fixed_version ? fixed_version.effective_date : nil)
#  end
# 
  /**
   * @param : $issue['start_date']
   * @return : Unix timestamp
   * ex. $this->Issue->duration();
   * ex. $this->Issue->duration($issue['Issue']);
   */ 
  function duration($issue = false) {
    if(!$issue) {
      $issue = $this->data[$this->alias];
    }
    ($issue['start_date'] && $issue['due_date']) ? strtotime($issue['due_date']) - strtotime($issue['start_date']) : 0;
  }
#  
#  def soonest_start
#    @soonest_start ||= relations_to.collect{|relation| relation.successor_soonest_start}.compact.min
#  end
#  
#  def self.visible_by(usr)
#    with_scope(:find => { :conditions => Project.visible_by(usr) }) do
#      yield
#    end
#  end
#  
  function to_string($data=false) {
    if(!$data) {
      $data = $this->data;
    }
    return "{$data['Tracker']['name']} #{$data['Issue']['id']}: {$data['Issue']['subject']}";
  }
  
#  private

  function _event_title($data) {
     return $data['Tracker']['name'].' #'.$data['Issue']['id'].': '.$data['Issue']['subject'];
  }
  function _event_url($data) {
    return  array('controller'=>'issues', 'action'=>'show', $data['Issue']['id']);
  }

#  
#  # Callback on attachment deletion
#  def attachment_removed(obj)
#    journal = init_journal(User.current)
#    journal.details << JournalDetail.new(:property => 'attachment',
#                                         :prop_key => obj.id,
#                                         :old_value => obj.filename)
#    journal.save
#  end
#end
#
  public function project() {
	$project = $this->read('Project.*', $this->data['Issue']['id']);

  	return $project;
  }
}
