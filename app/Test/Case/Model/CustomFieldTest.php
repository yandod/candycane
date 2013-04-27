<?php
App::uses('CustomField', 'Model');

/**
 * CustomField Test Case
 *
 */
class CustomFieldTestCase extends CakeTestCase
{
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = array('app.custom_field');

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->CustomField = ClassRegistry::init('CustomField');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CustomField);

        parent::tearDown();
    }

    /**
     * testValidateFieldFormat method
     *
     * @return void
     * @dataProvider fieldFormatProvider
     */
    public function testValidateFieldFormat($data, $expect)
    {
        $this->assertEqual($expect, $this->CustomField->validate_field_format($data));
    }

    /**
     * testValidatePossibleValues method
     *
     * @return void
     */
    public function testValidatePossibleValues()
    {
        $this->assertTrue($this->CustomField->validate_possible_values(array(
            'field_format' => 'list', 'possible_values' => array(1),
        )));
        $this->assertFalse($this->CustomField->validate_possible_values(array(
            'field_format' => 'list', 'possible_values' => null,
        )));
        $this->assertFalse($this->CustomField->validate_possible_values(array(
            'field_format' => 'list', 'possible_values' => array(),
        )));
    }

    /**
     * testGroupBy method
     *
     * @return void
     */
    public function testGroupBy()
    {
        $result = $this->CustomField->group_by($this->CustomField->find('all'), 'type');

        $this->assertCount(2, $result['IssueCustomField']);
        $this->assertCount(1, $result['ProjectCustomField']);
        $this->assertCount(3, $result['TimeEntryCustomField']);

        $this->assertEqual('IssueCustomField', $result['IssueCustomField'][0]['CustomField']['type']);
    }

    public static function fieldFormatProvider()
    {
        return array(
            array(array('field_format' => 'string'),  true),
            array(array('field_format' => 'text'),    true),
            array(array('field_format' => 'int'),     true),
            array(array('field_format' => 'float'),   true),
            array(array('field_format' => 'list'),    true),
            array(array('field_format' => 'date'),    true),
            array(array('field_format' => 'bool'),    true),
            array(array('field_format' => 'varchar'), false),
        );
    }
}
