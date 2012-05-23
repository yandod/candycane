<?php
class CandyView extends View {
	function element($name, $data = array(), $options = false) {
		
		$element = parent::element($name, $data, $options);
		
		$hookContainer = ClassRegistry::getObject('HookContainer');
		$before = "";
		if ($hookContainer->getElementHook($name,true)) {
			$before = $this->element($hookContainer->getElementHook($name,true), $data, $options);
		}
		$after = "";
		if ($hookContainer->getElementHook($name)) {
			$after = $this->element($hookContainer->getElementHook($name), $data, $options);
		}
		return $before.$element.$after;
	}

}