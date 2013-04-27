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
        $option = 'IPRI';
        $order = 'ASC';
        $data = $this->Enumeration->get_values($option, $order);
        $extractData = Set::extract("{n}.Enumeration.opt", $data);
        $this->assertEqual('IPRI', $extractData[0]);
        $this->assertEqual(1, count(array_unique($extractData)));

        $option = 'DCAT';
        $order = '';
        $data = $this->Enumeration->get_values($option, $order);
        $extractData = Set::extract("{n}.Enumeration.opt", $data);
        $this->assertEqual('DCAT', $extractData[0]);
        $this->assertEqual(1, count(array_unique($extractData)));

        $option = 'ACTI';
        $order = 'DESC';
        $data = $this->Enumeration->get_values($option, $order);
        $extractData = Set::extract("{n}.Enumeration.opt", $data);
        $this->assertEqual('ACTI', $extractData[0]);
        $this->assertEqual(1, count(array_unique($extractData)));
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
