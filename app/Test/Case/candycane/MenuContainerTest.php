<?php
App::uses('MenuContainer', 'Vendor');

class MenuContainerTest extends CakeTestCase {

    public $autoFixtures = false;

    protected $menuContainer = null;

    public function setUp() {
        $this->menuContainer = new MenuContainer();
    }

    public function testGetTopMenu() {
        $currentuser = array(
            'logged' => false,
            'admin' => false,
        );
        $this->assertInternalType('array', $this->menuContainer->getTopMenu($currentuser));
    }

    public function testGetTopMenuWithLogged() {
        $currentuser = array(
            'logged' => true,
            'admin' => false,
        );
        $top_menu = $this->menuContainer->getTopMenu($currentuser);
        $this->assertNotEmpty(Hash::extract($top_menu, '{n}[logged=true]'));
    }

    public function testGetTopMenuWithAdmin() {
        $currentuser = array(
            'logged' => true,
            'admin' => true,
        );
        $top_menu = $this->menuContainer->getTopMenu($currentuser);
        $this->assertNotEmpty(Hash::extract($top_menu, '{n}[admin=true]'));
    }

    public function testGetProjectMenu() {
        $this->assertInternalType('array', $this->menuContainer->getProjectMenu());
    }

    public function testGetProjectSettingMenu() {
        $this->assertInternalType('array', $this->menuContainer->getProjectSettingMenu());
    }

    public function testAddTopMenu() {
        $currentuser = array(
            'logged' => true,
        );

        $before = $this->menuContainer->getTopMenu($currentuser);

        $item = array(
            'url' => '/',
            'class' => 'foo',
            'caption' => 'foo',
            'logged' => false,
            'admin' => false
        );
        $this->menuContainer->addTopMenu($item);

        $after = $this->menuContainer->getTopMenu($currentuser);

        $this->assertGreaterThan(count($before), count($after));
    }

    public function testAddProjectMenu() {
        $before = $this->menuContainer->getProjectMenu();

        $key = 'foo';
        $item = array(
            'plugin' => '',
            'controller' => 'foo',
            'action' => 'index',
            'class' => '',
            'caption' => '',
            'params' => ''
        );
        $this->menuContainer->addProjectMenu($key, $item);

        $after = $this->menuContainer->getProjectMenu();

        $this->assertGreaterThan(count($before), count($after));
        $this->assertArrayHasKey($key, $after);
    }

    public function testAddProjectSettingMenu() {
        $before = $this->menuContainer->getProjectSettingMenu();

        $key = 'foo';
        $item = array(
            'name' => 'foo',
            'partial' => 'projects/settings/foo',
            'label' => 'foo'
        );
        $this->menuContainer->addProjectSettingMenu($key, $item);

        $after = $this->menuContainer->getProjectSettingMenu();

        $this->assertGreaterThan(count($before), count($after));
        $this->assertArrayHasKey($key, $after);
    }
}
