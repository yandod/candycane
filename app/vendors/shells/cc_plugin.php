<?php
/**
 * Task class for creating a plugin
 *
 * @package       cake
 * @subpackage    cake.cake.console.libs.tasks
 */
class CcPluginShell extends Shell {
/**
 * initialize
 *
 * @return void
 */
	function initialize() {
		$this->path = APP . 'plugins' . DS;
	}
/**
 * Execution method always used for tasks
 *
 * @return void
 */
	function main() {
		if (empty($this->params['skel'])) {
			$this->params['skel'] = '';
			if (is_dir(CAKE_CORE_INCLUDE_PATH.DS.'cake'.DS.'console'.DS.'libs'.DS.'templates'.DS.'skel') === true) {
				$this->params['skel'] = CAKE_CORE_INCLUDE_PATH.DS.'cake'.DS.'console'.DS.'libs'.DS.'templates'.DS.'skel';
			}
		}

		$plugin = null;

		if (isset($this->args[0])) {
			$plugin = Inflector::camelize($this->args[0]);
			$pluginPath = Inflector::underscore($plugin) . DS;
			$this->bake($plugin);
		} else {
			$this->__interactive($plugin);
		}
	}

/**
 * Interactive interface
 *
 * @access private
 * @return void
 */
	function __interactive($plugin = null) {
		while ($plugin === null || substr($plugin,0,2) !== 'Cc') {
			$plugin = $this->in(__('Enter the name of the plugin in CamelCase format start with "Cc"', true));
		}

		$this->bake($plugin);
	}

/**
 * Bake the plugin, create directories and files
 *
 * @params $plugin name of the plugin in CamelCased format
 * @access public
 * @return bool
 */
	function bake($plugin) {

		$pluginPath = Inflector::underscore($plugin);

		if ( substr($pluginPath,0,3) !== 'cc_' ) {
			$this->err('Plugin name must start with "Cc"');
			return;
		}

		$this->hr();
		$this->out("Plugin Name: $plugin");
		$this->out("Plugin Directory: {$this->path}{$pluginPath}");
		$this->hr();


		$looksGood = $this->in('Look okay?', array('y', 'n', 'q'), 'y');

		if (strtolower($looksGood) == 'y' || strtolower($looksGood) == 'yes') {
			$verbose = $this->in(__('Do you want verbose output?', true), array('y', 'n'), 'n');

			$Folder = new Folder($this->path . $pluginPath);
			$directories = array('models' . DS . 'behaviors', 'controllers' . DS . 'components', 'views' . DS . 'helpers');

			foreach ($directories as $directory) {
				$Folder->create($this->path . $pluginPath . DS . $directory);
			}

			if (strtolower($verbose) == 'y' || strtolower($verbose) == 'yes') {
				foreach ($Folder->messages() as $message) {
					$this->out($message);
				}
			}

			$errors = $Folder->errors();
			if (!empty($errors)) {
				return false;
			}

			$controllerFileName = $pluginPath . '_app_controller.php';

			$out = "<?php\n\n";
			$out .= "class {$plugin}AppController extends AppController {\n\n";
			$out .= "}\n\n";
			$out .= "?>";
			$this->createFile($this->path . $pluginPath. DS . $controllerFileName, $out);

			$modelFileName = $pluginPath . '_app_model.php';

			$out = "<?php\n\n";
			$out .= "class {$plugin}AppModel extends AppModel {\n\n";
			$out .= "}\n\n";
			$out .= "?>";
			$this->createFile($this->path . $pluginPath . DS . $modelFileName, $out);

			$out = "<?php\n";
			$out .= "\$pluginContainer = ClassRegistry::getObject('PluginContainer');\n";
			$out .= "\$pluginContainer->installed('{$pluginPath}','0.1');\n";
			$out .= "\n\n";
			$this->createFile($this->path . $pluginPath . DS . 'init.php', $out);


			$this->hr();
			$this->out(sprintf(__("Created: %s in %s", true), $plugin, $this->path . $pluginPath));
			$this->hr();
		}

		return true;
	}
/**
 * Help
 *
 * @return void
 * @access public
 */
	function help() {
		$this->hr();
		$this->out("Usage: cake cc_plugin <name>");
		$this->hr();
		$this->out('Commands:');
		$this->out("\n\tcc_plugin <name>\n\t\tbakes plugin directory structure and init.php.");
		$this->out("");
		$this->_stop();
	}
}
