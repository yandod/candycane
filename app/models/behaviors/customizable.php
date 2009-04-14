<?php
/*
module Redmine
  module Acts
    module Customizable
      def self.included(base)
        base.extend ClassMethods
      end

      module ClassMethods
        def acts_as_customizable(options = {})
          return if self.included_modules.include?(Redmine::Acts::Customizable::InstanceMethods)
          cattr_accessor :customizable_options
          self.customizable_options = options
          has_many :custom_values, :as => :customized,
                                   :include => :custom_field,
                                   :order => "#{CustomField.table_name}.position",
                                   :dependent => :delete_all
          before_validation_on_create { |customized| customized.custom_field_values }
          # Trigger validation only if custom values were changed
          validates_associated :custom_values, :on => :update, :if => Proc.new { |customized| customized.custom_field_values_changed? }
          send :include, Redmine::Acts::Customizable::InstanceMethods
          # Save custom values when saving the customized object
          after_save :save_custom_field_values
        end
      end

      module InstanceMethods
        def self.included(base)
          base.extend ClassMethods
        end

        def available_custom_fields
          CustomField.find(:all, :conditions => "type = '#{self.class.name}CustomField'",
                                 :order => 'position')
        end

        def custom_field_values=(values)
          @custom_field_values_changed = true
          values = values.stringify_keys
          custom_field_values.each do |custom_value|
            custom_value.value = values[custom_value.custom_field_id.to_s] if values.has_key?(custom_value.custom_field_id.to_s)
          end if values.is_a?(Hash)
        end

        def custom_field_values
          @custom_field_values ||= available_custom_fields.collect { |x| custom_values.detect { |v| v.custom_field == x } || custom_values.build(:custom_field => x, :value => nil) }
        end

        def custom_field_values_changed?
          @custom_field_values_changed == true
        end

        def custom_value_for(c)
          field_id = (c.is_a?(CustomField) ? c.id : c.to_i)
          custom_values.detect {|v| v.custom_field_id == field_id }
        end

        def save_custom_field_values
          custom_field_values.each(&:save)
          @custom_field_values_changed = false
          @custom_field_values = nil
        end

        module ClassMethods
        end
      end
    end
  end
end
*/
class CustomizableBehavior extends ModelBehavior {
  var $custom_field_values_changed = false;
  var $custom_field_values = null;
  
  function setup(&$Model, $config = array()) {
    $settings = $config;
    $this->settings[$Model->alias] = $settings;
    return true;
  }
  function beforeValidate(&$Model){
e(pr($Model->data));
exit;
    # before_validation_on_create { |customized| customized.custom_field_values }
    return true;
  }
  function beforeSave(&$Model) {
    # Trigger validation only if custom values were changed
    # validates_associated :custom_values, :on => :update, :if => Proc.new { |customized| customized.custom_field_values_changed? }
    if(!empty($Model->id) && $this->customized.custom_field_values_changed($Model)) {
      // TODO validate values
    }
    return true;
  }
  function afterSave(&$Model, $created) {
    # after_save :save_custom_field_values
    return $this->save_custom_field_values($Model, $created);
  }

  function available_custom_fields(&$Model) {
    $Model->bindModel(array('hasMany'=>array('CustomValue'=>array('order'=>'CustomField.position', 'dependent'=>true, 'foreignKey'=>'customized_id'))), false);
    return $Model->CustomValue->CustomField->find('all', 
        array('conditions' => array('type'=> $Model->name.'CustomField'), 'order'=>'position'));
  }

  function custom_field_values(&$Model, $values) {
    $this->custom_field_values_changed = true;
    if(is_array($values)) {
      $this->custom_field_values = $this->_custom_field_values($Model);
      foreach($this->custom_field_values as $key => $custom_value) {
        if(!empty($values[$custom_value['CustomValue']['custom_field_id']])) {
          $custom_value['CustomValue']['value'] = $values[$custom_value['CustomValue']['custom_field_id']];
        }
      }
    }
  }

  function _custom_field_values(&$Model) {
    if(empty($this->custom_field_values)) {
      $availables = $this->available_custom_fields($Model); 
      foreach($availables as $x) {
        $match = false;
        foreach($Model->data['CustomValue'] as $v) {
          if($v['CustomField'] == $x) {
              // copy
              $match = true;
          }
        }
        # (custom_values.build(:custom_field => x, :value => nil) 
      }
    }
  }

  function custom_value_for(&$Model, $c) {
    $field_id = isset($c['CustomField']) ? $c['CustomField']['id'] : 0;
    foreach($Model->data['CustomValue'] as $v) {
      if($v['custom_field_id'] == $field_id) {
        return $v;
      }
    }
    return false;
  }

  function save_custom_field_values(&$Model, $created) {
    $Model->bindModel(array('hasMany'=>array('CustomValue'=>array('order'=>'CustomField.position', 'dependent'=>true, 'foreignKey'=>'customized_id'))), false);
    if(isset($Model->data['CustomValue'])) {
      foreach($Model->data['CustomValue'] as $custom_value) {
        $Model->CustomValue->create();
        $Model->CustomValue->save($custom_value);
      }
    }
    $this->custom_field_values_changed = false;
    $this->custom_field_values = null;
  }
}

?>
