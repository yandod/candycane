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
  /**
   * sort_init
   *
   * Initializes the default sort column (default_key) and sort order
   * (default_order).
   *
   * - default_key is a column attribute name.
   * - default_order is 'asc' or 'desc'.
   * - name is the name of the session hash entry that stores the sort state,
   *   defaults to '<controller_name>_sort'.
   *
   */
  function sort_init($default_key, $default_order = 'asc', $name = null)
  {
    # @sort_name = name || params[:controller] + params[:action] + '_sort'
    # @sort_default = {:key => default_key, :order => default_order}
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
#    sort_key = params[:sort_key]
#    sort_key = nil unless (sort_keys.is_a?(Array) ? sort_keys.include?(sort_key) : sort_keys[sort_key])
#
#    sort_order = (params[:sort_order] == 'desc' ? 'DESC' : 'ASC')
#    
#    if sort_key
#      sort = {:key => sort_key, :order => sort_order}
#    elsif session[@sort_name]
#      sort = session[@sort_name]   # Previous sort.
#    else
#      sort = @sort_default
#    end
#    session[@sort_name] = sort
#    
#    sort_column = (sort_keys.is_a?(Hash) ? sort_keys[sort[:key]] : sort[:key])
#    @sort_clause = (sort_column.blank? ? nil : "#{sort_column} #{sort[:order]}")
  }

  /**
   * sort_clause
   *
   * Returns an SQL sort clause corresponding to the current sort state.
   * Use this to sort the controller's table items collection.
   */
  function sort_clause()
  {
    return $this->sort_clause;
  }

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
    /*
    caption = options.delete(:caption) || titleize(Inflector::humanize(column))
    default_order = options.delete(:default_order) || 'asc'
    options[:title]= l(:label_sort_by, "\"#{caption}\"") unless options[:title]
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
  function sort_link($column, $caption, $default_order)
  {
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
