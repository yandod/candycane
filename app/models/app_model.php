<?php
class AppModel extends Model {
  /**
   * validation messages
   */
  static $error = array(
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
      if(array_key_exists($value, AppModel::$error)) {
        $error = vsprintf(__(AppModel::$error[$value],true), $rule);
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
}
?>