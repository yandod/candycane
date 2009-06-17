<?php
/**
 * user_preference.php
 *
 */

/**
 * UserPreference
 *
 */
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
#    self.others ||= {}
    return true;
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
