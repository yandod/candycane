<?php
class HookContainer extends Object {

	protected $_elements = array();

	public function registerElementHook($target, $name, $before = false, $plugin = null) {
		if (!array_key_exists($target, $this->_elements)) {
			$this->_elements[$target] = array(
				'before' => null,
				'after' => null
			);
		}
		if ($before) {
			$this->_elements[$target]['before'] = $name;
		} else {
			$this->_elements[$target]['after'] = $name;
		}
		return true;
	}

	public function unregisterElementHook($target, $before = false) {
		if ($before) {
			$this->_elements[$target]['before'] = null;
		} else {
			$this->_elements[$target]['after'] = null;
		}
		return true;
	}

	public function getElementHook($target, $before = false) {
		if (!array_key_exists($target, $this->_elements)) {
			return null;
		}

		if ($before) {
			return $this->_elements[$target]['before'];
		} else {
			return $this->_elements[$target]['after'];
		}
	}

}

