<?php
App::uses('HookContainer', 'Vendor');

class HookContainerTest extends CakeTestCase {

    public $autoFixtures = false;

    protected $hookContainer = null;

    public function setUp() {
        $this->hookContainer = new HookContainer();
    }

    public function testRegisterElementHook() {
        $target = 'foo';
        $name = 'bar';
        $this->assertTrue($this->hookContainer->registerElementHook($target, $name));
    }

    public function testUnregisterElementHook() {
        $target = 'foo';
        $name = 'bar';

        $this->assertTrue($this->hookContainer->unregisterElementHook($target));

        $before = false;
        $this->hookContainer->registerElementHook($target, $name, $before);
        $this->assertEquals($name, $this->hookContainer->getElementHook($target, $before));
        $this->assertTrue($this->hookContainer->unregisterElementHook($target, $before));
        $this->assertNull($this->hookContainer->getElementHook($target, $before));

        $before = true;
        $this->hookContainer->registerElementHook($target, $name, $before);
        $this->assertEquals($name, $this->hookContainer->getElementHook($target, $before));
        $this->assertTrue($this->hookContainer->unregisterElementHook($target, $before));
        $this->assertNull($this->hookContainer->getElementHook($target, $before));
    }

    public function testGetElementHook() {
        $target = 'foo';
        $name = 'bar';

        $this->assertNull($this->hookContainer->getElementHook($target));

        $before = false;
        $this->hookContainer->registerElementHook($target, $name, $before);

        $this->assertEquals($name, $this->hookContainer->getElementHook($target, $before));
        $this->assertNull($this->hookContainer->getElementHook($target, ! $before));
    }
}
