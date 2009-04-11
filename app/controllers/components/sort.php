<?php
/**
 * sort.php
 *
 */

/**
 * SortComponent
 *
 */
class SortComponent extends Object
{
  var $components = array('Session');

  var $sort_name = null;

  /**
   * startUp
   */
  public function startUp($controller) {
    $this->controller = $controller;
    $this->params = $controller->params;
  }

  /**
   * sort_update
   *
   * Updates the sort state. Call this in the controller prior to calling
   * sort_clause.
   * sort_keys can be either an array or a hash of allowed keys
   */
  function sort_update($sort_keys)
  {
    $r_sort_key = null;

    if (isset($this->params['url']['sort_key'])) {
      $r_sort_key = $this->params['url']['sort_key'];
    }

    $sort_key = null;

    if (is_array($r_sort_key) && in_array($r_sort_key, $sort_keys)) {
      $sort_key = $sort_keys[$r_sort_key];
    }

    if (isset($this->params['url']['sort_order']) && strtolower($this->params['url']['sort_order']) == 'desc') {
      $sort_order = 'DESC';
    } else {
      $sort_order = 'ASC';
    }

    if ($sort_key != null) {
      $sort = array('key' => $sort_key, 'order' => $sort_order);
    } else if ($this->Session->read($this->sort_name)) {
      $sort = $this->Session->read($this->sort_name); // Previous sort.
    } else {
      $sort = $this->sort_default;
    }

    $this->Session->write($this->sort_name, $sort);

    $sort_column = null;

    if (isset($sort['key'])) {
      $sort_column = $sort['key'];
    }

    if (is_array($sort_keys)) {
      $sort_column = $sort_keys[$sort['key']];
    }
    
    $this->sort_clause = null;
    if (!empty($sort_column)) {
      $this->sort_clause = "{$sort_column} {$sort['order']}";
    }

    return $this->sort_clause;
  }

}


