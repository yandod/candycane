<?php

class CustomField extends AppModel
{
#  has_many :custom_values, :dependent => :delete_all
  var $name = 'CustomField';
  var $actsAs = array('List');

  var $FIELD_FORMATS = array(
            "string" => array( 'name' => 'Text', 'order' => 1 ),
            "text" => array( 'name' => 'Long text', 'order' => 2 ),
            "int" => array( 'name' => 'Integer', 'order' => 3 ),
            "float" => array( 'name' => 'Float', 'order' => 4 ),
            "list" => array( 'name' => 'List', 'order' => 5 ),
			      "date" => array( 'name' => 'Date', 'order' => 6 ),
			      "bool" => array( 'name' => 'Boolean', 'order' => 7 )
  );

  function group_by($fields, $name) {
    $results = array();
    foreach($fields as $field) {
      $results[$field[$this->name][$name]][] = $field;
    }
    return $results;
  }
  function count_project(&$list) {
    if (!empty($list['IssueCustomField'])) {
      $this->bindModel(array('hasMany'=>array('CustomFieldsProject')), false);
      foreach($list['IssueCustomField'] as $i=>$field) {
        $conditions = array('custom_field_id'=>$field[$this->name]['id']);
        $list['IssueCustomField'][$i]['Project']['count_all'] = $this->CustomFieldsProject->find('count', array('conditions'=>$conditions));
      }
    }
  }
  var $__add_trackers = array();
  var $__del_trackers = array();
  function beforeSave($options = array()) {
    if ($this->data[$this->name]['type'] == 'IssueCustomField') {
      $assoc_trackers = Set::extract('{n}.CustomFieldsTracker.tracker_id', $this->CustomFieldsTracker->find('all', array('conditions'=>array('custom_field_id'=>$this->data['CustomField']['id']))));
      $this->__add_trackers = array_diff($this->data[$this->name]['tracker_id'], $assoc_trackers);
      $this->__del_trackers = array_diff($assoc_trackers, $this->data[$this->name]['tracker_id']);
    }
    unset($this->data[$this->name]['tracker_id']);

    App::Import('vendor', 'spyc');
    if (!empty($this->data[$this->name]['possible_values'])) {
      if (empty($this->data[$this->name]['possible_values'][count($this->data[$this->name]['possible_values'])-1])) {
        unset($this->data[$this->name]['possible_values'][count($this->data[$this->name]['possible_values'])-1]);
      }
      $this->data[$this->name]['possible_values'] = Spyc::YAMLDump($this->data[$this->name]['possible_values'], true);
    } else {
      $this->data[$this->name]['possible_values'] = '--- []';
    }
   
    return true;
  }
  function afterSave($created) {
    $id = $this->id;
    if ($created) {
      $id = $this->getLastInsertID();
    }
    $db =& ConnectionManager::getDataSource($this->useDbConfig);
    foreach ($this->__del_trackers as $del) {
      $this->CustomFieldsTracker->deleteAll(array('custom_field_id'=>$id, 'tracker_id'=>$del), false);
    }
    foreach ($this->__add_trackers as $add) {
      $db->create($this->CustomFieldsTracker, array('custom_field_id', 'tracker_id'), array($id, $add));
    }
  }
  
  
}

#  validates_presence_of :name, :field_format
#  validates_uniqueness_of :name, :scope => :type
#  validates_length_of :name, :maximum => 30
#  validates_format_of :name, :with => /^[\w\s\.\'\-]*$/i
#  validates_inclusion_of :field_format, :in => FIELD_FORMATS.keys
#
#  def initialize(attributes = nil)
#    super
#    self.possible_values ||= []
#  end
#  
#  def before_validation
#    # remove empty values
#    self.possible_values = self.possible_values.collect{|v| v unless v.empty?}.compact
#    # make sure these fields are not searchable
#    self.searchable = false if %w(int float date bool).include?(field_format)
#    true
#  end
#  
#  def validate
#    if self.field_format == "list"
#      errors.add(:possible_values, :activerecord_error_blank) if self.possible_values.nil? || self.possible_values.empty?
#      errors.add(:possible_values, :activerecord_error_invalid) unless self.possible_values.is_a? Array
#    end
#    
#    # validate default value
#    v = CustomValue.new(:custom_field => self.clone, :value => default_value, :customized => nil)
#    v.custom_field.is_required = false
#    errors.add(:default_value, :activerecord_error_invalid) unless v.valid?
#  end
#
#  def <=>(field)
#    position <=> field.position
#  end
#  
#  # to move in project_custom_field
#  def self.for_all
#    find(:all, :conditions => ["is_for_all=?", true], :order => 'position')
#  end
#  
#  def type_name
#    nil
#  end
#end

