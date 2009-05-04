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
class SortHelper extends AppHelper
{
  var $helpers = array('Session', 'Html', 'Ajax');
  var $components = array('Sort');

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
      trigger_error('no implement!');
      // $caption = $this->titleize(Inflector::humanize($column));
    }

    if (isset($options['default_order'])) {
      $default_order = $options['default_order'];
    } else {
      $default_order = 'asc';
    }

    if (!isset($options['title'])) {
      $options['title'] = sprintf(__('Sort by %s', true), '"'.$caption.'"');
    }

    return $this->Html->tableHeaders(array($column), $options);
    /*
    content_tag('th', sort_link(column, caption, default_order), options)
     */
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
  function sort_link($column, $caption = null, $default_order)
  {
    if ($caption == null) {
      $caption = __($column, true);
    }

    $html = $this->Ajax->link($caption,
      '?sort_key=login&amp;sort_order=desc',
      array(),
      array('update' => 'post')
    );
    $html.= "&nbsp;&nbsp;";

    //$key = $this->Session->read()
    //if 

#    key, order = session[@sort_name][:key], session[@sort_name][:order]
#    if key == column
#      if order.downcase == 'asc'
#        icon = 'sort_asc.png'
#        order = 'desc'
#      else
#        icon = 'sort_desc.png'
#        order = 'asc'
#      end
#    else
#      icon = nil
#      order = default_order
#    end
#    caption = titleize(Inflector::humanize(column)) unless caption

#    
#    sort_options = { :sort_key => column, :sort_order => order }
#    # don't reuse params if filters are present
#    url_options = params.has_key?(:set_filter) ? sort_options : params.merge(sort_options)
#    
#    link_to_remote(caption,
#                  {:update => "content", :url => url_options},
#                  {:href => url_for(url_options)}) +
#    (icon ? nbsp(2) + image_tag(icon) : '')
  }

}

?>
