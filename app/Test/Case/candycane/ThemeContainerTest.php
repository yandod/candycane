<?php
App::uses('ThemeContainer', 'Vendor');

class ThemeContainerTest extends CakeTestCase {

    public $autoFixtures = false;

    protected $themeContainer = null;

    public function setUp() {
        $this->themeContainer = new ThemeContainer();
    }

    public function testGetThemeLists() {
        $this->assertInternalType('array', $this->themeContainer->getThemeLists());
    }
}
