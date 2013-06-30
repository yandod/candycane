<?php
App::uses('Project', 'Model');

/**
 * Project Test Case
 *
 */
class ProjectTestCase extends CakeTestCase {

    /**
     * @var Project
     */
    public $Project;

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.project', 'app.wiki', 'app.wiki_page', 'app.wiki_content', 'app.user', 'app.token', 'app.user_preference', 'app.member', 'app.role', 'app.wiki_content_version', 'app.wiki_redirect', 'app.issue_category', 'app.version', 'app.issue', 'app.issue_status', 'app.enumeration', 'app.tracker', 'app.workflow', 'app.time_entry', 'app.changeset', 'app.changesets_issue', 'app.enabled_module', 'app.projects_tracker', 'app.custom_field', 'app.custom_value', 'app.custom_fields_project');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Project = ClassRegistry::init('Project');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown()
    {
		unset($this->Project);

		parent::tearDown();
	}

/**
 * testFindById method
 *
 * @return void
 */
	public function testFindById()
    {
        $data = $this->Project->findById(1);
        $this->assertEqual($data['Project']['name'],'eCookbook');
        $this->assertEquals($this->Project->findById(100), array());
	}
/**
 * testFindByIdentifier method
 *
 * @return void
 */
	public function testFindByIdentifier()
    {
        $data = $this->Project->findByIdentifier('onlinestore');
        $this->assertEqual($data['Project']['name'],'OnlineStore');
        $this->assertEquals($this->Project->findByIdentifier('onlinestore2'), array());
	}
/**
 * testFindSubprojects method
 *
 * @return void
 */
	public function testFindSubprojects()
    {
        $data = $this->Project->findSubprojects(1);
        $this->assertCount(3,$data);

        $data = $this->Project->findSubprojects(5);
        $this->assertCount(0,$data);

    }
/**
 * testFindMainProject method
 *
 * @return void
 */
	public function testFindMainProject()
    {
        $this->loadFixtures('Issue', 'Project', 'Tracker', 'IssueStatus', 'User', 'Version', 'Enumeration', 'IssueCategory', 'TimeEntry', 'Changeset', 'EnabledModule','CustomFieldsProject', 'Wiki', 'ProjectsTracker', 'Member', 'CustomField', 'CustomValue');
        $project = $this->Project->findMainProject('ecookbook');
        $this->assertEqual('eCookbook', $project['Project']['name']);
        $this->assertEqual(8, count($project['EnabledModule']));
        $this->assertEqual(
            array('issue_tracking','time_tracking','news','documents','files','wiki','repository','boards'),
            array_reverse(Set::extract('{n}.name', $project['EnabledModule']))
        );
 	}
/**
 * testLatest method
 *
 * @return void
 */
	public function testLatest()
    {
        $data = $this->Project->latest(
            array(
                'logged' => false
            )
        );
        $this->assertCount(3, $data);
        foreach ($data as $row) {
            $this->assertEqual($row['Project']['is_public'], 1);
        }

        $data = $this->Project->latest(
            array(
                'admin' => true
            )
        );
        $this->assertCount(5, $data);
        $this->assertEqual(Set::extract('{n}.Project.is_public',$data),array(0,0,1,0,1));

    }
/**
 * testVisibleBy method
 *
 * @return void
 */
	public function testVisibleBy()
    {
        $data = $this->Project->visible_by(array('admin' => 1));
        $this->assertEqual($data,array('Project.status' => 1));

        $data = $this->Project->visible_by(array('admin' => false));
        $this->assertEqual($data,array(
            'Project.status' => 1,
            'Project.is_public' => true
        ));


        $memberships = array(
            array('Project' => array('id' => 3)),
            array('Project' => array('id' => 4)),
            array('Project' => array('id' => 5)),
        );

        $data = $this->Project->visible_by(array(
            'admin' => false,
            'memberships' => $memberships
        ));
        $this->assertEqual($data,array(
            'Project.status' => 1,
            array(
                0 => array(
                    'Project.id' => array(
                        3,4,5
                    ),
                ),
                'or' => array(
                    'Project.is_public' => true
                )
            )
        ));

    }
/**
 * testGetVisibleByCondition method
 *
 * @return void
 */
	public function testGetVisibleByCondition() {

	}
/**
 * testAllowedToCondition method
 *
 * @return void
 */
	public function testAllowedToCondition() {

	}
/**
 * testAllowedToConditionString method
 *
 * @return void
 */
	public function testAllowedToConditionString()
    {
        $data = $this->Project->allowed_to_condition_string(
            array(
                'admin' => true
            ),
            ':hoge'
        );
        $this->assertEqual($data,'Project.status=1');

        $data = $this->Project->allowed_to_condition_string(
            array(
                'admin' => false,
                'logged' => true,
                'memberships' => array(
                    array(
                        'Project' => array(
                            array('Project' => array('id' => 3))
                        )
                    )
                )
            ),
            ':hoge'
        );
        $this->assertEqual($data,'((Project.status=1) AND (1=0 OR Project.id IN (3)))');

        $data = $this->Project->allowed_to_condition_string(
            array(
                'admin' => false,
                'logged' => true,
                'memberships' => array(
                    array(
                        'Project' => array(
                            array('Project' => array('id' => 3))
                        )
                    ),
                    array(
                        'Project' => array(
                            array('Project' => array('id' => 4))
                        )
                    ),
                    array(
                        'Project' => array(
                            array('Project' => array('id' => 5))
                        )
                    ),
                )
            ),
            ':hoge'
        );
        $this->assertEqual($data,'((Project.status=1) AND (1=0 OR Project.id IN (3,4,5)))');

    }
/**
 * testProjectCondition method
 *
 * @return void
 */
	public function testProjectCondition() {

	}
/**
 * testIsActive method
 *
 * @return void
 */
	public function testIsActive() {

	}
/**
 * testArchive method
 *
 * @return void
 */
	public function testArchive() {

	}
/**
 * testUnarchive method
 *
 * @return void
 */
	public function testUnarchive() {

	}
/**
 * testActiveChildren method
 *
 * @return void
 */
	public function testActiveChildren() {

	}
/**
 * testAssignableUsers method
 *
 * @return void
 */
	public function testAssignableUsers() {

	}
/**
 * testMembers method
 *
 * @return void
 */
	public function testMembers() {

	}
/**
 * testRecipients method
 *
 * @return void
 */
	public function testRecipients() {

	}
/**
 * testShortDescription method
 *
 * @return void
 */
	public function testShortDescription() {

	}
/**
 * testAfterFindOne method
 *
 * @return void
 */
	public function testAfterFindOne() {

	}
/**
 * testIsAllowsTo method
 *
 * @return void
 */
	public function testIsAllowsTo() {

	}
}
