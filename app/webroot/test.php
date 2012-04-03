<?php
/* SVN FILE: $Id: test.php 7945 2008-12-19 02:16:01Z gwoo $ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <https://trac.cakephp.org/wiki/Developement/TestSuite>
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          https://trac.cakephp.org/wiki/Developement/TestSuite CakePHP(tm) Tests
 * @package       cake
 * @subpackage    cake.cake.tests.libs
 * @since         CakePHP(tm) v 1.2.0.4433
 * @version       $Revision: 7945 $
 * @modifiedby    $LastChangedBy: gwoo $
 * @lastmodified  $Date: 2008-12-18 18:16:01 -0800 (Thu, 18 Dec 2008) $
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
error_reporting(E_ALL);
set_time_limit(0);
ini_set('memory_limit','128M');
ini_set('display_errors', 1);
/**
 * Use the DS to separate the directories in other defines
 */
	if (!defined('DS')) {
		define('DS', DIRECTORY_SEPARATOR);
	}
/**
 * These defines should only be edited if you have cake installed in
 * a directory layout other than the way it is distributed.
 * When using custom settings be sure to use the DS and do not add a trailing DS.
 */

/**
 * The full path to the directory which holds "app", WITHOUT a trailing DS.
 *
 */
	if (!defined('ROOT')) {
		define('ROOT', dirname(dirname(dirname(__FILE__))));
	}
/**
 * The actual directory name for the "app".
 *
 */
	if (!defined('APP_DIR')) {
		define('APP_DIR', basename(dirname(dirname(__FILE__))));
	}
/**
 * The absolute path to the "cake" directory, WITHOUT a trailing DS.
 *
 */
	if (!defined('CAKE_CORE_INCLUDE_PATH')) {
		define('CAKE_CORE_INCLUDE_PATH', ROOT);
	}

/**
 * Editing below this line should not be necessary.
 * Change at your own risk.
 *
 */
if (!defined('WEBROOT_DIR')) {
	define('WEBROOT_DIR', basename(dirname(__FILE__)));
}
if (!defined('WWW_ROOT')) {
	define('WWW_ROOT', dirname(__FILE__) . DS);
}
if (!defined('CORE_PATH')) {
	if (function_exists('ini_set') && ini_set('include_path', CAKE_CORE_INCLUDE_PATH . PATH_SEPARATOR . ROOT . DS . APP_DIR . DS . PATH_SEPARATOR . ini_get('include_path'))) {
		define('APP_PATH', null);
		define('CORE_PATH', null);
	} else {
		define('APP_PATH', ROOT . DS . APP_DIR . DS);
		define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
	}
}
if (!include(CORE_PATH . 'cake' . DS . 'bootstrap.php')) {
	trigger_error("CakePHP core could not be found.  Check the value of CAKE_CORE_INCLUDE_PATH in APP/webroot/index.php.  It should point to the directory containing your " . DS . "cake core directory and your " . DS . "vendors root directory.", E_USER_ERROR);
}

$corePath = Configure::corePaths('cake');
if (isset($corePath[0])) {
	define('TEST_CAKE_CORE_INCLUDE_PATH', rtrim($corePath[0], DS) . DS);
} else {
	define('TEST_CAKE_CORE_INCLUDE_PATH', CAKE_CORE_INCLUDE_PATH);
}

require_once CAKE . 'TestSuite' . DS . 'test_manager.php';

if (Configure::read('debug') < 1) {
	die(__('Debug setting does not allow access to this url.'));
}

if (!isset($_SERVER['SERVER_NAME'])) {
	$_SERVER['SERVER_NAME'] = '';
}
if (empty( $_GET['output'])) {
	$_GET['output'] = 'html';
}
/**
 *
 * Used to determine output to display
 */
define('CAKE_TEST_OUTPUT_HTML', 1);
define('CAKE_TEST_OUTPUT_TEXT', 2);

if (isset($_GET['output']) && $_GET['output'] == 'html') {
	define('CAKE_TEST_OUTPUT', CAKE_TEST_OUTPUT_HTML);
} else {
	Debugger::output('txt');
	define('CAKE_TEST_OUTPUT', CAKE_TEST_OUTPUT_TEXT);
}

if (!App::import('Vendor', 'simpletest' . DS . 'reporter')) {
	CakePHPTestHeader();
	include CAKE . 'TestSuite' . DS . 'simpletest.php';
	CakePHPTestSuiteFooter();
	exit();
}

$analyzeCodeCoverage = false;
if (isset($_GET['code_coverage'])) {
	$analyzeCodeCoverage = true;
	require_once CAKE . 'TestSuite' . DS . 'code_coverage_manager.php';
	if (!extension_loaded('xdebug')) {
		CakePHPTestHeader();
		include CAKE . 'TestSuite' . DS . 'xdebug.php';
		CakePHPTestSuiteFooter();
		exit();
	}
}

CakePHPTestHeader();
CakePHPTestSuiteHeader();
define('RUN_TEST_LINK', $_SERVER['PHP_SELF']);

if (isset($_GET['group'])) {
	if ('all' == $_GET['group']) {
		TestManager::runAllTests(CakeTestsGetReporter());
	} else {
		if ($analyzeCodeCoverage) {
			CodeCoverageManager::start($_GET['group'], CakeTestsGetReporter());
		}
		TestManager::runGroupTest(ucfirst($_GET['group']), CakeTestsGetReporter());
		if ($analyzeCodeCoverage) {
			CodeCoverageManager::report();
		}
	}

	CakePHPTestRunMore();
	CakePHPTestAnalyzeCodeCoverage();
} elseif (isset($_GET['case'])) {
	if ($analyzeCodeCoverage) {
		CodeCoverageManager::start($_GET['case'], CakeTestsGetReporter());
	}

	TestManager::runTestCase($_GET['case'], CakeTestsGetReporter());

	if ($analyzeCodeCoverage) {
		CodeCoverageManager::report();
	}

	CakePHPTestRunMore();
	CakePHPTestAnalyzeCodeCoverage();
} elseif (isset($_GET['show']) && $_GET['show'] == 'cases') {
	CakePHPTestCaseList();
} else {
	CakePHPTestGroupTestList();
}
CakePHPTestSuiteFooter();
$output = ob_get_clean();
echo $output;
?>