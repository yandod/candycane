<?php
/* SVN FILE: $Id: bootstrap.php 7945 2008-12-19 02:16:01Z gwoo $ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @version       $Revision: 7945 $
 * @modifiedby    $LastChangedBy: gwoo $
 * @lastmodified  $Date: 2008-12-18 18:16:01 -0800 (Thu, 18 Dec 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 *
 * This file is loaded automatically by the app/webroot/index.php file after the core bootstrap.php is loaded
 * This is an application wide file to load any function that is not used within a class define.
 * You can also use this to include or require any files in your application.
 *
 */
/**
 * The settings below can be used to set additional paths to models, views and controllers.
 * This is related to Ticket #470 (https://trac.cakephp.org/ticket/470)
 *
 * $modelPaths = array('full path to models', 'second full path to models', 'etc...');
 * $viewPaths = array('this path to views', 'second full path to views', 'etc...');
 * $controllerPaths = array('this path to controllers', 'second full path to controllers', 'etc...');
 *
 */
//EOF
Configure::write('app_title', 'Candycane');

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