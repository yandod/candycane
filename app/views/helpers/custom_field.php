<?php
/* vim: fenc=utf8 ff=unix
 *
 *
 */

class CustomFieldHelper extends AppHelper
{
  var $helpers = array(
    'Candy'
  );

  function show_value($value)
  {
    if (empty($value)) { return ""; }

    // @FIXME
    // format_value(custom_value.value, custom_value.custom_field.field_format)
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
/*
  def custom_fields_tabs
    tabs = [{:name => 'IssueCustomField', :label => :label_issue_plural},
            {:name => 'TimeEntryCustomField', :label => :label_spent_time},
            {:name => 'ProjectCustomField', :label => :label_project_plural},
            {:name => 'UserCustomField', :label => :label_user_plural}
            ]
  end
*/
  # Return custom field html tag corresponding to its format
  function custom_field_tag($formHelper, $name, $custom_value) {	
    $custom_field = $custom_value['CustomField'];
    $field_name = 'custom_field_values.'.$custom_value['CustomField']['id'];

    switch($custom_field['field_format']) {
    case "date" :
      $out = $formHelper->input($field_name, array('type'=>'text', 'size'=>10, 'label'=>false, 'div'=>false)); 
      // TODO : calender 
      // $out .= calendar_for(field_id)
      break;
    case "text" :
      $out = $formHelper->input($field_name, array('type'=>'textarea', 'rows'=>3, 'style'=> 'width:90%', 'label'=>false, 'div'=>false ));
      break;
    case "bool" :
      $out = $formHelper->input($field_name, array('type'=>'checkbox', 'label'=>false, 'div'=>false));
      break;
    case "list" :
      $empty = true;
      $selected = null;
      $type = 'select';
      if($custom_field['is_required']) {
        $empty = false;
        if(empty($custom_field['default_value'])) {
          $options[] = '--- '.__('Please Select', true).' ---';
        } elseif(empty($this->data['custom_field_values'][$custom_value['CustomField']['id']])) {
          $selected = $custom_field['default_value'];
        }
      }
      App::Import('vendor', 'spyc');
      $list = Spyc::YAMLLoad($custom_value['CustomField']['possible_values']);
      $options = array();
      if(!empty($list)) {
        foreach($list as $item) {
          $options[$item[0]] = $item[0];
        }
      }
      
      $out = $formHelper->input($field_name, array_merge(compact('type', 'empty', 'selected', 'options'), array('label'=>false, 'div'=>false)));
      break;
    default :
      $out = $formHelper->input($field_name, array('type'=>'text', 'label'=>false, 'div'=>false));
      break;
    }
    return $out;
  }
  # Return custom field label tag
  function custom_field_label_tag($formHelper, $name, $custom_value) {
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
  function custom_field_tag_with_label($formHelper, $name, $custom_value) {
    return $this->custom_field_label_tag($formHelper, $name, $custom_value).$this->custom_field_tag($formHelper, $name, $custom_value);
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

  # Return an array of custom field formats which can be used in select_tag
  def custom_field_formats_for_select
    CustomField::FIELD_FORMATS.sort {|a,b| a[1][:order]<=>b[1][:order]}.collect { |k| [ l(k[1][:name]), k[0] ] }
  end
*/
  function format_value($value, $field_format) {
    switch($field_format) {
    case "date" :
      $out = $this->Candy->format_date($value);
      break;
    case "bool" :
      if(empty($value)) {
        $out = __('No', true);
      } else {
        $out = __('Yes', true);
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
}

