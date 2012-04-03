<?php
/**
 * Application Helper
 *
 * @package candycane
 */
App::uses('Helper', 'View');
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
	public function __construct($view) {
		$this->Settings = ClassRegistry::getObject('Setting');
		parent::__construct($view);
	}
}
