<?php
App::import('View','Theme');
class CandyView extends ThemeView {

	public function __construct (&$controller) {
		parent::__construct($controller);
	}

	function element($name, $params = array(), $loadHelpers = false) {
		$file = $plugin = $key = null;

		if (isset($params['plugin'])) {
			$plugin = $params['plugin'];
		}

		if (isset($this->plugin) && !$plugin) {
			$plugin = $this->plugin;
		}

		if (isset($params['cache'])) {
			$expires = '+1 day';

			if (is_array($params['cache'])) {
				$expires = $params['cache']['time'];
				$key = Inflector::slug($params['cache']['key']);
			} elseif ($params['cache'] !== true) {
				$expires = $params['cache'];
				$key = implode('_', array_keys($params));
			}

			if ($expires) {
				$cacheFile = 'element_' . $key . '_' . $plugin . Inflector::slug($name);
				$cache = cache('views' . DS . $cacheFile, null, $expires);

				if (is_string($cache)) {
					return $cache;
				}
			}
		}
		$paths = $this->_paths($plugin);

		foreach ($paths as $path) {
			if (file_exists($path . 'elements' . DS . $name . $this->ext)) {
				$file = $path . 'elements' . DS . $name . $this->ext;
				break;
			} elseif (file_exists($path . 'elements' . DS . $name . '.thtml')) {
				$file = $path . 'elements' . DS . $name . '.thtml';
				break;
			}
		}

		$hookContainer = ClassRegistry::getObject('HookContainer');
		if (is_file($file)) {
			$params = array_merge_recursive($params, $this->loaded);
			$before = "";
			if ($hookContainer->getElementHook($name,true)) {
				$before = $this->$element($hookContainer->getElementHook($name,true), array(), $loadHelpers);
			}
			$element = $this->_render($file, array_merge($this->viewVars, $params), $loadHelpers);
			$after = "";
			if ($hookContainer->getElementHook($name)) {
				$after = $this->element($hookContainer->getElementHook($name), array(), $loadHelpers);
			}
			if (isset($params['cache']) && isset($cacheFile) && isset($expires)) {
				cache('views' . DS . $cacheFile, $element, $expires);
			}
			return $before.$element.$after;
		}
		$file = $paths[0] . 'elements' . DS . $name . $this->ext;

		if (Configure::read() > 0) {
			return "Not Found: " . $file;
		}
	}

}