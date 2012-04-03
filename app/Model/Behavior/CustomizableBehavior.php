<?php
/*
  Customizable Behavior
*/
class CustomizableBehavior extends ModelBehavior {
  // array (example)
  // array[{n}]['CustomValue'][{fieldname}]
  var $custom_field_values = null;
  var $available_custom_fields = array();
  
  function setup(&$Model, $config = array()) {
    $settings = $config;
    $this->settings[$Model->alias] = $settings;
    return true;
  }

  /**
   * Check between Model->data[modelname][custom_field_values][] and Database values
   */
  function beforeValidate(&$Model){
    if(empty($this->available_custom_fields[$Model->alias])) {
      return true;
    }
    $fields = $this->available_custom_fields[$Model->alias];
    foreach($fields as $field) {
      if(!empty($Model->data[$Model->alias]['custom_field_values']) && array_key_exists($field['CustomField']['id'], $Model->data[$Model->alias]['custom_field_values'])){
        $data = $Model->data[$Model->alias]['custom_field_values'][$field['CustomField']['id']];
      } else {
        $data = '';
      }
      $message = '';
      $regex = false;
      if($field['CustomField']['is_required']) {
        $regex = '/[^\s]+/m';
        $message = 'validates_presence_of';
      }
      if(!$this->_check($regex, $data)) {
        $Model->validationErrors[$field['CustomField']['name']] = $message;
      } elseif(!empty($field['CustomField']['regexp'])) {
        $regex = '/'.$field['CustomField']['regexp'].'/um';
        $message = 'validates_invalid_of';
        if(!$this->_check($regex, $data)) {
          $Model->validationErrors[$field['CustomField']['name']] = $message;
        }
      }
      if(($field['CustomField']['field_format']  == 'list') && !empty($field['CustomField']['possible_values']) && !empty($data)) {
        App::Import('vendor', 'georgious-cakephp-yaml-migrations-and-fixtures/spyc/spyc');
        $list = Spyc::YAMLLoad($field['CustomField']['possible_values']);
        $options = array();
        if(!empty($list)) {
          foreach($list as $item) {
            if(is_array($item)) {
              $item = $item[0];
            }
            $options[$item] = $item;
          }
        }
        if(!in_array($data, $options)) {
          $Model->validationErrors[$field['CustomField']['name']] = $message;
        }
      }
    }
    // continue main model validates...
    return true;
  }
  function _check($regex, $data) {
    if(empty($regex)) {
      return true;
    }
    if (preg_match($regex, $data)) {
      return true;
    } else {
      return false;
    }
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
    if(isset($Model->_customFieldAfterFindDisable)) {
      return $results;
    }
    $single = false;
    if(empty($results[0])) {
      $results = array($results);
      $single = true;
    }
    if(is_array($results)) {
      foreach($results as $index => $result) {
        if(!empty($result[$Model->name]) && !empty($result[$Model->name]['id'])) {
          $customValueModel = & ClassRegistry::init('CustomValue');
          $conditions = array('customized_type'=> $Model->name, 'customized_id'=>$result[$Model->name]['id']);
          $order = 'CustomField.position';
          $values = $customValueModel->find('all', compact('conditions', 'order'));
          if(!empty($values)) {
            $results[$index]['CustomValue'] = array();
            foreach($values as $value) {
              $value['CustomValue']['CustomField'] = $value['CustomField'];
              $results[$index]['CustomValue'][] = $value['CustomValue'];
            }
          }
        }
      }
    }
    if($single) {
      $results = $results[0];
    }
    return $results;
  }
  
  function cached_available_custom_fields(&$Model) {
    return $this->available_custom_fields[$Model->alias];
  }
  /**
   * Get available field values 
   */
  function available_custom_fields(&$Model, $project_id=false, $tracker_id=false) {
    $customValueModel = & ClassRegistry::init('CustomValue');
    $is_for_all = true;
    if(isset($this->settings[$Model->alias]['is_for_all'])) { 
      $is_for_all = $this->settings[$Model->alias]['is_for_all'];
    }
    $for_alls = $customValueModel->CustomField->find('all', 
        array('conditions' => array('type'=> $Model->name.'CustomField', 'is_for_all'=>$is_for_all), 'order'=>'position'));
    if(!empty($project_id)) {
      $CustomFieldsProject = & ClassRegistry::init('CustomFieldsProject');
      $for_projects = $CustomFieldsProject->find('all', 
        array('conditions' => array('CustomField.type'=> $Model->name.'CustomField', 'project_id'=>$project_id), 'order'=>'CustomField.position'));
    } else {
      $for_projects = array();
    }
    $availables = array();
    $result = array();
    foreach($for_alls as $for_all) {
      $availables[$for_all['CustomField']['position']] = $for_all;
    }
    foreach($for_projects as $for_project) {
      $availables[$for_project['CustomField']['position']] = $for_project;
    }
    if(!empty($tracker_id) && !empty($availables)) {
      $ids = array();
      foreach($availables as $available) {
        $ids[] = $available['CustomField']['id'];
      }
      $CustomFieldsTracker = & ClassRegistry::init('CustomFieldsTracker');
      $for_tracker_ids = $CustomFieldsTracker->find('all', array(
        'conditions' => array('CustomField.type'=> $Model->name.'CustomField', 'tracker_id'=>$tracker_id, 'CustomField.id'=>$ids), 
        'fields'=>array('CustomField.position'),
        'order'=>"CustomField.position"
      ));
      if(!empty($for_tracker_ids)) {
        $result = array();
        foreach($for_tracker_ids as $for_tracker_id)
          $result[] = $availables[$for_tracker_id['CustomField']['position']];
      }
    }
    if(empty($result)) {
      $result = $availables;
    }
    $this->available_custom_fields[$Model->alias] = $result;
    return $this->available_custom_fields[$Model->alias];
  }
  function findCustomFieldById(&$Model, $id) {
    $CustomField = & ClassRegistry::init('CustomField');
    return $CustomField->read(null, $id); 
  }
  /**
   * Filter only available fields.
   * @note : Before call this function, must be call available_custom_fields.
   */
  function filterCustomFieldValue(&$Model, $values) {
    $result = array();
    foreach($this->available_custom_fields[$Model->alias] as $available) {
      $id = $available['CustomField']['id'];
      if(array_key_exists($id, $values)) {
        $result[$id] = $values[$id];
      }
    }
    return $result;
  }
  function custom_value_for(&$Model, $custom_field, $data=false) {
    if(!$data) {
      $data = $Model->data;
    }
    $field_id = $custom_field['CustomField']['id'];
    if(!empty($data['CustomValue'])) {
      foreach($data['CustomValue'] as $value) {
        if($value['custom_field_id'] == $field_id) {
          return $value;
        }
      }
    }
    return false;
  }
  function custom_field_type_name($Model) {
    return $Model->name.'CustomField';
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
        } else {
          $conditions = $custom_value['CustomValue'];
          unset($conditions['value']);
          $exists = $customValueModel->find('first', array('conditions'=>$conditions, 'fields'=>array('id')));
          if(!empty($exists)) {
            $custom_value['CustomValue']['id'] = $exists['CustomValue']['id'];
            $customValueModel->id = $exists['CustomValue']['id'];
          }
        }
        $customValueModel->save($custom_value);
      }
    }
    $this->custom_field_values = null;
  }
}

?>
