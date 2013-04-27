<?php
App::uses('Permission', 'Model');

/**
 * Permission Test Case
 *
 */
class PermissionTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array();

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Permission = ClassRegistry::init('Permission');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Permission);

		parent::tearDown();
	}

/**
 * testPermission method
 *
 * @return void
 */
	public function testPermission() {

	}
/**
 * testFindByName method
 *
 * @return void
 */
	public function testFindByName() {
		// set()してないやつはemptyか？
		$data = $this->Permission->findByName('hogehoge');
		$this->assertEmpty($data);

		// set()したら、そのデータがfindByNameでとれるか？
    	$this->Permission->set('hogehoge',
			array('projects' => array('show', 'activity')),
			array('public' => true)
		);
		$data = $this->Permission->findByName('hogehoge');
		$this->assertEqual('hogehoge', $data['name']);
	}
/**
 * testAvailableProjectModules method
 *
 * @return void
 */
	public function testAvailableProjectModules() {
		// set()されていないmoduleは存在しないことを確認
		$data = $this->Permission->available_project_modules();
		$this->assertArrayNotHasKey('hoge_mod', $data);
		$this->assertNotContains('hoge_mod', $data);


		// set()したものがmodule一覧に含まれているか確認
    	$this->Permission->set('hoge_mod',
			array('projects' => array('show', 'activity')),
			array('public' => true),
			'hoge_mod'
		);
		$data = $this->Permission->available_project_modules();
		$this->assertArrayHasKey('hoge_mod', $data);
		$this->assertContains('hoge_mod', $data);

	}
/**
 * testSetablePermissionsName method
 *
 * @return void
 */
	public function testSetablePermissionsName() {

		// public===falseのみ取得される
		$data = $this->Permission->setable_permissions_name();
		foreach($data as $key => $val){
			$permission = $this->Permission->findByName( substr($val, 1));
			$this->assertFalse(is_array($val));
			$this->assertFalse($permission['public']);
		}

		// 引数1の場合は require!='member' のみ取得
		$data = $this->Permission->setable_permissions_name(1);
		foreach($data as $key => $val){
			$permission = $this->Permission->findByName( substr($val, 1));
			$this->assertFalse(is_array($val));
			$this->assertFalse($permission['public']);
			$this->assertNotContains($permission['require'], array('member'));
		}

		// 引数1の場合は require!='member' && require!='loggedint' のみ取得
		$data = $this->Permission->setable_permissions_name(2);
		foreach($data as $key => $val){
			$permission = $this->Permission->findByName( substr($val, 1));
			$this->assertFalse(is_array($val));
			$this->assertFalse($permission['public']);
			$this->assertNotContains($permission['require'], array('member', 'loggedin'));
		}

		// setable_permissions_name() で引数なしと 1,2 以外の値の場合は同じになるはず
		$this->assertEquals(
			$this->Permission->setable_permissions_name(),
			$this->Permission->setable_permissions_name(3)
		);
	}
/**
 * testSetablePermissions method
 *
 * @return void
 */
	public function testSetablePermissions() {

		// public===falseのみ取得される
		$data = $this->Permission->setable_permissions();
		$this->assertTrue(is_array($data));
		foreach($data as $module => $perms){
			$this->assertTrue(is_array($data));
			foreach($perms as $p){
				$this->assertFalse($p['public']);
			}
		}

		// 引数1の場合は require!='member' のみ取得
		$data = $this->Permission->setable_permissions(1);
		$this->assertTrue(is_array($data));
		foreach($data as $module => $perms){
			$this->assertTrue(is_array($data));
			foreach($perms as $p){
				$this->assertFalse($p['public']);
				$this->assertNotContains($p['require'], array('member'));
			}
		}

		// 引数1の場合は require!='member' && require!='loggedint' のみ取得
		$data = $this->Permission->setable_permissions(2);
		$this->assertTrue(is_array($data));
		foreach($data as $module => $perms){
			$this->assertTrue(is_array($data));
			foreach($perms as $p){
				$this->assertFalse($p['public']);
				$this->assertNotContains($p['require'], array('member', 'loggedin'));
			}
		}

		// setable_permissions_name() で引数なしと 1,2 以外の値の場合は同じになるはず
		$this->assertEquals(
			$this->Permission->setable_permissions(),
			$this->Permission->setable_permissions(3)
		);

	}
/**
 * testNonPublicPermissions method
 *
 * @return void
 */
	public function testNonPublicPermissions() {
		// public じゃないものだけとってきている？
		$permissions = $this->Permission->non_public_permissions();
		foreach($permissions as $module => $perms){
			foreach($perms as $p){
				$this->assertFalse($p['public']);
			}
		}

	}
/**
 * testPublicPermissions method
 *
 * @return void
 */
	public function testPublicPermissions() {
		// public のものだけとってきている？
		$permissions = $this->Permission->public_permissions();
		foreach($permissions as $module => $perms){
			foreach($perms as $p){
				$this->assertTrue($p['public']);
			}
		}

	}
/**
 * testAllowedActions method
 *
 * @return void
 */
	public function testAllowedActions() {
		// set()されていないmoduleは存在しないことを確認
		$action = $this->Permission->allowed_actions('hogehoge');
		$this->assertEmpty($action);
		$this->assertNotEquals('projects/show', $action);

		// set()したものがmodule一覧に含まれているか確認
    	$this->Permission->set('hogehoge',
			array('projects' => array('show', 'activity')),
			array('public' => true),
			'hoge_mod'
		);
		$action = $this->Permission->allowed_actions('hogehoge');
		$this->assertContains('projects/show', $action);

	}
/**
 * testModulesPermissions method
 *
 * @return void
 */
	public function testModulesPermissions() {

		$get_modules = array('', 'issue_tracking', 'time_tracking');

		$permissions = $this->Permission->modules_permissions($get_modules);
		$this->assertTrue(is_array($permissions));
		foreach($permissions as $module => $perms){
			$this->assertTrue(is_array($perms));
			foreach($perms as $p){
				$this->assertContains($p['project_module'], $get_modules);
			}
		}

	}
}
