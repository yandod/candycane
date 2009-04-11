<?php
/* vim: fenc=utf8 ff=unix
 *
 *
 */

class CustomFieldHelper extends AppHelper
{

  function show_value($value)
  {
    if (empty($value)) { return ""; }

    // @FIXME
    // format_value(custom_value.value, custom_value.custom_field.field_format)
    return $value;
  }
}

