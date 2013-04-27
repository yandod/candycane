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
    public $fixtures = array('app.enumeration', 'app.tracker', 'app.time_entry', 'app.changeset', 'app.changesets_issue');

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
        $order = 'ASC';
        $option = 'IPRI';
        $data = $this->Enumeration->get_values($option, $order);
        $this->assertEqual(1, $data[0]['Enumeration']['position']);
        $this->assertEqual('IPRI', $data[0]['Enumeration']['opt']);
        $extractData = Set::extract("{n}.Enumeration.opt", $data);
        $this->assertEqual(1, count(array_unique($extractData)));

        $order = '';
        $option = 'DCAT';
        $data = $this->Enumeration->get_values($option, $order);
        $this->assertEqual(1, $data[0]['Enumeration']['position']);
        $this->assertEqual('DCAT', $data[0]['Enumeration']['opt']);
        $extractData = Set::extract("{n}.Enumeration.opt", $data);
        $this->assertEqual(1, count(array_unique($extractData)));

        $order = 'DESC';
        $option = 'ACTI';
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
        $data = $this->Enumeration->find('all');
        $this->assertEqual(0, $this->Enumeration->objects_count($data[0]));
        $this->assertEqual(3, $this->Enumeration->objects_count($data[8]));
        $this->assertEqual(1, $this->Enumeration->objects_count($data[9]));
    }

    /**
     * testInUse method
     *
     * @return void
     */
    public function testInUse()
    {
        $data = $this->Enumeration->find('all');
        $this->assertFalse($this->Enumeration->in_use($data[0]));
        $this->assertTrue($this->Enumeration->in_use($data[8]));
        $this->assertTrue($this->Enumeration->in_use($data[9]));
    }

    /**
     * testDestroy method
     *
     * @return void
     */
    public function testDestroy()
    {
        $this->markTestIncomplete(
            'このテストは、まだ実装されていません。'
        );
    }

    /**
     * testDeleteOfDestroy method
     *
     * @return void
     */
    public function testDeleteOfDestroy()
    {
        $enumerationId = 4;

        // 削除前
        $this->assertEqual(1, count($this->Enumeration->findById($enumerationId)));
        $this->Enumeration->delete($enumerationId);

        // 削除後
        $this->assertFalse($this->Enumeration->findById($enumerationId));
    }

}
