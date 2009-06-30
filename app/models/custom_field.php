<?php

class CustomField extends AppModel
{
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
}

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
#class CustomField < ActiveRecord::Base
#  has_many :custom_values, :dependent => :delete_all
#  acts_as_list :scope => 'type = \'#{self.class}\''
#  serialize :possible_values
#  
#  FIELD_FORMATS = { "string" => { :name => :label_string, :order => 1 },
#                    "text" => { :name => :label_text, :order => 2 },
#                    "int" => { :name => :label_integer, :order => 3 },
#                    "float" => { :name => :label_float, :order => 4 },
#                    "list" => { :name => :label_list, :order => 5 },
#			        "date" => { :name => :label_date, :order => 6 },
#			        "bool" => { :name => :label_boolean, :order => 7 }
#  }.freeze
#
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

