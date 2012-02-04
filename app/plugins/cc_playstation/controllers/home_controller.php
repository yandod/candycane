<?php
	class HomeController extends CcPlaystationAppController {
	public $uses = array('Issue');
	public function index() {
		$this->set('count',$this->Issue->find('count'));
	}
}

