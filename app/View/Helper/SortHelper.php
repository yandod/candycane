<?php
/**
 * sort.php
 *
 */

/**
 * SortHelper
 *
 * Helpers to sort tables using clickable column headers.
 *
 * Author:  Stuart Rackham <srackham@methods.co.nz>, March 2005.
 * License: This source code is released under the MIT license.
 *
 * - Consecutive clicks toggle the column's sort order.
 * - Sort state is maintained by a session hash entry.
 * - Icon image identifies sort column and state.
 * - Typically used in conjunction with the Pagination module.
 *
 * Example code snippets:
 *
 * Controller:
 *
 *   helper :sort
 *   include SortHelper
 * 
 *   def list
 *     sort_init 'last_name'
 *     sort_update
 *     @items = Contact.find_all nil, sort_clause
 *   end
 * 
 * Controller (using Pagination module):
 *
 *   helper :sort
 *   include SortHelper
 * 
 *   def list
 *     sort_init 'last_name'
 *     sort_update
 *     @contact_pages, @items = paginate :contacts,
 *       :order_by => sort_clause,
 *       :per_page => 10
 *   end
 * 
 * View (table header in list.rhtml):
 * 
 *   <thead>
 *     <tr>
 *       <%= sort_header_tag('id', :title => 'Sort by contact ID') %>
 *       <%= sort_header_tag('last_name', :caption => 'Name') %>
 *       <%= sort_header_tag('phone') %>
 *       <%= sort_header_tag('address', :width => 200) %>
 *     </tr>
 *   </thead>
 *
 * - The ascending and descending sort icon images are sort_asc.png and
 *   sort_desc.png and reside in the application's images directory.
 * - Introduces instance variables: @sort_name, @sort_default.
 * - Introduces params :sort_key and :sort_order.
 *
 *
 */
App::import('Helper', 'Paginator');
class SortHelper extends PaginatorHelper
{
  var $helpers = array('AppAjax', 'Html');
  // sort_init moved SortComponent
  // sort_update moved SortComponent
  // sort_clause moved SortComponent

  /**
   *
   *
   * Returns a table header <th> tag with a sort link for the named column
   * attribute.
   *
   * Options:
   *   :caption     The displayed link name (defaults to titleized column name).
   *   :title       The tag's 'title' attribute (defaults to 'Sort by :caption').
   *
   * Other options hash entries generate additional table header tag attributes.
   *
   * Example:
   *
   *   <%= sort_header_tag('id', :title => 'Sort by contact ID', :width => 40) %>
   *
   * Renders:
   *
   *   <th title="Sort by contact ID" width="40">
   *     <a href="/contact/list?sort_order=desc&amp;sort_key=id">Id</a>
   *     &nbsp;&nbsp;<img alt="Sort_asc" src="/images/sort_asc.png" />
   *   </th>
   */
  function sort_header_tag($column, $options = array())
  {
    if (isset($options['caption'])) {
      $caption = $options['caption'];
    } else {
      $caption = Inflector::humanize($column);
    }
    if (!isset($options['title'])) {
      $options['title'] = sprintf(__('Sort by %s'), '"'.$caption.'"');
    }
    if (!isset($options['update'])) {
      $options['update'] = 'content';
    }
    return $this->Html->tag('th', $this->sort_link($column, $caption, $options));
  }

  /**
   * nbsp
   *
   * Return n non-breaking spaces.
   *
   * @access private
   */
  function nbsp($n)
  {
    # '&nbsp;' * n
    return ;
  }

  /**
   * titleize
   *
   * Return capitalized title.
   *
   * @access private
   */
  function titleize($title)
  {
    return strtoupper($title);
  }

  /**
   * sort_link
   *
   * Returns a link which sorts by the named column.
   *
   * - column is the name of an attribute in the sorted record collection.
   * - The optional caption explicitly specifies the displayed link text.
   * - A sort icon image is positioned to the right of the sort link.
   */
  function sort_link($column, $caption = null, $options)
  {
    if ($caption == null) {
      $caption = __($column);
    }
    $html = $this->sort($column, $caption, $options);

    if($this->request->params['named']['sort'] == $column) {
      if($this->request->params['named']['direction'] == 'asc') {
        $icon = 'sort_asc.png';
      } else {
        $icon = 'sort_desc.png';
      }
      $html.= "&nbsp;&nbsp;".$this->Html->image($icon);
    }
    return $html;
  }

// overwrite
/**
 * Generates a plain or Ajax link with pagination parameters
 *
 * FIX: Because two or more GET parameters.
 *  from: $obj = isset($options['update']) ? 'Ajax' : 'Html';
 *  to:   $obj = isset($options['update']) ? 'AppAjax' : 'Html';
 *
 * @param  string $title Title for the link.
 * @param  mixed $url Url for the action. See Router::url()
 * @param  array $options Options for the link. See #options for list of keys.
 * @return string A link with pagination parameters.
 */
  function link($title, $url = array(), $options = array()) {
    $options = array_merge(array('model' => null, 'escape' => true), $options);
    $model = $options['model'];
    unset($options['model']);

    if (!empty($this->options)) {
      $options = array_merge($this->options, $options);
    }
    if (isset($options['url'])) {
      $url = array_merge((array)$options['url'], (array)$url);
      unset($options['url']);
    }
    $url = $this->url($url, true, $model);

    $obj = isset($options['update']) ? 'AppAjax' : 'Html';
    $url = array_merge(array('page' => $this->current($model)), $url);
    $url = array_merge(Set::filter($url, true), array_intersect_key($url, array('plugin'=>true)));
    return $this->{$obj}->link($title, $url, $options);
  }
}

?>
