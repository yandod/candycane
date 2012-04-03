<?php
/**
 * Copyright 2009-2010, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2009-2010, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Searchable behavior
 *
 * @package		plugins.search
 * @subpackage	plugins.search.models.behaviors
 */
class SearchableBehavior extends ModelBehavior {

/**
 * settings indexed by model name.
 *
 * @var array
 */
	public $settings = array();

/**
 * Default settings
 *
 * @var string
 */
	protected $_defaults = array();

/**
 * Configuration of model
 *
 * @param AppModel $model
 * @param array $config
 */
	public function setup(Model $model, $config = array()) {
		$this->settings[$model->alias] = array_merge($this->_defaults, $config);
	}

/**
 * parseCriteria
 * parses the GET data and returns the conditions for the find('all')/paginate
 * we are just going to test if the params are legit
 *
 * @param array $data Criteria of key->value pairs from post/named parameters
 * @return array Array of conditions that express the conditions needed for the search.
 */
	public function parseCriteria(Model $model, $data) {
		$conditions = array();
		foreach ($model->filterArgs as $field) {
			if (in_array($field['type'], array('string', 'like'))) {
				$this->_addCondLike($model, $conditions, $data, $field);
			} elseif (in_array($field['type'], array('int', 'value'))) {
				$this->_addCondValue($model, $conditions, $data, $field);
			} elseif ($field['type'] == 'expression') {
				$this->_addCondExpression($model, $conditions, $data, $field);
			} elseif ($field['type'] == 'query') {
				$this->_addCondQuery($model, $conditions, $data, $field);
			} elseif ($field['type'] == 'subquery') {
				$this->_addCondSubquery($model, $conditions, $data, $field);
			}
		}
		return $conditions;
	}

/**
 * Validate search
 *
 * @param object Model
 * @return boolean always true
 */
	public function validateSearch(Model $model, $data = null) {
		if (!empty($data)) {
			$model->set($data);
		}
		$keys = array_keys($model->data[$model->alias]);
		foreach ($keys as $key) {
			if (empty($model->data[$model->alias][$key])) {
				unset($model->data[$model->alias][$key]);
			}
		}
		return true;
	}

/**
 * filter retrieving variables only that present in  Model::filterArgs
 *
 * @param object Model
 * @param array $vars
 * @return array, filtered args
 */
	public function passedArgs(Model $model, $vars) {
		$result = array();
		foreach ($vars as $var => $val) {
			if (in_array($var, Set::extract($model->filterArgs, '{n}.name'))) {
				$result[$var] = $val;
			}
		}
		return $result;
	}

/**
 * Method to generated DML SQL queries using find* style.
 *
 * Specifying 'fields' for new-notation 'list':
 *  - If no fields are specified, then 'id' is used for key and Model::$displayField is used for value.
 *  - If a single field is specified, 'id' is used for key and specified field is used for value.
 *  - If three fields are specified, they are used (in order) for key, value and group.
 *  - Otherwise, first and second fields are used for key and value.
 *
 * @param array $conditions SQL conditions array, or type of find operation (all / first / count / neighbors / list / threaded)
 * @param mixed $fields Either a single string of a field name, or an array of field names, or options for matching
 * @param string $order SQL ORDER BY conditions (e.g. "price DESC" or "name ASC")
 * @param integer $recursive The number of levels deep to fetch associated records
 * @return string SQL query string.
 * @link http://book.cakephp.org/view/449/find
 */
	public function getQuery(Model $model, $conditions = null, $fields = array(), $order = null, $recursive = null) {
		if (!is_string($conditions) || (is_string($conditions) && !array_key_exists($conditions, $model->_findMethods))) {
			$type = 'first';
			$query = compact('conditions', 'fields', 'order', 'recursive');
		} else {
			list($type, $query) = array($conditions, $fields);
		}

		$db =& ConnectionManager::getDataSource($model->useDbConfig);
		$model->findQueryType = $type;
		$model->id = $model->getID();

		$query = array_merge(
			array(
				'conditions' => null, 'fields' => null, 'joins' => array(), 
				'limit' => null, 'offset' => null, 'order' => null, 'page' => null, 
				'group' => null, 'callbacks' => true
			),
			(array)$query
		);

		if ($type != 'all') {
			if ($model->_findMethods[$type] === true) {
				$query = $model->{'_find' . ucfirst($type)}('before', $query);
			}
		}

		if (!is_numeric($query['page']) || intval($query['page']) < 1) {
			$query['page'] = 1;
		}
		if ($query['page'] > 1 && !empty($query['limit'])) {
			$query['offset'] = ($query['page'] - 1) * $query['limit'];
		}
		if ($query['order'] === null && $model->order !== null) {
			$query['order'] = $model->order;
		}
		$query['order'] = array($query['order']);


		if ($query['callbacks'] === true || $query['callbacks'] === 'before') {
			$return = $model->Behaviors->trigger($model, 'beforeFind', array($query), array(
				'break' => true, 'breakOn' => false, 'modParams' => true
			));
			$query = (is_array($return)) ? $return : $query;

			if ($return === false) {
				return null;
			}

			$return = $model->beforeFind($query);
			$query = (is_array($return)) ? $return : $query;

			if ($return === false) {
				return null;
			}
		}
		return $this->__queryGet($model, $query, $recursive);
	}

/**
 * Clear all associations
 *
 * @param AppModel $model
 * @param bool $reset
 */
	public function unbindAllModels(Model $model, $reset = false) {
		$assocs = array('belongsTo', 'hasOne', 'hasMany', 'hasAndBelongsToMany');
		$unbind = array();
		foreach ($assocs as $assoc) {
		  $unbind[$assoc] = array_keys($model->{$assoc});
		}
		$model->unbindModel($unbind, $reset);
	}

/**
 * Add Conditions based on fuzzy comparison
 *
 * @param AppModel $model Reference to the model
 * @param array $conditions existing Conditions collected for the model
 * @param array $data Array of data used in search query
 * @param array $field Field definition information
 * @return array of conditions.
 */
	protected function _addCondLike(Model $model, &$conditions, $data, $field) {
		$fieldName = $field['name'];
		if (isset($field['field'])) {
			$fieldName = $field['field'];
		}
		if (strpos($fieldName, '.') === false) {
			$fieldName = $model->alias . '.' . $fieldName;
		}
		if (!empty($data[$field['name']])) {
			$conditions[$fieldName . " LIKE"] = "%" . $data[$field['name']] . "%";
		}
		return $conditions;
	}

/**
 * Add Conditions based on exact comparison
 *
 * @param AppModel $model Reference to the model
 * @param array $conditions existing Conditions collected for the model
 * @param array $data Array of data used in search query
 * @param array $field Field definition information
 * @return array of conditions.
 */
	protected function _addCondValue(Model $model, &$conditions, $data, $field) {
		$fieldName = $field['name'];
		if (isset($field['field'])) {
			$fieldName = $field['field'];
		}
		if (strpos($fieldName, '.') === false) {
			$fieldName = $model->alias . '.' . $fieldName;
		}
		if (!empty($data[$field['name']]) || (isset($data[$field['name']]) && ($data[$field['name']] === 0 || $data[$field['name']] === '0'))) {
			$conditions[$fieldName] = $data[$field['name']];
		}
		return $conditions;
	}

/**
 * Add Conditions based query to search conditions.
 *
 * @param Object $model  Instance of AppModel
 * @param array $conditions Existing conditions.
 * @param array $data Data for a field.
 * @param array $field Info for field.
 * @return array of conditions modified by this method.
 */
	protected function _addCondQuery(Model $model, &$conditions, $data, $field) {
		if ((method_exists($model, $field['method']) || $this->__checkBehaviorMethods($model, $field['method'])) && !empty($data[$field['name']])) {
			$conditionsAdd = $model->{$field['method']}($data);
			$conditions = array_merge($conditions, (array)$conditionsAdd);
		}
		return $conditions;
	}

/**
 * Add Conditions based expressions to search conditions.
 *
 * @param Object $model  Instance of AppModel
 * @param array $conditions Existing conditions.
 * @param array $data Data for a field.
 * @param array $field Info for field.
 * @return array of conditions modified by this method.
 */
	protected function _addCondExpression(Model $model, &$conditions, $data, $field) {
		$fieldName = $field['field'];
		if ((method_exists($model, $field['method']) || $this->__checkBehaviorMethods($model, $field['method'])) && !empty($data[$field['name']])) {
			$fieldValues = $model->{$field['method']}($data, $field);
			if (!empty($conditions[$fieldName]) && is_array($conditions[$fieldName])) {
				$conditions[$fieldName] = array_unique(array_merge(array($conditions[$fieldName]), array($fieldValues)));
			} else {
				$conditions[$fieldName] = $fieldValues;
			}
		}
		return $conditions;
	}

/**
 * Add Conditions based subquery to search conditions.
 *
 * @param Object $model  Instance of AppModel
 * @param array $conditions Existing conditions.
 * @param array $data Data for a field.
 * @param array $field Info for field.
 * @return array of conditions modified by this method.
 */
	protected function _addCondSubquery(Model $model, &$conditions, $data, $field) {
		$fieldName = $field['field'];
		if ((method_exists($model, $field['method']) || $this->__checkBehaviorMethods($model, $field['method'])) && !empty($data[$field['name']])) {
			$subquery = $model->{$field['method']}($data);
			$conditions[] = array("$fieldName in ($subquery)");
		}
		return $conditions;
	}

/**
 * Helper method for getQuery.
 * extension of dbosource method. Create association query.
 *
 * @param AppModel $model
 * @param array $queryData
 * @param integer $recursive
 */
	private function __queryGet(Model $model, $queryData = array(), $recursive = null) {
		$db =& ConnectionManager::getDataSource($model->useDbConfig);
		$db->__scrubQueryData($queryData);
		$null = null;
		$array = array();
		$linkedModels = array();
		$db->__bypass = false;
		$db->__booleans = array();

		if ($recursive === null && isset($queryData['recursive'])) {
			$recursive = $queryData['recursive'];
		}

		if (!is_null($recursive)) {
			$_recursive = $model->recursive;
			$model->recursive = $recursive;
		}

		if (!empty($queryData['fields'])) {
			$db->__bypass = true;
			$queryData['fields'] = $db->fields($model, null, $queryData['fields']);
		} else {
			$queryData['fields'] = $db->fields($model);
		}

		foreach ($model->__associations as $type) {
			foreach ($model->{$type} as $assoc => $assocData) {
				if ($model->recursive > -1) {
					$linkModel =& $model->{$assoc};

					$external = isset($assocData['external']);
					if ($model->alias == $linkModel->alias && $type != 'hasAndBelongsToMany' && $type != 'hasMany') {
						if (true === $db->generateSelfAssociationQuery($model, $linkModel, $type, $assoc, $assocData, $queryData, $external, $null)) {
							$linkedModels[] = $type . '/' . $assoc;
						}
					} else {
						if ($model->useDbConfig == $linkModel->useDbConfig) {
							if (true === $db->generateAssociationQuery($model, $linkModel, $type, $assoc, $assocData, $queryData, $external, $null)) {
								$linkedModels[] = $type . '/' . $assoc;
							}
						}
					}
				}
			}
		}
		return $db->generateAssociationQuery($model, $null, null, null, null, $queryData, false, $null);
	}

/**
 * Check if model have some method in attached behaviors
 *
 * @param Model $Model
 * @param string $method
 * @return boolean, true if method exists in attached and enabled behaviors
 */
	private function __checkBehaviorMethods(Model $Model, $method) {
		$behaviors = $Model->Behaviors->enabled();
		$count = count($behaviors);
		$found = false;
		for ($i = 0; $i < $count; $i++) {
			$name = $behaviors[$i];
			$methods = get_class_methods($Model->Behaviors->{$name});
			$check = array_flip($methods);
			$found = isset($check[$method]);
			if ($found) {
				return true;
			}
		}
		return $found;
	}
}
