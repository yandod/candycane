<?php
App::uses('PluginContainer', 'Vendor');

class PluginContainerTest extends CakeTestCase {

	public $autoFixtures = false;

	protected $pluginContainer = null;

	public function setUp() {
		$this->pluginContainer = new PluginContainer();
	}

	public function test_getEntries() {
		$exp = array(
			'cc_octoland' => array(
			'id' => 'cc_octoland',
			'name' => 'Octoland',
			'description' => 'Collect all octocat stickers on CandyCane for free.',
			'url' => 'https://github.com/yandod/CcOctoland/zipball/master',
			'author' => 'yandod',
			'author_url' => 'https://github.com/yandod',
			'version' => '0.1',
			'installed' => false
			)
		);
		$ret = $this->pluginContainer->getEntries();
		$this->assertEqual($ret, $exp, "check entries.");
	}

	public function test_fetchEntry() {
	}

	public function test_addEntry() {
		$entry = array();
		$this->assertFalse($this->pluginContainer->addEntry($entry));

		$entry = array(
			'id' => 1,
		);
		$this->assertTrue($this->pluginContainer->addEntry($entry));
	}

	public function test_getEntry() {
		$exp = array(
			'id' => 'cc_octoland',
			'name' => 'Octoland',
			'description' => 'Collect all octocat stickers on CandyCane for free.',
			'url' => 'https://github.com/yandod/CcOctoland/zipball/master',
			'author' => 'yandod',
			'author_url' => 'https://github.com/yandod',
			'version' => '0.1',
			'installed' => false
		);
		$ret = $this->pluginContainer->getEntry('cc_octoland');
		$this->assertEqual($ret, $exp);
		$ret = $this->pluginContainer->getEntry('invalid');
		$this->assertFalse($ret);
	}

	public function test_updateEntry() {
		$entry = array(
			'id' => 'cc_octoland',
			'name' => 'Epic Sax Guy plugin FTW.',
			'description' => 'You never forget this FFFFFF sax roll.',
			'url' => 'https://github.com/downloads/yandod/cc_epicsax/yandod-cc_epicsax-v0.2-0-gad8a5da.zip',
			'author' => 'yandod',
			'author_url' => 'https://github.com/yandod',
			'version' => '0.2',
			'installed' => false
		);
		$result = $this->pluginContainer->updateEntry('cc_octoland', $entry, 'update entry correctly.');
		$this->assertTrue($result);
		$result = $this->pluginContainer->updateEntry('invalid', $entry, 'update invalid entry.');
		$this->assertFalse($result);
		$result = $this->pluginContainer->getEntry('cc_octoland');
		$this->assertEqual($result, $entry, 'checking updated entry.');
	}

	public function test_install() {
		$entry = array(
			'id' => 'cc_octoland',
			'name' => 'Epic Sax Guy plugin FTW.',
			'description' => 'You never forget this FFFFFF sax roll.',
			'url' => 'https://github.com/yandod/cc_epicsax/zipball/v0.1',
			'author' => 'yandod',
			'author_url' => 'https://github.com/yandod',
			'version' => '0.2',
			'installed' => false
		);
		$result = $this->pluginContainer->updateEntry('cc_octoland', $entry, 'update entry correctly.');
		$this->assertTrue($result);
		$this->pluginContainer->uninstall('cc_octoland');
		$result = $this->pluginContainer->install('cc_octoland');
		$this->assertTrue($result);
		$this->assertTrue(file_exists(APP.'Plugin/CcOctoland/init.php'));

		$result = $this->pluginContainer->install('dummy');
		$this->assertFalse($result);
	}

	public function test_installed() {
		$this->pluginContainer->uninstall('cc_octoland');
		$entry = $this->pluginContainer->getEntry('cc_octoland');
		$this->assertFalse($entry['installed']);
		$result = $this->pluginContainer->installed('cc_octoland', '10.8.7');
		$this->assertTrue($result);
		$entry = $this->pluginContainer->getEntry('cc_octoland');
		$this->assertEqual($entry['installed'], '10.8.7');

		$result = $this->pluginContainer->installed('macos', '10.8.7');
		$this->assertTrue($result);
		$entry = $this->pluginContainer->getEntry('macos');
		$this->assertEqual($entry['installed'], '10.8.7');
	}

	public function test_upgrade() {
		$result = $this->pluginContainer->upgrade('cc_octoland');
		$this->assertTrue($result);

		$result = $this->pluginContainer->upgrade('dummy');
		$this->assertFalse($result);
	}

	public function test_uninstall() {
		$result = $this->pluginContainer->uninstall('cc_octoland');
		$this->assertTrue($result);
		$this->assertFalse(file_exists(APP.'Plugin/CcOctoland/init.php'));

		$result = $this->pluginContainer->uninstall('dummy');
		$this->assertFalse($result);
	}
}
