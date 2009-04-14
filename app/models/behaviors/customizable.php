<?php
/*
  Customizable Behavior
*/
class CustomizableBehavior extends ModelBehavior {
  // array (example)
  // array[{n}]['CustomValue'][{fieldname}]
  var $custom_field_values = null;
  
  function setup(&$Model, $config = array()) {
    $settings = $config;
    $this->settings[$Model->alias] = $settings;
    return true;
  }

  /**
   * Check between Model->data[modelname][custom_field_values][] and Database values
   */
  function beforeValidate(&$Model){
    return true;
  }
  /**
   * Create save data
   * Create data from Model->data[modelname][custom_field_values] 
   */
  function beforeSave(&$Model) {
    $this->custom_field_values = $this->_create_save_data($Model);
    return true;
  }
  /**
   * Save custom field values after Main Model
   */
  function afterSave(&$Model, $created) {
    return $this->_save_custom_field_values($Model, $created);
  }

  /**
   * Add relation of CustomValues
   */
  function afterFind(&$Model, $results, $primary = false) {
    if(!empty($results[$Model->name])) {
      $customValueModel = & ClassRegistry::init('CustomValue');
      $conditions = array('type'=> $Model->name, 'customized_id'=>$results[$Model->name]['id']);
      $order = 'CustomField.position';
      $values = $customValueModel->find('all', compact('conditions', 'order'));
      if(!empty($values)) {
        $results['CustomValue'] = array();
        foreach($values as $value) {
          $results['CustomValue'][] = $value['CustomValue'];

        }
      }
    }
    return $results;
  }
  
  /**
   * Get available field values 
   */
  function available_custom_fields(&$Model) {
    $customValueModel = & ClassRegistry::init('CustomValue');
    return $customValueModel->CustomField->find('all', 
        array('conditions' => array('type'=> $Model->name.'CustomField'), 'order'=>'position'));
  }

   // ==== privates 

  function _create_save_data(&$Model) {
    $data = array();
    if(!empty($Model->data[$Model->name]['custom_field_values'])) {
      // Create save data from POST data.
      foreach($Model->data[$Model->name]['custom_field_values'] as $key => $input) {
        $data[] = array('CustomValue' => array(
          'customized_type' => $Model->name,
          'customized_id' => $Model->id,
          'custom_field_id' => $key,
          'value' => $input
        ));
      }
    }
    return $data;
  }

  function _save_custom_field_values(&$Model, $created) {
    if($created) {
      $insertId = $Model->getLastInsertID();
    }
    $customValueModel = & ClassRegistry::init('CustomValue');
    if(!empty($this->custom_field_values)) {
      foreach($this->custom_field_values as $custom_value) {
        $customValueModel->create();
        if($created) {
          $custom_value['CustomValue']['customized_id'] = $insertId;
        }
        $customValueModel->save($custom_value);
      }
    }
    $this->custom_field_values = null;
  }
}

?>
