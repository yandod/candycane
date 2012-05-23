<?php
App::import('Vendor','PluginContainer');
class PluginContainerTest extends CakeTestCase {

	public $autoFixtures = false;

	protected $pluginContainer = null;

	public function startTest(){
		$this->pluginContainer = new PluginContainer();
	}

	public function test_getEntries(){
		$exp = array(
			'cc_nyancat' => array(
				'id' => 'cc_nyancat',
				'name' => 'Nyan Down Chart',
				'description' => 'This plugin make you nyan\'d!!',
				'url' => 'https://github.com/downloads/yandod/cc_nyancat/cc_nyancat-v0.2.zip',
				'author' => 'yandod',
				'author_url' => 'https://github.com/yandod',
				'version' => '0.2',
				'installed' => false
			),
			'cc_epicsax' => array(
				'id' => 'cc_epicsax',
				'name' => 'Epic Sax Guy plugin.',
				'description' => 'You never forget this sax roll.',
				'url' => 'https://github.com/downloads/yandod/cc_epicsax/yandod-cc_epicsax-v0.1-0-gad8a5da.zip',
				'author' => 'yandod',
				'author_url' => 'https://github.com/yandod',
				'version' => '0.1',
				'installed' => false
			)
		);
		$ret = $this->pluginContainer->getEntries();
		$this->assertEqual($ret, $exp, "check entries.");
	}

	public function test_addEntry() {
	}

	public function test_getEntry() {
		$exp = array(
			'id' => 'cc_epicsax',
			'name' => 'Epic Sax Guy plugin.',
			'description' => 'You never forget this sax roll.',
			'url' => 'https://github.com/downloads/yandod/cc_epicsax/yandod-cc_epicsax-v0.1-0-gad8a5da.zip',
			'author' => 'yandod',
			'author_url' => 'https://github.com/yandod',
			'version' => '0.1',
			'installed' => false
		);
		$ret = $this->pluginContainer->getEntry('cc_epicsax');
		$this->assertEqual($ret, $exp);
		$ret = $this->pluginContainer->getEntry('invalid');
		$this->assertFalse($ret);
	}

	public function test_updateEntry() {
		$entry = array(
			'id' => 'cc_epicsax',
			'name' => 'Epic Sax Guy plugin FTW.',
			'description' => 'You never forget this FFFFFF sax roll.',
			'url' => 'https://github.com/downloads/yandod/cc_epicsax/yandod-cc_epicsax-v0.2-0-gad8a5da.zip',
			'author' => 'yandod',
			'author_url' => 'https://github.com/yandod',
			'version' => '0.2',
			'installed' => false
		);
		$result = $this->pluginContainer->updateEntry('cc_epicsax', $entry, 'update entry correctly.');
		$this->assertTrue($result);
		$result = $this->pluginContainer->updateEntry('invalid', $entry, 'update invalid entry.');
		$this->assertFalse($result);
		$result = $this->pluginContainer->getEntry('cc_epicsax');
		$this->assertEqual($result, $entry, 'checking updated entry.');
	}

	public function test_install() {
		$entry = array(
			'id' => 'cc_epicsax',
			'name' => 'Epic Sax Guy plugin FTW.',
			'description' => 'You never forget this FFFFFF sax roll.',
			'url' => 'https://github.com/yandod/cc_epicsax/zipball/v0.1',
			'author' => 'yandod',
			'author_url' => 'https://github.com/yandod',
			'version' => '0.2',
			'installed' => false
		);
		$result = $this->pluginContainer->updateEntry('cc_epicsax', $entry, 'update entry correctly.');
		$this->assertTrue($result);
		$this->pluginContainer->uninstall('cc_epicsax');
		$result = $this->pluginContainer->install('cc_epicsax');
		$this->assertTrue($result);
		$this->assertTrue(file_exists(APP.'plugins/cc_epicsax/init.php'));
	}

	public function test_installed() {
		$this->pluginContainer->uninstall('cc_epicsax');
		$entry = $this->pluginContainer->getEntry('cc_epicsax');
		$this->assertFalse($entry['installed']);
		$result = $this->pluginContainer->installed('cc_epicsax', '10.8.7');
		$this->assertTrue($result);
		$entry = $this->pluginContainer->getEntry('cc_epicsax');
		$this->assertEqual($entry['installed'], '10.8.7');

		$result = $this->pluginContainer->installed('macos', '10.8.7');
		$this->assertTrue($result);
		$entry = $this->pluginContainer->getEntry('macos');
		$this->assertEqual($entry['installed'], '10.8.7');
	}

	public function test_uninstall() {
		$result = $this->pluginContainer->uninstall('cc_epicsax');
		$this->assertTrue($result);
		$this->assertFalse(file_exists(APP.'plugins/cc_epicsax/init.php'));
	}

}
