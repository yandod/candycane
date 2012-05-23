<?php
/**
 * token.php
 *
 *
 */

/**
 * Token
 *
 */
class Token extends AppModel
{
	public $belongsTo = array('User');

  var $validity_time = 0;

  function __construct($id = false, $table = null, $ds = null) {
    parent::__construct($id, $table, $ds);
    $this->validity_time = 60 * 60 * 24; // 1.day
  }

  function beforeCreate()
  {
    $this->data['User']['value'] = $this->_generate_token_value();
    return true;
  }

  /**
   * public static funtion isExpired
   *
   * Return true if token has expired  
   */
  function isExpired($token)
  {
    if (time() > (strtotime($token['Token']['created_on']) + $this->validity_time)) {
      return true;
    } else {
      return false;
    }
  }
  
  /**
   * destroy_expired
   *
   * Delete all expired tokens
   */
  function destroy_expired()
  {
    return $this->deleteAll(
      array(
        'action <>' => 'feeds',
        'created_on < ?' => array(time() - $this->validity_time),
      )
    );
  }
  
  /**
   * _generate_token_value
   *
   * @access private
   * @todo fix token generate algorithm
   */
  function _generate_token_value()
  {
#    chars = ("a".."z").to_a + ("A".."Z").to_a + ("0".."9").to_a
#    token_value = ''
#    40.times { |i| token_value << chars[rand(chars.size-1)] }
#    token_value
    return sha1(microtime());
  }
  
  function destroy($user_id,$action)
  {
    $this->deleteAll(
      array(
        'action =' => $action,
        'user_id =' => $user_id,
      )
    );
    $this->save(
      array(
        $this->alias => array(
          'user_id' => $user_id,
          'action' => $action,
          'value' => $this->_generate_token_value()
        )
      )
    );
  }
}

