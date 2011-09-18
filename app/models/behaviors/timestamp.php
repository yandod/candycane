<?php
/**
 * Timestamp Behavior
 *
 * Allows 'created' and 'modified' fields to be written if they are not
 * following the CakePHP standards for field names.
 *
 * @todo Implement functionality for 'created' in afterSave().
 *
 * @package candycane
 * @subpackage candycane.models.behaviors
 */
class TimestampBehavior extends ModelBehavior {
	
/**
 * Default settings
 *
 * 'created' => Created field name
 * 'modified' => Modified field name
 * 'forceModified' => Force the update of the modified field, even if data is
 *                    supplied for the save operation.
 *
 * @var array
 */
	protected $_defaults = array(
		'created' => 'created_on',
 		'modified' => 'updated_on',
		'forceModified' => true,
	);

/**
 * Instance settings
 *
 * @var array
 */
	public $settings = array();

/**
 * Setup the behavior
 *
 * @param Model $Model Model instance
 * @param array $config Configuration options
 * @return void
 */
	public function setup(&$Model, $config) {
		$this->settings[$Model->alias] = array_merge($this->_defaults, $config);
	}

/**
 * Return the settings for a particular model
 *
 * @param string $alias Model alias
 * @return array Settings
 */
	protected function _settings($alias) {
		if (isset($this->settings[$alias])) {
			return $this->settings[$alias];
		}
		return $this->_defaults;
	}

/**
 * Determine if the schema has a "modified" field
 *
 * @param array $schema Model schema
 * @param array $settings Settings
 * @return boolean True if the schema has a modified field
 */
	protected function _hasModified($schema, $settings) {
		return array_key_exists($settings['modified'], $schema);
	}

/**
 * Determine if the schema has a "created" field
 *
 * @param array $schema Model schema
 * @param array $settings Settings
 * @return boolean True if the schema has a created field
 */
	protected function _hasCreated($schema, $settings) {
		return array_key_exists($settings['created'], $schema);
	}

/**
 * Determine if the "modified" field should be set / updated
 *
 * @param Model $Model Model instance
 * @return boolean True if the field should be updated
 */
	protected function _shouldSetModified(&$Model) {
		if ($this->settings[$Model->alias]['forceModified']) {
			return true;
		}

		return !isset($Model->data[$Model->alias][$settings['modified']]);
	}

/**
 * Before Save Callback
 *
 * @param Model $Model Model Instance
 * @return boolean True if save can continue
 */
	public function beforeSave(&$Model) {
		$settings = $this->_settings($Model->alias);
		$schema = $Model->schema();

		if ($this->_hasModified($schema, $settings) && $this->_shouldSetModified($Model)) {
			$Model->data[$Model->alias][$settings['modified']] = $this->_formatForColumn($Model, $settings['modified']);
		}

		return parent::beforeSave($Model);
	}

/**
 * Get time format for the specified column
 *
 * @param Model $Model Model instance
 * @param string $column Column name
 * @return string Formatted time
 */
	protected function _formatForColumn(&$Model, $column) {
		$default = array('formatter' => 'date');
		$db =& ConnectionManager::getDataSource($Model->useDbConfig);
		$colType = array_merge($default, $db->columns[$Model->getColumnType($column)]);
		if (!array_key_exists('format', $colType)) {
			$time = strtotime('now');
		} else {
			$time = $colType['formatter']($colType['format']);
		}
		return $time;
	}
}
