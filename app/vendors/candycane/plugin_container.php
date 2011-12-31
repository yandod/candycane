<?php
class PluginContainer extends Object{

	protected $__entries = array();

	public function  __construct() {
		$this->__init();
		parent::__construct();
	}

	protected function __init() {
		$this->__loadEntry();
	}

	protected function __loadEntry() {
		$this->__entries[] = array(
			'id' => 'cc_nyancat',
			'name' => 'Nyan Down Chart',
			'description' => 'This plugin make you nyan\'d!!',
			'url' => 'http://github.com/yandod/cc_nyancat/zipball/master',
			'author' => 'yandod',
			'author_url' => 'https://github.com/yandod',
			'version' => '0.1',
			'installed' => false
		);
	}

	public function getEntries(){
		return $this->__entries;
	}

	public function addEntry() {
		
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
			}
		}
		return false;
	}

	public function install($id) {
		if ($entry = $this->getEntry($id)) {
			App::import('Core', 'File');
			copy($entry['url'],TMP.DS.$id);
			App::import('Vendor', 'PclZip', array('file' => 'pclzip-2-8-2/pclzip.lib.php'));
			$zip = new PclZip(TMP.DS.$id);
			$list = $zip->listContent();
			$zip->extract(TMP);
			unlink(TMP.DS.$id);
			rename(TMP.DS.$list[0]['filename'], APP.'plugins'.DS.$id);
			return true;
		}
		return false;
	}

	public function installed($id, $version) {
		if ($entry = $this->getEntry($id)) {
			$entry['installed'] = $version;
			$this->updateEntry($id, $entry);
			return true;
		}
		return false;
	}

	public function uninstall($id) {
		if ($this->getEntry($id)) {
			App::import('Core', 'Folder');
			$folder = new Folder;
			return $folder->delete(APP.'plugins'.DS.$id);
		}
		return false;
	}

	public function upgrade($id){
		$this->uninstall($id);
		$this->install($id);
	}
}

