<?php
/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class SettingContainer extends Object {

	protected $system_setting = array();

	protected $project_setting = array();
	
	protected $user_setting = array();
	

	public function __construct() {
		$this->initSystemSetting();
	}

	protected function initSystemSetting() {
		$this->system_setting = array(
			'general' => array(
				'name' => 'general',
				'partial' => 'settings/general',
				'label' => __('General')
			),
			'authentication' => array(
				'name' => 'authentication',
				'partial' => 'settings/authentication',
				'label' =>  __('Authentication')
			),
			'projects' => array(
				'name' => 'projects',
				'partial' => 'settings/projects',
				'label' => __('Projects')
			),
			'issues' => array(
				'name' => 'issues',
				'partial' => 'settings/issues',
				'label' => __('Issue tracking')
			),
			'notifications' => array(
				'name' => 'notifications',
				'partial' => 'settings/notifications',
				'label' => __('Email notifications')
			),
		);
		//array('name' => 'mail_handler', 'partial' => 'settings/mail_handler', 'label' => __('Incoming emails')),
		//array('name' => 'repositories', 'partial' =>  'settings/repositories', 'label' => __('Repositories'))

	}
	
	public function getSystemSetting() {
		return $this->system_setting;
	}

	public function addSystemSetting($setting, $first = false) {
		$this->system_setting[$setting['name']] = $setting;
	}

}