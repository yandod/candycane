<?php
define('CANDYCANE_VERSION', '0.9.0');
Configure::write('app_title', 'Candycane');
setlocale(LC_CTYPE,'C');

App::import('Vendor','candycane/MenuContainer');
App::import('Vendor','candycane/HookContainer');
App::import('Vendor','candycane/PluginContainer');
$menu_container = new MenuContainer();
$hookContainer = new HookContainer();
$pluginContainer = new PluginContainer();
App::uses('ClassRegistry', 'Utility');
CakePlugin::loadAll();
ClassRegistry::addObject('HookContainer',$hookContainer);
ClassRegistry::addObject('MenuContainer',$menu_container);
ClassRegistry::addObject('PluginContainer',$pluginContainer);
//foreach( glob(APP.'plugins/cc_*/init.php') as $val){
//	require_once(realpath($val));
//}


// by PHP_Compat 1.6.0a2
function php_compat_http_build_query($formdata, $numeric_prefix = null)
{
    // If $formdata is an object, convert it to an array
    if (is_object($formdata)) {
        $formdata = get_object_vars($formdata);
    }

    // Check we have an array to work with
    if (!is_array($formdata)) {
        user_error('http_build_query() Parameter 1 expected to be Array or Object. Incorrect value given.',
            E_USER_WARNING);
        return false;
    }

    // If the array is empty, return null
    if (empty($formdata)) {
        return;
    }

    // Argument seperator
    $separator = ini_get('arg_separator.output');
    if (strlen($separator) == 0) {
        $separator = '&';
    }

    // Start building the query
    $tmp = array ();
    foreach ($formdata as $key => $val) {
        if (is_null($val)) {
            continue;
        }

        if (is_integer($key) && $numeric_prefix != null) {
            $key = $numeric_prefix . $key;
        }

        if (is_scalar($val)) {
            array_push($tmp, urlencode($key) . '=' . urlencode($val));
            continue;
        }

        // If the value is an array, recursively parse it
        if (is_array($val) || is_object($val)) {
            array_push($tmp, php_compat_http_build_query_helper($val, urlencode($key)));
            continue;
        }

        // The value is a resource
        return null;
    }

    return implode($separator, $tmp);
}


// Helper function
function php_compat_http_build_query_helper($array, $name)
{
    $tmp = array ();
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            array_push($tmp, php_compat_http_build_query_helper($value, sprintf('%s[%s]', $name, $key)));
        } elseif (is_scalar($value)) {
            array_push($tmp, sprintf('%s[%s]=%s', $name, urlencode($key), urlencode($value)));
        } elseif (is_object($value)) {
            array_push($tmp, php_compat_http_build_query_helper(get_object_vars($value), sprintf('%s[%s]', $name, $key)));
        }
    }

    // Argument seperator
    $separator = ini_get('arg_separator.output');
    if (strlen($separator) == 0) {
        $separator = '&';
    }

    return implode($separator, $tmp);
}


// Define
if (!function_exists('http_build_query')) {
    function http_build_query($formdata, $numeric_prefix = null)
    {
        return php_compat_http_build_query($formdata, $numeric_prefix);
    }
}

if (!function_exists('array_intersect_key')) {
  function array_intersect_key ($isec, $arr2) {
    $argc = func_num_args();
    for ($i = 1; !empty($isec) && $i < $argc; $i++) {
      $arr = func_get_arg($i);
      foreach ($isec as $k => $v)
        if (!isset($arr[$k]))
          unset($isec[$k]);
    }
    return $isec;
  }
}

if(!function_exists('str_split')) {
    function str_split($string,$string_length=1) {
        if(strlen($string)>$string_length || !$string_length) {
            do {
                $c = strlen($string);
                $parts[] = substr($string,0,$string_length);
                $string = substr($string,$string_length);
            } while($string !== false);
        } else {
            $parts = array($string);
        }
        return $parts;
    }
}

/**
 * Constructs associative array from pairs of arguments.
 *
 * Example:
 *
 * `aa('a','b')`
 *
 * Would return:
 *
 * `array('a'=>'b')`
 *
 * @return array Associative array
 * @link http://book.cakephp.org/view/1123/aa
 * @deprecated Will be removed in 2.0
 */
function aa() {
    $args = func_get_args();
    $argc = count($args);
    for ($i = 0; $i < $argc; $i++) {
        if ($i + 1 < $argc) {
            $a[$args[$i]] = $args[$i + 1];
        } else {
            $a[$args[$i]] = null;
        }
        $i++;
    }
    return $a;
}