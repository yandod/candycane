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
        'app.project',
        'app.user',
        'app.enumeration',
        'app.query'
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
        
        $this->assertEqual(
            $this->Controller->viewVars['show_filters'],
            array(
                'status_id' => array(
                    'operator' => 'o',
                    'values' => array(
                        0 => ''
                    )
                )
            )
        );
    }

/**
 * access with query id in db. 
 */
    public function testRetrieveQueryFromDb()
    {
        //data should be empty before process
        $this->assertEqual(
            $this->Controller->request->data,
            array()
        );
        
        //get parameter for query_id = 1
        $this->assertEqual($this->Component->retrieve_query(1),null);
        $this->assertEqual(
            $this->Controller->request->data,
            array(
                'Filter' => array(
                    'fields_status_id' => 'status_id',
                    'operators_status_id' => 'o',
                    'values_status_id' => array(
                        0 => '1',
                    ),
                    'fields_tracker_id' => 'tracker_id',
                    'operators_tracker_id' => '=',
                    'values_tracker_id' => array(
                        0 => '2'
                    )
                )
            )
        );

        //check state of show_filters
        $this->assertEqual(
        $this->Controller->viewVars['show_filters'],
            array(
                'status_id' => array(
                    'operator' => 'o',
                    'values' => array(
                        0 => 1
                    )
                ),
                'tracker_id' => array(
                    'operator' => '=',
                    'values' => array(
                        0 => 2
                    )
                )
            )
        );
    }
    
    public function testTretrieveQueryFromQueryString()
    {
        //data should be empty before process
        $this->assertEqual(
            $this->Controller->request->data,
            array()
        );
        
        //get parameter for query string
        $this->Component->controller->request->query = array(
            'set_filter' => 1,
            'values' => array(
                'status_id' => array(1,2,3),
                'tracker_id' => '3',
            ),
            'fields' => array(
                'status_id',
                'tracker_id'
            ),
            'operators' => array(
                'status_id' => '=',
                'tracker_id' => '='
            )
        );
        $this->assertEqual($this->Component->retrieve_query(),null);
        $this->assertEqual(
            $this->Controller->request->data,
            array(
                'Filter' => array(
                    'fields_tracker_id' => 'tracker_id',
                    'operators_tracker_id' => '=',
                    'values_tracker_id' => '3',
                    'fields_status_id' => 'status_id',
                    'operators_status_id' => '=',
                    'values_status_id' => array(1,2,3),
                )
            )
        );

        //check state of show_filters
        $this->assertEqual(
        $this->Controller->viewVars['show_filters'],
            array(
                'status_id' => array(
                    'values' => array(
                        6 => 'Rejected',
                        1 => 'New',
                        2 => 'Assigned',
                        3 => 'Resolved',
                        4 => 'Feedback',
                        5 => 'Closed',
                    ),
                    'type' => 'list_status',
                    'order' => 1,
                    'operators' => array(
                        'o' => 'open',
                        '=' => 'is',
                        '!' => 'is not',
                        'c' => 'closed',
                        '*' => 'all',
                    )
                ),
                
                'tracker_id' => array(
                    'values' => array(),
                    'type' => 'list',
                    'order' => 2,
                    'operators' => array(
                        '=' => 'is',
                        '!' => 'is not',
                    ),
                )
            )
        );
    }
}
