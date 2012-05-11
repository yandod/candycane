<?php
/**
 * PluginContainer
 * manage cakephp plugin based on entry list.
 * shoud not change any plugin this container doesn't know.
 */
class PluginContainer extends Object {

	protected $__entries = array();

	protected $__entries_url = 'https://raw.github.com/gist/1550522/entries.json';

	public function  __construct() {
		$this->__init();
		parent::__construct();
	}

	protected function __init() {
		$this->__loadEntry();
	}

	protected function __loadEntry() {
		$this->__entries['cc_nyancat'] = array(
			'id' => 'cc_nyancat',
			'name' => 'Nyan Down Chart',
			'description' => 'This plugin make you nyan\'d!!',
			'url' => 'https://github.com/downloads/yandod/cc_nyancat/cc_nyancat-v0.2.zip',
			'author' => 'yandod',
			'author_url' => 'https://github.com/yandod',
			'version' => '0.2',
			'installed' => false
		);
		$this->__entries['cc_epicsax'] = array(
			'id' => 'cc_epicsax',
			'name' => 'Epic Sax Guy plugin.',
			'description' => 'You never forget this sax roll.',
			'url' => 'https://github.com/downloads/yandod/cc_epicsax/yandod-cc_epicsax-v0.1-0-gad8a5da.zip',
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
					'timeout' => 0.2
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
			App::import('Core', 'File');
			copy($entry['url'],TMP.DS.$id);
			App::import('Vendor', 'PclZip', array('file' => 'pclzip-2-8-2/pclzip.lib.php'));
			$zip = new PclZip(TMP.DS.$id);
			$list = $zip->listContent();
			$zip->extract(TMP);
			unlink(TMP.DS.$id);
			rename(TMP.DS.$list[0]['filename'], APP.'Plugin'.DS.$id);
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

	public function uninstall($id) {
		$entry = $this->getEntry($id);
		if ($entry && !empty($entry['url'])) {
			App::import('Core', 'Folder');
			$folder = new Folder;
			return $folder->delete(APP.'Plugin'.DS.$id);
		}
		return false;
	}

}

