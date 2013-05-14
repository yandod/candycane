<?php
App::uses('SettingContainer', 'Vendor');

class SettingContainerTest extends CakeTestCase {

    public $autoFixtures = false;

    protected $settingContainer = null;

    public function setUp() {
        $this->settingContainer = new SettingContainer();
    }

    public function testGetSystemSetting() {
        $this->assertInternalType('array', $this->settingContainer->getSystemSetting());
    }

    public function testAddSystemSetting() {
        $setting = array(
            'name' => 'foo',
        );

        $this->settingContainer->addSystemSetting($setting);
        $system_setting = $this->settingContainer->getSystemSetting();
        $this->assertEquals($setting, $system_setting['foo']);
    }
}
