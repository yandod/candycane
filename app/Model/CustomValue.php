<?php

class CustomValue extends AppModel
{
  var $name = 'CustomValue';
  var $belongsTo = array(
    'CustomField' => array(
      'className' => 'CustomField',
      'foreignKey'=>'custom_field_id',
    ),
  );

  var $validate = array(
    'value' => array(
      'validates_presence_of'=>array('rule'=>array('notEmpty')),
      'validates_invalid_of'=>array('rule'=>array('validate_value_regexp')),
      'validates_inclusion_of'=>array('rule'=>array('validate_value_range')),
      'validates_format_of'=>array('rule'=>array('validate_value_format')),
    ),
  );

  function beforeSave($options = array()) {
    if (!empty($this->data[$this->name]['custom_field_id'])) {
      $this->CustomField->read(null, $this->data[$this->name]['custom_field_id']);
    } elseif (!empty($this->data['CustomField'])) {
      $this->CustomField->set($this->data['CustomField']);
    }
    return true;
  }

  function beforeValidate($options = array()) {
    if (!isset($this->data[$this->CustomField->alias])) {
        return true;
    }
    $custom_field = $this->data[$this->CustomField->alias];
    # Format specific validations
    switch ($custom_field['field_format']) {
    case 'int' :
      $message = 'is not a number';
      break;
    case 'float' :
      $message = 'is invalid';
      break;
    case 'date' :
      $message = 'is not a valid date';
      break;
    case 'list' :
      $message = 'is not included in the list';
      break;
    default :
      $message = '';
      break;
    }
    $this->validate['value']['validates_format_of']['message'] = $message;
    $this->validate['value']['validates_inclusion_of']['rule'][] = $custom_field['min_length'];
    $this->validate['value']['validates_inclusion_of']['rule'][] = $custom_field['max_length'];
    return true;
  }
  
  function validate_value_regexp($data) {
    if (isset($this->data[$this->name]['custom_field_id']) && !empty($this->data[$this->name]['custom_field_id'])) {
      $backup = $this->CustomField->data;

      $custom_field = $this->CustomField->read(null, $this->data[$this->name]['custom_field_id']);
      $custom_field = $custom_field['CustomField'];

      $this->CustomField->data = $backup;
      if (!empty($custom_field['regexp'])) {
        return preg_match("/{$custom_field['regexp']}/u", $this->data[$this->name]['value']);
      }
    }
    return true;
  }

  function validate_value_range($data) {
    if (!isset($this->data[$this->CustomField->alias])) {
        return true;
    }
    $custom_field = $this->data[$this->CustomField->alias];
    if ($custom_field['min_length'] > 0 and strlen($this->data[$this->name]['value']) < $custom_field['min_length']) {
      return false;
    }
    if ($custom_field['max_length'] > 0 and strlen($this->data[$this->name]['value']) > $custom_field['max_length']) {
      return false;
    }
    return true;
  }
  
  function validate_value_format($data) {
    $ret = true;
    if (!isset($this->data[$this->CustomField->alias])) {
        return true;
    }
    $custom_field = $this->data[$this->CustomField->alias];
    # Format specific validations
    switch ($custom_field['field_format']) {
    case 'int' :
      $ret = preg_match('/^[+-]?\d+$/', $this->data[$this->name]['value']);
      break;
    case 'float' :
      $ret = strval(floatval($this->data[$this->name]['value'])) === $this->data[$this->name]['value'];
      break;
    case 'date' :
      $ret = preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->data[$this->name]['value']);
      break;
    case 'list' :
      if (!empty($custom_field['possible_values']) && is_array($custom_field['possible_values'])) {
        $ret = in_array($this->data[$this->name]['value'], $custom_field['possible_values']);
      }
      break;
    }
    return $ret;
  }

}

#  def after_initialize
#    if custom_field && new_record? && (customized_type.blank? || (customized && customized.new_record?))
#      self.value ||= custom_field.default_value
#    end
#  end
#  
#  # Returns true if the boolean custom value is true
#  def true?
#    self.value == '1'
#  end
#  
