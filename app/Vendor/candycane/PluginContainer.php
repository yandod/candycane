<?php
/**
 * PluginContainer
 * manage cakephp plugin based on entry list.
 * shoud not change any plugin this container doesn't know.
 */
class PluginContainer extends Object {

	protected $__entries = array();

	protected $__entries_url = 'https://raw.github.com/yandod/candycane-plugins/master/entries2.json';

	public function  __construct() {
		$this->__init();
		parent::__construct();
	}

	protected function __init() {
		$this->__loadEntry();
	}

	protected function __loadEntry() {
		$this->__entries['cc_octoland'] = array(
			'id' => 'cc_octoland',
			'name' => 'Octoland',
			'description' => 'Collect all octocat stickers on CandyCane for free.',
			'url' => 'https://github.com/yandod/CcOctoland/zipball/master',
			'author' => 'yandod',
			'author_url' => 'https://github.com/yandod',
			'version' => '0.1',
			'installed' => false
		);
	}

	public function getEntries(){
		return $this->__entries;
	}

	public function fetchEntry(){
		$context = stream_context_create(
			array(
				'http' => array(
					'timeout' => 7
				)
			)
		);
		$json = @file_get_contents($this->__entries_url,false,$context);
		if ($json == false) {
			return false;
		}
		$remote = json_decode($json,true);
		$local = $this->__entries;
		foreach ($remote as $id => $entry) {
			if (isset($local[$id])) {
				$entry['installed'] = $local[$id]['installed'];
				$this->updateEntry($id, $entry);
			} else {
				$entry['installed'] = false;
				$this->addEntry($entry);
			}
		}
		return true;
	}
	public function addEntry($entry) {
		$default = array(
			'id' => null,
			'name' => null,
			'description' => null,
			'url' => null,
			'author' => null,
			'author_url' => null,
			'version' => null,
			'installed' => false
		);
		$entry = array_merge($default,$entry);
		if (is_null($entry['id'])) {
			return false;
		}
		$this->__entries[$entry['id']] = $entry;
		return true;
	}

	public function getEntry($id) {
		foreach ($this->__entries as $val) {
			if ($val['id'] === $id) {
				return $val;
			}
		}
		return false;
	}

	public function updateEntry($id,$entry) {
		foreach ($this->__entries as $k => $val) {
			if ($val['id'] === $id) {
				$this->__entries[$k] = $entry;
				return true;
			}
		}
		return false;
	}

	public function install($id) {
		$entry = $this->getEntry($id);
		if ($entry && !empty($entry['url'])) {
			App::uses('File', 'Utility');
			copy($entry['url'],TMP.DS.$id);
			App::import('Vendor', 'PclZip', array('file' => 'pclzip-2-8-2/pclzip.lib.php'));
			$zip = new PclZip(TMP.DS.$id);
			$list = $zip->listContent();
			$zip->extract(TMP);
			unlink(TMP.DS.$id);
			rename(TMP.DS.$list[0]['filename'], APP.'Plugin' . DS . Inflector::camelize($id));
			Cache::clear(false, '_cake_core_');
			CakePlugin::loadAll();
			return true;
		}
		return false;
	}

	public function installed($id, $version) {
		$entry = $this->getEntry($id);
		if ($entry) {
			$entry['installed'] = $version;
			return $this->updateEntry($id, $entry);
		} else {
			$entry = array(
				'id' => $id,
				'name' => $id,
				'installed' => $version
			);
			return $this->addEntry($entry);
		}
		return false;
	}

	public function upgrade($id) {
		return $this->uninstall($id) && $this->install($id);
	}

	public function uninstall($id) {
		$entry = $this->getEntry($id);
		if ($entry && !empty($entry['url'])) {
			App::uses('Folder', 'Utility');
			$folder = new Folder;
			$folder->delete(APP.'Plugin'.DS. Inflector::camelize($id));
			//CakePlugin::unload(Inflector::camelize($id));
			Cache::clear(false, '_cake_core_');
			return true;
		}
		return false;
	}

}

