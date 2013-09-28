<?php
App::uses('FetcherComponent', 'Controller/Component');
App::uses('Controller', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('ComponentCollection', 'Controller');

class FetcherComponentTestController extends Controller
{
}

class FetcherComponentTest extends CakeTestCase
{
    public $autoFixtures = false;
    public $fixtures = array(
        'app.issue',
        'app.project',
        'app.tracker',
        'app.issue_status',
        'app.user',
        'app.version',
        'app.enumeration',
        'app.issue_category',
        'app.token',
        'app.member',
        'app.role',
        'app.user_preference',
        'app.custom_fields_project',
        'app.enabled_module',
        'app.time_entry',
        'app.changeset',
        'app.changesets_issue',
        'app.attachment',
        'app.projects_tracker',
        'app.custom_value',
        'app.custom_field',
        'app.watcher',
        'app.journal',
        'app.journal_detail',
        'app.news',
        'app.comment',
        'app.document',
        'app.wiki',
        'app.wiki_page',
        'app.wiki_content',
        'app.wiki_content_version',
        'app.wiki_redirect',
        'app.workflow'
    );
    public $Controller = null;

    /**
     * @var FetcherComponent
     */
    public $Component = null;
    public $Project = null;

    public function startTest()
    {
        $this->loadFixtures(
            'Project',
            'Version',
            'User',
            'Role',
            'Member',
            'Issue',
            'Journal',
            'JournalDetail',
            'Tracker',
            'ProjectsTracker',
            'IssueStatus',
            'EnabledModule',
            'IssueCategory',
            'TimeEntry',
            'Enumeration',
            'CustomValue',
            'CustomField',
            'News',
            'Comment',
            'Document',
            'CustomFieldsProject',
            'Changeset',
            'ChangesetsIssue',
            'Token',
            'UserPreference',
            'Watcher',
            'Attachment',
            'Wiki'
        );
        $this->Project = ClassRegistry::init('Project');
        $this->Project->read(null, 1);
        $Collection = new ComponentCollection();
        $CakeRequest = new CakeRequest();
        $CakeResponse = new CakeResponse();
        $this->Controller = new FetcherComponentTestController($CakeRequest, $CakeResponse);
        $this->Component = new FetcherComponent($Collection);

        $this->Component->initialize($this->Controller);
    }

    public function test_activity_without_subprojects()
    {
        $User = ClassRegistry::init('User');
        $user = $User->read(null, 6); // User.anonymous
        $this->Component->fetch($user['User'], array('project' => $this->Project->data));
        $events = $this->Component->events(date('Y-m-d', strtotime('-30 day')), date('Y-m-d', strtotime('+1 day')));
        $this->assertNotNull($events);
        $this->assertEqual(4, count($events));
        $this->assertEqual('issue-note', $events[0]['type']);
        $this->assertEqual(2, $events[0]['id']);
        $this->assertEqual('issue-note', $events[1]['type']);
        $this->assertEqual(1, $events[1]['id']);
        $this->assertEqual('issue', $events[2]['type']);
        $this->assertEqual(1, $events[2]['id']);
        $this->assertEqual('issue', $events[3]['type']);
        $this->assertEqual(7, $events[3]['id']);
        foreach (range(0, 2) as $i) {
            $this->assertTrue(
                strtotime($events[$i]['datetime']) >= strtotime($events[$i + 1]['datetime']),
                "Compare dates {$events[$i]['datetime']} > {$events[$i + 1]['datetime']}"
            );
            $this->assertFalse(
                (strtotime($events[$i]['datetime']) == strtotime($events[$i + 1]['datetime']) &&
                    $events[$i]['type'] == $events[$i + 1]['type'] &&
                    $events[$i]['id'] < $events[$i + 1]['id']
                ),
                "Compare id {$events[$i]['id']} > {$events[$i + 1]['id']}"
            );

        }


    }

    public function test_activity_with_subprojects()
    {
        $User = ClassRegistry::init('User');
        $user = $User->read(null, 6); // User.anonymous
        $this->Component->fetch($user['User'], array('project' => $this->Project->data, 'with_subprojects' => 1));
        $events = $this->Component->events(date('Y-m-d', strtotime('-30 day')), date('Y-m-d', strtotime('+1 day')));
        $this->assertNotNull($events);
        $this->assertEqual(5, count($events));
        $this->assertEqual('issue-note', $events[0]['type']);
        $this->assertEqual(2, $events[0]['id']);
        $this->assertEqual('issue', $events[2]['type']);
        $this->assertEqual(1, $events[2]['id']);
        # subproject issue
        $this->assertEqual('issue-note', $events[1]['type']);
        $this->assertEqual(1, $events[1]['id']);

        $this->assertEqual('issue', $events[3]['type']);
        $this->assertEqual(5, $events[3]['id']);
        $this->assertEqual('issue', $events[4]['type']);
        $this->assertEqual(7, $events[4]['id']);
    }

    public function test_global_activity_anonymous()
    {
        $User = ClassRegistry::init('User');
        $user = $User->read(null, 6); // User.anonymous
        $this->Component->fetch($user['User']);
        $events = $this->Component->events(date('Y-m-d', strtotime('-30 day')), date('Y-m-d', strtotime('+1 day')));
        $this->assertNotNull($events);
        $this->assertEqual(5, count($events));
        $this->assertEqual('issue-note', $events[0]['type']);
        $this->assertEqual(2, $events[0]['id']);
        $this->assertEqual('issue', $events[2]['type']);
        $this->assertEqual(1, $events[2]['id']);
        # subproject issue
        $this->assertEqual('issue-note', $events[1]['type']);
        $this->assertEqual(1, $events[1]['id']);
        $this->assertEqual('Bug #1: Can\'t print recipes', $events[1]['title']);

        $this->assertEqual('issue', $events[3]['type']);
        $this->assertEqual(5, $events[3]['id']);
        $this->assertEqual('Bug #5: Subproject issue', $events[3]['title']);
        $this->assertEqual('issue', $events[4]['type']);
        $this->assertEqual(7, $events[4]['id']);
        $this->assertEqual('Bug #7: Issue due today', $events[4]['title']);


// TODO Message feature
//    assert events.include?(Message.find(5))
        # Issue of a private project
    }

    public function test_global_activity_logged_user()
    {
        $User = ClassRegistry::init('User');
        $user = $User->find_by_id_logged(2); // manager
        $this->Component->fetch($user);
        $events = $this->Component->events(date('Y-m-d', strtotime('-30 day')), date('Y-m-d', strtotime('+1 day')));
        $this->assertNotNull($events);

        $this->assertEqual(7, count($events));
        $this->assertEqual('issue', $events[0]['type']);
        $this->assertEqual(6, $events[0]['id']);
        $this->assertEqual('issue-note', $events[1]['type']);
        $this->assertEqual('Bug #1: Can\'t print recipes', $events[1]['title']);
        # Issue of a private project the user belongs to
        $this->assertEqual(2, $events[1]['id']);
        $this->assertEqual('issue-note', $events[1]['type']);
        $this->assertEqual('Bug #1: Can\'t print recipes', $events[1]['title']);
        $this->assertEqual(1, $events[2]['id']);
        $this->assertEqual('issue', $events[3]['type']);
        $this->assertEqual('Bug #1: Can\'t print recipes', $events[3]['title']);
        # Issue of a private project the user belongs to
        $this->assertEqual(1, $events[3]['id']);
        $this->assertEqual('issue', $events[4]['type']);
        $this->assertEqual(4, $events[4]['id']);

        $this->assertEqual('issue', $events[5]['type']);
        $this->assertEqual(5, $events[5]['id']);
        $this->assertEqual('issue', $events[6]['type']);
        $this->assertEqual(7, $events[6]['id']);
    }

    public function test_user_activity()
    {
        $User = ClassRegistry::init('User');
        $user = $User->find_by_id_logged(2); // manager
        $anonymous = $User->read(null, 6); // User.anonymous
        $this->Component->fetch($anonymous['User'], array('author' => $user));
        $events = $this->Component->events(null, null, array('limit' => 10));
        $this->assertTrue(count($events) > 0);
        $this->assertTrue(count($events) <= 10);
        $this->assertEqual(array('2' => count($events)), array_count_values(Set::extract('{n}.author.id', $events)));
    }

    public function test_news_and_files_activity()
    {
        $this->loadFixtures('Attachment');
        $User = ClassRegistry::init('User');
        $user = $User->find_by_id_logged(2); // manager
        $this->Component->fetch($user);
        $events = $this->Component->events(
            date('Y-m-d', strtotime('2006-07-19 0:0:0')),
            date('Y-m-d', strtotime('2006-07-20 0:0:0'))
        );
        $this->assertNotNull($events);
        $this->assertEqual('news', $events[0]['type']);
        $this->assertEqual(2, $events[0]['id']);
        $this->assertEqual(
            array('controller' => 'news', 'action' => 'show', 'id' => 2, 'project_id' => 1),
            $events[0]['url']
        );
        $this->assertEqual('news', $events[1]['type']);
        $this->assertEqual(1, $events[1]['id']);
        $this->assertEqual(
            array('controller' => 'news', 'action' => 'show', 'id' => 1, 'project_id' => 1),
            $events[1]['url']
        );
        //$this->assertEqual('issue', $events[2]['type']);
        //$this->assertEqual(3, $events[2]['id']);
        //$this->assertEqual(array('controller'=>'issues', 'action'=>'show',3), $events[2]['url']);
    }

    public function test_documents_activity()
    {
        $this->loadFixtures('Attachment');
        $User = ClassRegistry::init('User');
        $user = $User->find_by_id_logged(2); // manager
        $this->Component->fetch($user);
        $events = $this->Component->events(
            date('Y-m-d', strtotime('2007-01-27 0:0:0')),
            date('Y-m-d', strtotime('2007-01-28 0:0:0'))
        );
        $this->assertNotNull($events);
        $this->assertEqual(1, count($events));
        $this->assertEqual('document', $events[0]['type']);
        $this->assertEqual(1, $events[0]['id']);
        $this->assertEqual(array('controller' => 'documents', 'action' => 'show', 'id' => 1), $events[0]['url']);

    }

}
