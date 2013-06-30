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
    public $fixtures = array(
        'app.version',
        'app.user',
        'app.project',
        'app.document',
        'app.issue_category',
        'app.issue_status',
        'app.enumeration',
        'app.tracker',
        'app.time_entry',
        'app.changeset',
        'app.changesets_issue',
        'app.issue',
        'app.watcher',
        'app.custom_value',
        'app.custom_field',
    );

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
        $this->assertEquals(1,$data['Enumeration']['is_default']);

        $isFalseOption = 'DCAT';
        $data = $this->Enumeration->default_value($isFalseOption);
        $this->assertEquals($data, array());
    }

    /**
     * testObjectsCount method
     *
     * @return void
     */
    public function testObjectsCount()
    {
        $data = $this->Enumeration->find('all');
        $this->assertEqual(1, $this->Enumeration->objects_count($data[0]));
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
        $this->assertTrue($this->Enumeration->in_use($data[0]));
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
        $reassign_to = 5;
        $data = $this->Enumeration->find('all');
        $row = $data[3];
        $enumerationId = $row['Enumeration']['id'];

        $model = ClassRegistry::init($this->Enumeration->OPTIONS[$row['Enumeration']['opt']]['model']);

        // reassign前
        $foreignKeyColumnName = $this->Enumeration->OPTIONS[$row['Enumeration']['opt']]['foreign_key'];
        $modelData = $model->find('all', array('conditions' => array(
            $foreignKeyColumnName => $enumerationId
        )));
        $this->assertEqual($enumerationId, $modelData[0][$model->name]['priority_id']);

        // destroy
        $this->Enumeration->destroy($row);

        // reassign後
        $modelData = $model->find('all', array('conditions' => array(
            $foreignKeyColumnName => $enumerationId
        )));
        $this->assertEqual(0, count($modelData));

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
        $this->assertEquals($this->Enumeration->findById($enumerationId), array());
    }

}
