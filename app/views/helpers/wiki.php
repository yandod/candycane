<?php
/* vim: fenc=utf8 ff=unix
 *
 *
 */

class WikiHelper extends AppHelper
{
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
}
