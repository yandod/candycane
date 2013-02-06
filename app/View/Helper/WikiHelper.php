<?php
/* vim: fenc=utf8 ff=unix
 *
 *
 */

class WikiHelper extends AppHelper
{
  var $helpers = array(
    'Candy', 'Html'
  );

  function pretty_title($str) {
    if ($str && is_string($str)) {
      return str_replace('_', ' ', $str);
    } else {
      return $str;
    }
  }

  function titleize($title) {
    // replace spaces with _ and remove unwanted caracter
    $title = preg_replace('/\s+/', '_', $title);
    // upcase the first letter
    $title = preg_replace('/^([a-z])/e', 'strtoupper("\\1")', $title);
    return $title;
  }
  
  function render_wiki_breadcrumb($wiki_links) {
    $links = array();
    foreach(array_slice($wiki_links,0,-1) as $wiki_link) {
      $links[] = $this->Html->link(
        str_replace('_',' ',$wiki_link[0]),
        array(
          'controller' => 'wiki',
          'action' => 'index',
          'project_id' => $wiki_link[1],
          'wikipage' => $wiki_link[0]
        )
      );
    }
    $last_page = array_slice($wiki_links,-1);
    $links[] = str_replace('_',' ',$last_page[0][0]);
    return $this->Candy->breadcrumb($links);
  }
}
