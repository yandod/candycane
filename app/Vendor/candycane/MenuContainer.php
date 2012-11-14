<?php
/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class MenuContainer extends Object {

	protected $top_menu = array();

	protected $project_menu = array();

  protected $project_setting_menu = array();

/**
 * constructor
 */
	public function __construct() {
		$this->_initTopMenu();
		$this->_initProjectMenu();
		$this->_initProjectSettingMenu();
	}

/**
 * initialize top menu.
 */
	protected function _initTopMenu() {
		$this->top_menu = array(
			'home' => array(
				'url' => '/',
				'class' => 'home',
				'caption' => 'Home',
				'logged' => false,
				'admin' => false
			),
			'mypage' => array(
				'url' => '/my/page',
				'class' => 'my-page',
				'caption' => 'My page',
				'logged' => true,
				'admin' => false
			),
			'projects' => array(
				'url' => '/projects',
				'class' => 'projects',
				'caption' => 'Projects',
				'logged' => false,
				'admin' => false
			),
			'administration' => array(
				'url' => '/admin',
				'class' => 'administration',
				'caption' => 'Administration',
				'logged' => true,
				'admin' => true
			),
			'help' => array(
				'url' => 'https://groups.google.com/group/candycane-users',
				'class' => 'help',
				'caption' => 'Help',
				'logged' => false,
				'admin' => false
			),
		);
	}

	protected function _initProjectMenu() {
		$this->project_menu = array(
			'overview' => array(
				'plugin' => '',
				'controller' => 'projects',
				'action' => 'show',
				'class' => '',
				'caption' => 'Overview',
				'params' => 'project_id'
			),
			'activity' => array(
				'plugin' => '',
				'controller' => 'projects',
				'action' => 'activity',
				'class' => '',
				'caption' => 'Activity',
				'params' => 'project_id'
			),
			'roadmap'  => array(
				'plugin' => '',
				'controller' => 'projects',
				'action' => 'roadmap',
				'class' => '',
				'caption' => 'Roadmap',
				'params' => 'project_id'
			),
			'issues'   => array(
				'plugin' => '',
				'controller' => 'issues',
				'action' => 'index',
				'class' => '',
				'caption' => 'Issues',
				'params' => 'project_id'
			),
			'new_issue'=> array(
				'plugin' => '',
				'controller' => 'issues',
				'action' => 'add',
				'class' => '',
				'caption' => 'New issue',
				'params' => 'project_id'
			),
			'news'     => array(
				'plugin' => '',
				'controller' => 'news',
				'action' => 'index',
				'class' => '',
				'caption' => 'News',
				'params' => 'project_id'
			),
			'wiki'     => array(
				'plugin' => '',
				'controller' => 'wiki',
				'action' => 'index',
				'class' => '',
				'caption' => 'Wiki',
				'params' => 'project_id'
			),
			'settings' => array(
				'plugin' => '',
				'controller' => 'projects',
				'action' => 'settings',
				'class' => '',
				'caption' => 'Preferences',
				'params' => 'project_id'
			),
		);
	}

  protected function _initProjectSettingMenu()
  {
    $this->project_setting_menu = array(
      array(
        'name' => 'info',
        'partial' => 'projects/edit',
        'label' =>  __('Information')
      ),
      array(
        'name' => 'modules',
        'partial' => 'projects/settings/modules',
        'label' => __('Modules')
      ),
      array(
        'name' => 'members',
        'partial' => 'projects/settings/members',
        'label' => __('Members')
      ),
      array(
        'name' => 'versions',
        'partial' => 'projects/settings/versions',
        'label' => __('Versions')
      ),
      array(
        'name' => 'categories',
        'partial' => 'projects/settings/issue_categories',
        'label' => __('Issue categories')
      ),
      array(
        'name' => 'wiki',
        'partial' => 'projects/settings/wiki',
        'label' => __('Wiki')
      ),
    );
  }

/**
 * get top menu items.
 * @param array $currentuser
 * @return array
 */
	public function getTopMenu($currentuser) {
		$temp = array();
		foreach ($this->top_menu as $val) {
			if (
				array_key_exists('logged', $val) &&
				$val['logged'] &&
				!$currentuser['logged']
			) {
				continue;
			}
			if (
				array_key_exists('admin', $val) &&
				$val['admin'] &&
				array_key_exists('admin', $currentuser) &&
				!$currentuser['admin']
			) {
				continue;
			}
			$temp[] = $val;
		}
		return $temp;
	}

	public function getProjectMenu() {
		return $this->project_menu;
	}

  public function getProjectSettingMenu() {
    return $this->project_setting_menu;
  }

/**
 * add item to topmenu.
 * @param array $item
 * @param boolean $first
 */
	public function addTopMenu($item, $first = false) {
		$this->top_menu[] = $item;
	}

/**
 * add item to projectmenu.
 * @param array $item
 * @param boolean $first
 */
	public function addProjectMenu($key,$item, $first = false) {
		$this->project_menu[$key] = $item;
	}

  public function addProjectSettingMenu($key, $item, $first = false) {
    $this->project_setting_menu[$key] = $item;
  }
}
