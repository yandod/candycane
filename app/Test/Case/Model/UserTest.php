<?php
App::uses('User', 'Model');
App::uses('Security', 'Utility');

/**
 * User Test Case
 *
 */
class UserTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
    public $fixtures = array('app.user', 'app.token', 'app.user_preference', 'app.member', 'app.project', 'app.wiki', 'app.wiki_page', 'app.wiki_content', 'app.wiki_content_version', 'app.wiki_redirect', 'app.issue_category', 'app.version', 'app.issue', 'app.issue_status', 'app.enumeration', 'app.tracker', 'app.workflow', 'app.time_entry', 'app.changeset', 'app.changesets_issue', 'app.enabled_module', 'app.projects_tracker', 'app.custom_field', 'app.custom_fields_project', 'app.role');

/**
 * setUp method
 *
 * @return void
 */
    public function setUp() {
        parent::setUp();
        $this->User = ClassRegistry::init('User');
    }

/**
 * tearDown method
 *
 * @return void
 */
    public function tearDown() {
        unset($this->User);

        parent::tearDown();
    }

/**
 * testValidatesConfirmationOf method
 *
 * @return void
 */
    public function testValidatesConfirmationOf() {
        $password = 'foo';
        $params = array(
            'password' => $password,
        );
        $this->User->data = array();
        $this->assertTrue($this->User->validates_confirmation_of($params, null));

        $this->User->data[$this->User->alias]['password'] = $password;
        $this->User->data[$this->User->alias]['password_confirmation'] = 'bar';
        $this->assertFalse($this->User->validates_confirmation_of($params, null));

        $this->User->data[$this->User->alias]['password_confirmation'] = $password;
        $this->assertTrue($this->User->validates_confirmation_of($params, null));
    }
/**
 * testName method
 *
 * @return void
 */
    public function testName() {
        $data = array(
            'User' => array(
                'firstname' => 'foo',
                'lastname' => 'bar',
            ),
        );

        $results = $this->User->name($data);
        $this->assertEqual('foo bar', $results);

        // @TODO fix formatter
        $formatter = 'formatter';
        $results = $this->User->name($data, $formatter);
        $this->assertEqual('foo bar', $results);
    }
/**
 * testNameFields method
 *
 * @return void
 */
    public function testNameFields() {
        $expected = array('firstname', 'lastname');
        $this->assertEqual($expected, $this->User->name_fields());
    }
/**
 * testCheckPassword method
 *
 * @return void
 */
    public function testCheckPassword() {
        $password = 'foo';
        $user = array(
            'hashed_password' => 'bar',
        );
        $this->assertFalse($this->User->check_password($password, $user));

        $user['hashed_password'] = $this->User->hash_password($password);
        $this->assertTrue($this->User->check_password($password, $user));
    }
/**
 * testRssKey method
 *
 * @return void
 */
    public function testRssKey() {
        $this->assertNull($this->User->rss_key(1000));

        $data = array(
            'action' => 'feeds',
            'value' => 'foo',
            'user_id' => 1,
        );
        $this->User->RssToken->save($data);
        $this->assertEqual('foo', $this->User->rss_key(1));
    }
/**
 * testNotifiedProjectsIds method
 *
 * @return void
 */
    public function testNotifiedProjectsIds() {
        // @see User::set_notified_project_ids()
    }
/**
 * testSetNotifiedProjectIds method
 *
 * @return void
 */
    public function testSetNotifiedProjectIds() {
        $user_id = 2;
        $this->assertEqual(array(), $this->User->notified_projects_ids($user_id));

        $this->assertEqual(array(1), $this->User->set_notified_project_ids(1, $user_id));
        $this->assertEqual(array(1, 2), $this->User->set_notified_project_ids(array(1, 2), $user_id));

        $this->assertEqual(array(1, 2), $this->User->notified_projects_ids($user_id));
    }
/**
 * testFindByRssKey method
 *
 * @return void
 */
    public function testFindByRssKey() {
        $this->assertNull($this->User->find_by_rss_key('foo'));

        $results = $this->User->find_by_rss_key('DwMJ2yIxBNeAk26znMYzYmz5dAiIina0GFrPnGTM');
        $this->assertEqual(1, $results['id']);
    }
/**
 * testFindByApiKey method
 *
 * @return void
 */
    public function testFindByApiKey() {
        $this->assertNull($this->User->find_by_api_key('foo'));

        $results = $this->User->find_by_api_key('DwMJ2yIxBNeAk26znMYzYmz5dAiIina0GFrPnGTM');
        $this->assertEqual(1, $results['id']);
        $this->assertTrue($results['logged']);
    }
/**
 * testFindByIdLogged method
 *
 * @return void
 */
    public function testFindByIdLogged() {
        $this->assertFalse($this->User->find_by_id_logged(1000));

        $id = 1;
        $results = $this->User->find_by_id_logged($id);
        $this->assertTrue($results['logged']);
        $this->assertEqual($id, $results['id']);
        $this->assertEqual('admin', $results['name']);
        $this->assertEqual(array(), $results['memberships']);
        $this->assertEqual(array(), $results['RssToken']);
        $this->assertEqual(array(), $results['ApiToken']);
        $this->assertEqual($id, $results['UserPreference']['user_id']);
    }
/**
 * testToString method
 *
 * @return void
 */
    public function testToString() {
        $data = array(
            'User' => array(
                'firstname' => 'foo',
                'lastname' => 'bar',
            ),
        );
        $results = $this->User->to_string($data);
        $this->assertEqual('foo bar', $results);

        $this->User->data = $data;
        $results = $this->User->to_string();
        $this->assertEqual('foo bar', $results);
    }
/**
 * testIsAllowedTo method
 *
 * @return void
 */
    public function testIsAllowedTo() {
    }
/**
 * testAnonymous method
 *
 * @return void
 */
    public function testAnonymous() {
        $results = $this->User->anonymous();
        $expected = array(
            'User' => array(
                'id' => '',
                'lastname'  => 'Anonymous',
                'firstname' => '',
                'mail'      => '',
                'login'     => '',
                'status'    => 0,
                'admin' => false
            ),
        );
        $this->assertEqual($expected, $results);

        $id = 1;
        $this->User->id = $id;
        $this->User->saveField('status', USER_STATUS_ANONYMOUS);
        $this->User->create();
        $results = $this->User->anonymous();
        $this->assertEqual($id, $results['User']['id']);
    }
/**
 * testBeforeCreate method
 *
 * @return void
 */
    public function testBeforeCreate() {
        $this->User->data[$this->User->alias]['mail_notification'] = 1;
        $this->User->beforeCreate();
        $results = $this->User->data[$this->User->alias]['mail_notification'];
        $this->assertEqual(0, $results);
    }
/**
 * testBeforeSave method
 *
 * @return void
 */
    public function testBeforeSave() {
        $password = 'foobar';
        $this->User->data[$this->User->alias]['password'] = $password;
        $this->User->beforeSave();
        $results = $this->User->data[$this->User->alias]['hashed_password'];
        $this->assertEqual(sha1($password), $results);
    }
/**
 * testHashPassword method
 *
 * @return void
 */
    public function testHashPassword() {
        $password = 'foobar';
        $this->assertEqual(sha1($password), $this->User->hash_password($password));
    }
/**
 * testTryToLogin method
 *
 * @return void
 */
    public function testTryToLogin() {
        $login = 'admin';
        $password = 'foobar';
        $this->assertFalse($this->User->tryToLogin($login, ''));
        $this->assertFalse($this->User->tryToLogin($login, $password));

        $id = 1;
        $this->User->id = $id;
        $this->User->saveField('hashed_password', Security::hash($password));
        $this->User->create();
        $results = $this->User->tryToLogin($login, $password);
        $this->assertEqual($id, $results['id']);

        $lockedLogin = 'dlopper2';
        $this->assertFalse($this->User->tryToLogin($lockedLogin, $password));
    }
/**
 * testUpdateAttribute method
 *
 * @return void
 */
    public function testUpdateAttribute() {
        $id = 1;
        $lastLoginOn = date('Y-m-d H:i:s');
        $user = array(
            'id' => $id,
        );
        $this->User->updateAttribute($user, $lastLoginOn);

        $results = $this->User->findById($id);
        $this->assertEqual($lastLoginOn, $results['User']['last_login_on']);
    }
/**
 * testRoleForProject method
 *
 * @return void
 */
    public function testRoleForProject() {
        $anonymousRollId = 5;
        $user = $project = array();
        $this->assertEqual($anonymousRollId, $this->User->role_for_project($user, $project));

        $user['status'] = USER_STATUS_ANONYMOUS;
        $this->assertEqual($anonymousRollId, $this->User->role_for_project($user, $project));


        $statuses = array(
            USER_STATUS_REGISTERED,
            USER_STATUS_ACTIVE,
            USER_STATUS_LOCKED,
        );

        $noMemberRollId = 4;
        foreach ($statuses as $status) {
            $user['status'] = $status;
            $this->assertEqual($noMemberRollId, $this->User->role_for_project($user, $project));
        }

        $roleId = 2;
        $projectId = 100;
        $user['memberships'] = array(
            array('project_id' => $projectId, 'role_id' => $roleId),
        );
        $project['Project']['id'] = $projectId;
        foreach ($statuses as $status) {
            $user['status'] = $status;
            $this->assertEqual($roleId, $this->User->role_for_project($user, $project));
        }
    }
/**
 * testIsActive method
 *
 * @return void
 */
    public function testIsActive() {
        $this->assertTrue($this->User->is_active(1));
        $this->assertFalse($this->User->is_active(1000));

        $this->User->data[$this->User->alias]['status'] = USER_STATUS_LOCKED;
        $this->assertFalse($this->User->is_active());

        $this->User->data[$this->User->alias]['status'] = USER_STATUS_ACTIVE;
        $this->assertTrue($this->User->is_active());
    }
}
