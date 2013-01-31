<?php
/* vim: fenc=utf8 ff=unix
 *
 *
 */

class CustomFieldHelper extends AppHelper
{
  var $helpers = array(
    'Candy',
    'Form'
  );

  function show_value($custom_value)
  {
    if (empty($custom_value)) { return ""; }

    $value = $this->format_value($custom_value['CustomValue']['value'], $custom_value['CustomValue']['CustomField']['field_format']);
    return $value;
  }

  var $validationErrors = array();
  function beforeRender() {
    $models = ClassRegistry::keys();
    foreach ($models as $currentModel) {
      if (ClassRegistry::isKeySet($currentModel)) {
        $currentObject =& ClassRegistry::getObject($currentModel);
        if (is_a($currentObject, 'Model') && !empty($currentObject->validationErrors)) {
          $this->validationErrors[Inflector::camelize($currentModel)] =& $currentObject->validationErrors;
        }
      }
    }
  }

  function custom_fields_tabs() {
    $tabs = array(
      array('name' => 'IssueCustomField', 'label' => __('Issues')),
      array('name' => 'TimeEntryCustomField', 'label' => __('Spent time')),
      array('name' => 'ProjectCustomField', 'label' => __('Projects')),
      array('name' => 'UserCustomField', 'label' => __('Users'))
    );
    return $tabs;
  }
  
  function type_name($field) {
    foreach ($this->custom_fields_tabs() as $tab) {
      if ($tab['name'] == $field['CustomField']['type']) {
        return $tab['label'];
      }
    }
    return '';
  }

  # Return custom field html tag corresponding to its format
  function custom_field_tag($name, $custom_value) {	
    $custom_field = $custom_value['CustomField'];
    $field_name = 'custom_field_values.'.$custom_value['CustomField']['id'];

    switch($custom_field['field_format']) {
    case "date" :
      $out = $this->Form->input($field_name, array('type'=>'text', 'size'=>10, 'label'=>false, 'div'=>false)); 
      // TODO : calender 
      // $out .= calendar_for(field_id)
      break;
    case "text" :
      $out = $this->Form->input($field_name, array('type'=>'textarea', 'rows'=>3, 'style'=> 'width:90%', 'label'=>false, 'div'=>false ));
      break;
    case "bool" :
      $out = $this->Form->input($field_name, array('type'=>'checkbox', 'label'=>false, 'div'=>false));
      break;
    case "list" :
      $empty = true;
      $selected = null;
      $type = 'select';
      if($custom_field['is_required']) {
        $empty = false;
        if(empty($custom_field['default_value'])) {
          $options[] = '--- '.__('Please Select').' ---';
        } elseif(empty($this->request->data['custom_field_values'][$custom_value['CustomField']['id']])) {
          $selected = $custom_field['default_value'];
        }
      }
      App::Import('vendor', 'georgious-cakephp-yaml-migrations-and-fixtures/spyc/spyc');
      $list = Spyc::YAMLLoad($custom_value['CustomField']['possible_values']);
      $options = array();
      if(!empty($list)) {
        foreach($list as $item) {
          $options[$item] = $item;
        }
      }
      
      $out = $this->Form->input($field_name, array_merge(compact('type', 'empty', 'selected', 'options'), array('label'=>false, 'div'=>false)));
      break;
    default :
      $out = $this->Form->input($field_name, array('type'=>'text', 'label'=>false, 'div'=>false));
      break;
    }
    return $out;
  }
  # Return custom field label tag
  function custom_field_label_tag($name, $custom_value) {
    $out = sprintf('<label for="%s" class="%s">%s', 
      $name.'_custom_field_values_'.$custom_value['CustomField']['id'],
      empty($this->validationErrors[Inflector::camelize($name)]['custom_field_values'][$custom_value['CustomField']['id']]) ? "" : "error",
      $custom_value['CustomField']['name']
    );
    if($custom_value['CustomField']['is_required']) {
      $out .= " <span class=\"required\">*</span>";
    }
    $out .= "</label>";
    return $out;
  }
  # Return custom field tag with its label tag
  function custom_field_tag_with_label($name, $custom_value) {
    return $this->custom_field_label_tag($name, $custom_value).$this->custom_field_tag($name, $custom_value);
  }
  function default_value_tag($custom_field) {
    $type = 'text';
    if(!empty($custom_field['CustomField']['field_format']) && $custom_field['CustomField']['field_format'] == 'bool') {
      $type = 'checkbox';
    }
    return $this->Form->input('default_value', array('type'=>$type, 'div'=>false, 'label'=>false));
  }

/*
  # Return a string used to display a custom value
  def format_value(value, field_format)
    return "" unless value && !value.empty?
    case field_format
    when "date"
      begin; format_date(value.to_date); rescue; value end
    when "bool"
      l_YesNo(value == "1")
    else
      value
    end
  end
*/

  # Return an array of custom field formats which can be used in select_tag
  function custom_field_formats_for_select() {
    $model = ClassRegistry::init('CustomField');
    $formats = $model->FIELD_FORMATS;
    uasort($formats, array($this, '__sort_custom_field_formats_for_select'));
    $select = array();
    foreach ($formats as $k=>$format) {
      $select[$k] = __($format['name']);
    }
    return $select;
  }
  function __sort_custom_field_formats_for_select($a, $b) {
    return $a['order'] - $b['order'];
  }
  
  function custom_field_possible_values_for_select($field) {
    if (!empty($this->request->data['CustomField']['possible_values']) && is_array($this->request->data['CustomField']['possible_values'])) {
      return $this->request->data['CustomField']['possible_values'];
    }
    App::Import('vendor', 'georgious-cakephp-yaml-migrations-and-fixtures/spyc/spyc');
    $list = !empty($field['CustomField']['possible_values']) ? Spyc::YAMLLoad($field['CustomField']['possible_values']) : array();
    $options = array();
    if(!empty($list)) {
      foreach($list as $item) {
        if(is_array($item)) {
          $item = $item[0];
        }
        $options[$item] = $item;
      }
    }
    $options[] = '';
    return array_values($options);
  }
  
  function format_value($value, $field_format) {
    switch($field_format) {
    case "date" :
      $out = $this->Candy->format_date($value);
      break;
    case "bool" :
      if(empty($value)) {
        $out = __('No');
      } else {
        $out = __('Yes');
      }
      break;
    case "text" :
    case "list" :
    default :
      $out = $value;
      break;
    }
    return $out;
  }

  function value($custom_value) {	
    $custom_field = $custom_value['CustomField'];
    return $this->format_value($custom_value['value'], $custom_field['field_format']);
  }
  function field_value($custom_field_id, $custom_values) {
    $value = '';
    foreach($custom_values as $custom_value) {
      if($custom_value['custom_field_id'] == $custom_field_id) {
        $value = $this->value($custom_value);
        break;
      }
    }
    return $value;
  }
  
  function sort_custom_fields_by_type($custom_fields_by_type, $tab) {
    $target = array();
    if (!empty($custom_fields_by_type[$tab['name']])) {
      $target = $custom_fields_by_type[$tab['name']];
    }
    usort($target, array($this, '__sort_custom_fields_by_type'));
    return $target;
  }
  function __sort_custom_fields_by_type($a, $b) {
    return $a['CustomField']['position'] - $b['CustomField']['position'];
  }
  
  function field_format($field_format, $name) {
    $model = ClassRegistry::init('CustomField');
    return $model->FIELD_FORMATS[$field_format][$name];
  }
  function custom_fields_tracker_selected($custom_field) {
    $selected = array();
    if (!empty($custom_field['CustomFieldsTracker'])) {
      foreach ($custom_field['CustomFieldsTracker'] as $customFieldsTracker) {
        $selected[] = $customFieldsTracker['tracker_id'];
      }
    }
    return $selected;
  }
}

