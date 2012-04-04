<?php
#class IssueCustomField < CustomField
#  has_and_belongs_to_many :projects, :join_table => "#{table_name_prefix}custom_fields_projects#{table_name_suffix}", :foreign_key => "custom_field_id"
#  has_and_belongs_to_many :trackers, :join_table => "#{table_name_prefix}custom_fields_trackers#{table_name_suffix}", :foreign_key => "custom_field_id"
#  has_many :issues, :through => :issue_custom_values
#    
#  def type_name
#    :label_issue_plural
#  end
#end
#
#

App::uses('CustomField', 'Model');

class IssueCustomField extends CustomField
{
  var $name = 'IssueCustomField';
  var $alias = 'IssueCustomField';
  var $useTable = 'custom_fields';
  var $belongsTo = array();
  var $hasMany = array();
  var $hasAndBelongsToMany = array();

  function beforeFind($queryData)
  {
    $ret = parent::beforeFind($queryData);
    if (is_array($ret)) {
      $queryData = $ret;
    }

    if ($queryData['conditions'] == null) {
      $queryData['conditions'] = array();
    }
    $queryData['conditions'][$this->name.'.type'] = $this->name;

    return $queryData;
  }

}

