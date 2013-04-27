<?php
App::uses('Enumeration', 'Model');

/**
 * Enumeration Test Case
 *
 */
class EnumerationTestCase extends CakeTestCase
{
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = array('app.enumeration');

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->Enumeration = ClassRegistry::init('Enumeration');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Enumeration);

        parent::tearDown();
    }

    /**
     * testGetValues method
     *
     * @return void
     */
    public function testGetValues()
    {
    }

    /**
     * testDefaultValue method
     *
     * @return void
     */
    public function testDefaultValue()
    {

    }

    /**
     * testObjectsCount method
     *
     * @return void
     */
    public function testObjectsCount()
    {

    }

    /**
     * testInUse method
     *
     * @return void
     */
    public function testInUse()
    {

    }

    /**
     * testDestroy method
     *
     * @return void
     */
    public function testDestroy()
    {

    }

}
