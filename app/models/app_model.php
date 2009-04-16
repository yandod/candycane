<?php
class AppModel extends Model {
  /**
   * validation messages
   */
  var $error = array(
    'validates_presence_of' 	=> 'Please be sure to input.',
    'validates_uniqueness_of' => 'There are already exists.',
    'validates_length_of' => 'Please input by %2$d or less characters.',
    'validates_format_of' => 'Please input in readable charactors.',

    'date_format' => 'Please input in the date format.',
    'compare_from_to' => 'Start time should specify the past from finish time.',
    'minLength' => 'Please input by %2$d or more characters.',
    'email' => 'Please input in mail address form.',
    'harfWidthChar'=> 'Please input a half-width alphanumeric character.',
    'equalPasswords' => 'Invalid Password Confirmation.',
    'select' => 'Please be sure to select.',
    'requireParticipant' => 'Please select a participant.'
  );

  function invalidFields($options = array()) {
    $errors = parent::invalidFields($options);
    foreach($errors as $key => $value) {
      $model = false;
      if(is_array($value)) {
        $values = each($value);
        $model = $values['key'];
        $value = $values['value'];
      }
      $rule = array();
      if(!empty($this->validate[$key][$value]['rule'])) {
        $rule = $this->validate[$key][$value]['rule'];
      }
      if(array_key_exists($value, $this->error)) {
        $error = vsprintf(__($this->$error[$value],true), $rule);
      } else {
        $error = __($value,true);
      }
      if(!empty($model)) {
        $error = array($model=>$error);
      }
      $errors[$key] = $error;
    }
    $this->validationErrors = $errors;
    return $errors;
  }
  function beforeSave($options = array()) {
    $dateFields = array('updated_on');
    if (!$this->__exists) {
      $dateFields[] = 'created_on';
    }
    if (isset($this->data[$this->alias])) {
      $fields = array_keys($this->data[$this->alias]);
    }
    $db =& ConnectionManager::getDataSource($this->useDbConfig);
    foreach ($dateFields as $updateCol) {
      if ($this->hasField($updateCol) && !in_array($updateCol, $fields)) {
        $default = array('formatter' => 'date');
        $colType = array_merge($default, $db->columns[$this->getColumnType($updateCol)]);
        if (!array_key_exists('format', $colType)) {
          $time = strtotime('now');
        } else {
          $time = $colType['formatter']($colType['format']);
        }
        if (!empty($this->whitelist)) {
          $this->whitelist[] = $updateCol;
        }
        $this->set($updateCol, $time);
      }
    }
    return true;
  }
  
  
}

/*
class ARCondition
  attr_reader :conditions

  def initialize(condition=nil)
    @conditions = ['1=1']
    add(condition) if condition
  end

  def add(condition)
    if condition.is_a?(Array)
      @conditions.first << " AND (#{condition.first})"
      @conditions += condition[1..-1]
    elsif condition.is_a?(String)
      @conditions.first << " AND (#{condition})"
    else
      raise "Unsupported #{condition.class} condition: #{condition}"
    end
    self
  end

  def <<(condition)
    add(condition)
  end
end*/
?>