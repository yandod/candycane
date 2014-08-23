<?php
define('CANDYCANE_VERSION', '0.9.4');
Configure::write('app_title', 'Candycane');
setlocale(LC_CTYPE,'C');

if ( file_exists('../../vendor/autoload.php')) {
    include_once '../../vendor/autoload.php';
}
App::import('Vendor','candycane/MenuContainer');
App::import('Vendor','candycane/HookContainer');
App::import('Vendor','candycane/PluginContainer');
App::import('Vendor','candycane/SettingContainer');
App::import('Vendor','candycane/ThemeContainer');

$menu_container = new MenuContainer();
$hookContainer = new HookContainer();
$pluginContainer = new PluginContainer();
$settingContainer = new SettingContainer();
$themeContainer = new ThemeContainer();

App::uses('ClassRegistry', 'Utility');
CakePlugin::loadAll(
	array(
//		array('routes' => true)
	)
);
ClassRegistry::addObject('HookContainer',$hookContainer);
ClassRegistry::addObject('MenuContainer',$menu_container);
ClassRegistry::addObject('PluginContainer',$pluginContainer);
ClassRegistry::addObject('SettingContainer',$settingContainer);
ClassRegistry::addObject('ThemeContainer',$themeContainer);

$pluginPaths = glob(APP.'Plugin/Cc*/init.php');
if ($pluginPaths === false) {
    $pluginPaths = array();
}
foreach( $pluginPaths as $val){
	include_once(realpath($val));
}

// Enable the Dispatcher filters for plugin assets, and
// CacheHelper.
Configure::write('Dispatcher.filters', array(
    'AssetDispatcher',
    'CacheDispatcher'
));

// Add logging configuration.
CakeLog::config('debug', array(
    'engine' => 'FileLog',
    'types' => array('notice', 'info', 'debug'),
    'file' => 'debug',
));
CakeLog::config('error', array(
    'engine' => 'FileLog',
    'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
    'file' => 'error',
));

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
