<?php
App::uses('QueriesComponent', 'Controller/Component');
App::uses('Query', 'Model');
App::uses('Project', 'Model');
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
        'Query',
        'Project'
    );

    /**
     * @var Project
     */
    public $Project;

    /**
     * @var Query;
     */
    public $Query;
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
        'app.version',
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
        $this->Controller->Query = ClassRegistry::init('Query');
        $this->Controller->Project = ClassRegistry::init('Project');
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
    
    public function testRetrieveQueryFromQueryString()
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
    
    public function testRetrieveQueryFromQueryStringWithNotEqual()
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
                'status_id' => array(1),
            ),
            'fields' => array(
                'status_id',
            ),
            'operators' => array(
                'status_id' => '!',
            )
        );
        $this->assertEqual($this->Component->retrieve_query(),null);
        $this->assertEqual(
            $this->Controller->request->data,
            array(
                'Filter' => array(
                    'fields_status_id' => 'status_id',
                    'operators_status_id' => '!',
                    'values_status_id' => 1,
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
            )
        );
    }

    public function testRetrieveQueryFromQueryStringWithoutIssueStatus()
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
                'subject' => 'private',
            ),
            'fields' => array(
                'subject'
            ),
            'operators' => array(
                'subject' => '~'
            )
        );
        $this->assertEqual($this->Component->retrieve_query(),null);
        $this->assertEqual(
            $this->Controller->request->data,
            array(
                'Filter' => array(
                    'fields_subject' => 'subject',
                    'operators_subject' => '~',
                    'values_subject' => 'private',
                )
            )
        );

        //check state of show_filters
        $this->assertEqual(
        $this->Controller->viewVars['show_filters'],
            array(
                'subject' => array(
                    'type' => 'text',
                    'order' => 8,
                    'operators' => array(
                        '~' => 'contains',
                        '!~' => "doesn't contain",
                    ),
                )
            )
        );
    }

    public function testRetrieveQueryFromQueryStringWithMe()
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
                'assigned_to_id' => array('me'),
            ),
            'fields' => array(
                'assigned_to_id'
            ),
            'operators' => array(
                'assigned_to_id' => '='
            )
        );
        $this->Component->controller->current_user = array(
            'id' => 4,
            'admin' => false
        );
        $this->assertEqual($this->Component->retrieve_query(),null);
        $this->assertEqual(
            $this->Controller->request->data,
            array(
                'Filter' => array(
                    'fields_assigned_to_id' => 'assigned_to_id',
                    'operators_assigned_to_id' => '=',
                    'values_assigned_to_id' => 'me',
                )
            )
        );

        //check state of show_filters
        $this->assertEqual(
        $this->Controller->viewVars['show_filters'],
            array(
                'assigned_to_id' => array(
                    'type' => 'list_optional',
                    'order' => 4,
                    'operators' => array(
                        '=' => 'is',
                        '!' => 'is not',
                        '!*' => 'none',
                        '*' => 'all',
                    ),
                    'values' => array(
                        'me' => 'me'
                    )
                )
            )
        );
    }

    public function testRetrieveQueryFromQueryStringWithProject()
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
                'created_on' => '3',
            ),
            'fields' => array(
                'created_on' => 'created_on'
            ),
            'operators' => array(
                'created_on' => '>t-'
            )
        );
        $this->Component->controller->current_user = array(
            'id' => 4
        );
        $this->Component->controller->_project = array(
            'Project' => array('id' => 2)
        );
        $this->assertEqual($this->Component->retrieve_query(),null);
        $this->assertEqual(
            $this->Controller->request->data,
            array(
                'Filter' => array(
                    'fields_created_on' => 'created_on',
                    'operators_created_on' => '>t-',
                    'values_created_on' => '3',
                )
            )
        );

        //check state of show_filters
        $this->assertEqual(
        $this->Controller->viewVars['show_filters'],
            array(
                'created_on' => array(
                    'type' => 'date_past',
                    'order' => 9,
                    'operators' => array(
                        '>t-' => 'less than days ago',
                        '<t-' => 'more than days ago',
                        't-' => 'days ago',
                        't' => 'today',
                        'w' => 'this week',
                    ),
                )
            )
        );
        
        $this->assertEqual(
            $this->Component->query_filter_cond,
            array(
                'Issue.project_id' => 2,
                0 => array(
                    'Issue.created_on >' => date('Y-m-d 23:59:59',  strtotime('-3 days')),
                ),
                1 => array(
                    'Issue.created_on <=' => date('Y-m-d 23:59:59'),
                ), 
            )
        );
    }
}
