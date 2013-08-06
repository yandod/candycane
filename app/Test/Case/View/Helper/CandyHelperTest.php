<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('CandyHelper', 'View/Helper');
App::uses('Controller', 'Controller');
App::uses('CakeSession', 'Model/Datasource');


/**
 * CandyHelper Test Case
 * @property CandyHelper Candy
 */
class CandyHelperTest extends CakeTestCase
{
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = array(
        'app.project',
        'app.wiki',
        'app.wiki_page',
        'app.wiki_content',
        'app.user',
        'app.token',
        'app.user_preference',
        'app.member',
        'app.role',
        'app.wiki_content_version',
        'app.wiki_redirect',
        'app.issue_category',
        'app.version',
        'app.issue',
        'app.issue_status',
        'app.enumeration',
        'app.tracker',
        'app.workflow',
        'app.time_entry',
        'app.changeset',
        'app.changesets_issue',
        'app.enabled_module',
        'app.projects_tracker',
        'app.setting',
        'app.custom_field',
        'app.custom_value',
        'app.custom_fields_project'
    );


    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $View = new View();
        $this->Candy = new CandyHelper($View);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Candy);

        parent::tearDown();
    }

    /**
     * testLink method
     *
     * @return void
     */
    public function testLink()
    {
    }

    /**
     * testAccesskey method
     *
     * @return void
     */
    public function testAccesskey()
    {
    }

    /**
     * testLwrE method
     *
     * @return void
     */
    public function testLwrE()
    {
    }

    /**
     * testLwrR method
     *
     * @return void
     */
    public function testLwrR()
    {
    }

    /**
     * testLwr method
     *
     * @return void
     */
    public function testLwr()
    {
    }

    /**
     * testHtmlTitle method
     *
     * @return void
     */
    public function testHtmlTitle()
    {
    }

    /**
     * testAuthorizeFor method
     *
     * @return void
     */
    public function testAuthorizeFor()
    {
        // can not see link to the project what user does not own.
        $project = ClassRegistry::init('Project')->findById(7);
        $result = $this->Candy->authorize_for(
            array(
                'controller' => 'members',
                'action' => 'edit'
            ),
            $project
        );
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testAuthorizeFor_Admin()
    {
        // can see link to the project for system admin.
        CakeSession::id('testsess');
        CakeSession::init();
        CakeSession::write('user_id', 1);
        $project = ClassRegistry::init('Project')->findById(1);
        $result = $this->Candy->authorize_for(
            array(
                'controller' => 'members',
                'action' => 'edit'
            ),
            $project
        );
        $this->assertTrue($result);
    }

    /**
     * testLinkToIfAuthorized method
     *
     * @return void
     */
    public function testLinkToIfAuthorized()
    {
    }

    /**
     * testFormatUsername method
     *
     * @return void
     */
    public function testFormatUsername()
    {
    }

    /**
     * testLinkToUser method
     *
     * @return void
     */
    public function testLinkToUser()
    {
    }

    /**
     * testLinkToIssue method
     *
     * @return void
     */
    public function testLinkToIssue()
    {
    }

    /**
     * testLinkToAttachment method
     *
     * @return void
     */
    public function testLinkToAttachment()
    {
    }

    /**
     * testLinkToVersion method
     *
     * @return void
     */
    public function testLinkToVersion()
    {
    }

    /**
     * testToggleLink method
     *
     * @return void
     */
    public function testToggleLink()
    {
    }

    /**
     * testFormatDate method
     *
     * @return void
     */
    public function testFormatDate()
    {
    }

    /**
     * testFormatTime method
     *
     * @return void
     */
    public function testFormatTime()
    {
    }

    /**
     * testFormatActivityTitle method
     *
     * @return void
     */
    public function testFormatActivityTitle()
    {
    }

    /**
     * testFormatActivityDay method
     *
     * @return void
     */
    public function testFormatActivityDay()
    {
    }

    /**
     * testFormatActivityDescription method
     *
     * @return void
     */
    public function testFormatActivityDescription()
    {
    }

    /**
     * testDistanceOfDateInWords method
     *
     * @return void
     */
    public function testDistanceOfDateInWords()
    {
    }

    /**
     * testDueDateDistanceInWords method
     *
     * @return void
     */
    public function testDueDateDistanceInWords()
    {
    }

    /**
     * testTruncateSingleLine method
     *
     * @return void
     */
    public function testTruncateSingleLine()
    {
    }

    /**
     * testHtmlHours method
     *
     * @return void
     */
    public function testHtmlHours()
    {
    }

    /**
     * testAuthoring method
     *
     * @return void
     */
    public function testAuthoring()
    {
    }

    /**
     * testSyntaxHighlight method
     *
     * @return void
     */
    public function testSyntaxHighlight()
    {
    }

    /**
     * testPaginationLinksFull method
     *
     * @return void
     */
    public function testPaginationLinksFull()
    {
    }

    /**
     * testPerPageLinks method
     *
     * @return void
     */
    public function testPerPageLinks()
    {
    }

    /**
     * testBreadcrumb method
     *
     * @return void
     */
    public function testBreadcrumb()
    {
    }

    /**
     * testTextilizable method
     *
     * @return void
     */
    public function testTextilizable()
    {
    }

    /**
     * testLangOptionsForSelect method
     *
     * @return void
     */
    public function testLangOptionsForSelect()
    {
    }

    /**
     * testGetLangLabel method
     *
     * @return void
     */
    public function testGetLangLabel()
    {
    }

    /**
     * testBackUrlHiddenFieldTag method
     *
     * @return void
     */
    public function testBackUrlHiddenFieldTag()
    {
    }

    /**
     * testProgressBarAuto method
     *
     * @return void
     */
    public function testProgressBarAuto()
    {
    }

    /**
     * testProgressBar method
     *
     * @return void
     */
    public function testProgressBar()
    {
    }

    /**
     * testContextMenuLink method
     *
     * @return void
     */
    public function testContextMenuLink()
    {
    }

    /**
     * testCalendarFor method
     *
     * @return void
     */
    public function testCalendarFor()
    {
    }

    /**
     * testIncludeCalendarHeadersTags method
     *
     * @return void
     */
    public function testIncludeCalendarHeadersTags()
    {
    }

    /**
     * testAvatar method
     *
     * @return void
     */
    public function testAvatar()
    {
    }

    /**
     * testTruncate method
     *
     * @return void
     */
    public function testTruncate()
    {
    }

    /**
     * testCycle method
     *
     * @return void
     */
    public function testCycle()
    {
    }

    /**
     * testResetCycle method
     *
     * @return void
     */
    public function testResetCycle()
    {
    }

    /**
     * testLabelText method
     *
     * @return void
     */
    public function testLabelText()
    {
    }

    /**
     * testCheckAllLinks method
     *
     * @return void
     */
    public function testCheckAllLinks()
    {
    }

    /**
     * testDistanceOfTimeInWords method
     *
     * @return void
     */
    public function testDistanceOfTimeInWords()
    {
    }

    /**
     * testOptionsFromCollectionForSelect method
     *
     * @return void
     */
    public function testOptionsFromCollectionForSelect()
    {
    }

    /**
     * testProjectIcon method
     *
     * @return void
     */
    public function testProjectIcon()
    {
    }

}
