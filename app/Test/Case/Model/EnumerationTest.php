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
     * @todo order test
     */
    public function testGetValues()
    {
        $option = 'IPRI';
        $order = 'ASC';
        $data = $this->Enumeration->get_values($option, $order);
        $this->assertEqual(1, $data[0]['Enumeration']['position']);
        $this->assertEqual('IPRI', $data[0]['Enumeration']['opt']);
        $extractData = Set::extract("{n}.Enumeration.opt", $data);
        $this->assertEqual(1, count(array_unique($extractData)));

        $option = 'DCAT';
        $order = '';
        $data = $this->Enumeration->get_values($option, $order);
        $this->assertEqual(1, $data[0]['Enumeration']['position']);
        $this->assertEqual('DCAT', $data[0]['Enumeration']['opt']);
        $extractData = Set::extract("{n}.Enumeration.opt", $data);
        $this->assertEqual(1, count(array_unique($extractData)));

        $option = 'ACTI';
        $order = 'DESC';
        $data = $this->Enumeration->get_values($option, $order);
        $this->assertEqual(3, $data[0]['Enumeration']['position']);
        $this->assertEqual('ACTI', $data[0]['Enumeration']['opt']);
        $extractData = Set::extract("{n}.Enumeration.opt", $data);
        $this->assertEqual(1, count(array_unique($extractData)));
    }

    /**
     * testDefaultValue method
     *
     * @return void
     */
    public function testDefaultValue()
    {
        $isTrueOption = 'IPRI';
        $data = $this->Enumeration->default_value($isTrueOption);
        $this->assertTrue($data['Enumeration']['is_default']);

        $isFalseOption = 'DCAT';
        $data = $this->Enumeration->default_value($isFalseOption);
        $this->assertFalse($data);
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
