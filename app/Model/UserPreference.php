<?php
/**
 * user_preference.php
 *
 */

/**
 * UserPreference
 *
 */
App::import('Vendor', 'georgious-cakephp-yaml-migrations-and-fixtures/spyc/spyc');
class UserPreference extends AppModel
{
  var $belongsTo = array('User');

#  serialize :others
#  
#  attr_protected :others
  
#  def initialize(attributes = nil)
#    super
#    self.others ||= {}
#  end
  
  /**
   * beforeSave
   *
   */
  function beforeSave()
  {
    //pr($this->data);
    if (isset($this->data['UserPreference']['pref'])) {
      $this->data['UserPreference']['others'] = Spyc::YAMLDump($this->data['UserPreference']['pref']);
    }
#    self.others ||= {}
    return true;
  }
  
  function afterFind($results, $primary = false)
  {
    if (isset($results['id'])) {
      $results['pref'] = Spyc::YAMLLoad($results['others']);
      return $results;
    }
    foreach($results as $key => $result) {
      $result['UserPreference']['pref'] = Spyc::YAMLLoad($result['UserPreference']['others']);
      $results[$key] = $result;
    }
    return $results;  
  }
  
  
#  def [](attr_name)
#    if attribute_present? attr_name
#      super
#    else
#      others ? others[attr_name] : nil
#    end
#  end
#  
#  def []=(attr_name, value)
#    if attribute_present? attr_name
#      super
#    else
#      h = read_attribute(:others).dup || {}
#      h.update(attr_name => value)
#      write_attribute(:others, h)
#      value
#    end
#  end
#  
#  def comments_sorting; self[:comments_sorting] end
#  def comments_sorting=(order); self[:comments_sorting]=order end
}
