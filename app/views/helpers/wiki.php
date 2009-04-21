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
}
