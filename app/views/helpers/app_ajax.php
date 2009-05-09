<?php
App::import('Helper', 'Ajax');
class AppAjaxHelper extends AjaxHelper {
  /**
   * Finds URL for specified action.
   * 
   * default : h(Router::url($url, $full));
   * This function corresponds so that URL is not encoded in Javascript. 
   * Two or more GET parameters cannot be correctly sent when encoded when the Ajax request is sent in Javascript. 
   */
  function url($url = null, $full = false) {
    return Router::url($url, $full);
  }
}
?>