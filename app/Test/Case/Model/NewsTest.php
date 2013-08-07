<?php
App::uses('News', 'Model');

/**
 * News Test Case
 * @property News $News
 * @property User $User
 */
class NewsTestCase extends CakeTestCase
{
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = array(
        'app.news',
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
        'app.custom_field',
        'app.custom_fields_project',
        'app.comment'
    );

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->News = ClassRegistry::init('News');
        $this->User = ClassRegistry::init('User');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->News);

        parent::tearDown();
    }

    /**
     * testLatest method
     *
     * @return void
     */
    public function testLatest()
    {
        $user = $this->User->findById(7);

        //ログインをエミュレート
        $user['logged'] = true;
        $user['memberships'] = array(array('Project' => array('id' => 6)));

        //デフォルト５件返ってくる
        $result = $this->News->latest($user);
        $this->assertEqual(count($result), 5);
        //自分が所属していないプロジェクト(7)がヒットしていない
        foreach ($result as $rows) {
            $this->assertNotEqual($rows['News']['project_id'], "7", "id:" . $rows['News']['id']);
        }

        //デフォルト指定件数返ってくる
        $result = $this->News->latest($user, 2);
        $this->assertEqual(count($result), 2);
        //自分が所属していないプロジェクト(7)がヒットしていない
        foreach ($result as $rows) {
            $this->assertNotEqual($rows['News']['project_id'], "7", "id:" . $rows['News']['id']);
        }

        //デフォルト５件以上返ってくる
        $result = $this->News->latest($user, 10);
        $this->assertTrue(count($result) > 5, "count:" . count($result));
        //自分が所属していないプロジェクト(7)がヒットしていない
        foreach ($result as $rows) {
            $this->assertNotEqual($rows['News']['project_id'], "7", "id:" . $rows['News']['id']);
        }

        //test as anonymous user.
        $user = $this->User->anonymous();
        $result = $this->News->latest($user);
        $this->assertCount(2, $result);

        //make sure all project is public.
        foreach ($result as $rows) {
            $this->assertEqual($rows['Project']['is_public'], 1);
        }

        //test as admin user.
        $user = array();
        $user['logged'] = true;
        $user['memberships'] = array();
        $user['admin'] = true;

        $result = $this->News->latest($user, 10);
        $this->assertCount(8, $result);

        //make sure results contains public and private..
        $public_map = Hash::extract($result, "{n}.Project.is_public");
        $this->assertEqual( $public_map, array(1, 0, 0, 0, 0, 0, 0, 1));

    }
}
