<?php
App::uses('QueriesComponent', 'Controller/Component');
App::uses('Query', 'Model');
App::uses('AppController', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('ComponentCollection', 'Controller');

/**
 * testcase for QueriesComponent
 *
 * @author yando
 */
class QueriesComponentTestController extends AppController
{
    public $uses = array(
        'Query'
    );
}

class QueriesComponentTest extends CakeTestCase
{

/**
 * @var QueriesComponent
 */
    public $Component;

    public $Controller;
    //public $autoFixtures = false;
    public $fixtures = array(
        'app.issue_status',
        'app.enumeration',
    );

/**
 * startTest
 * initialize test case. 
 */
    public function startTest()
    {
        $this->loadFixtures();
        $Collection = new ComponentCollection();
        $CakeRequest = new CakeRequest();
        $CakeResponse = new CakeResponse();
        $this->Controller = new QueriesComponentTestController($CakeRequest, $CakeResponse);
        $this->Controller->Query = new Query();
        $this->Component = new QueriesComponent($Collection);
        $this->Component->startup($this->Controller);
    }

/**
 * testGetOptionValue
 * test case for get_option_value method 
 */
    public function testGetOptionValue()
    {
        $this->assertEqual($this->Component->get_option_value(1),1);
        $this->assertEqual($this->Component->get_option_value("ABC"),"ABC");
        $this->assertEqual($this->Component->get_option_value(array(3)),3);
        $this->assertEqual($this->Component->get_option_value(array(3,2,1)),array(3,2,1));   
    }

    /**
     * first access without any parameter 
     */
    public function testRetrieveQueryDefault()
    {
        //data should be empty before process
        $this->assertEqual(
            $this->Controller->request->data,
            array()
        );
        //
        $this->assertEqual($this->Component->retrieve_query(),null);
        $this->assertEqual(
            $this->Controller->request->data,
            array(
                'Filter' => array(
                    'fields_status_id' => 'status_id',
                    'operators_status_id' => 'o',
                    'values_status_id' => array(
                        0 => '',
                    )
                )
            )
        );

    }
}
