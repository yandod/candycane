<?php
/**
 * Application Helper
 *
 * @package candycane
 */
class AppHelper extends Helper {

/**
 * Settings
 *
 * @var Setting
 */
	public $Settings;

/**
 * Constructor
 *
 */
	public function __construct() {
		$this->Settings =& ClassRegistry::getObject('Setting');
		parent::__construct();
	}
}
