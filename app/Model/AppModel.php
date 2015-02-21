<?php
/**
 * Application Model
 *
 * @package candycane
 */
class AppModel extends Model {

/**
 * Components
 *
 * @var array
 */
	public $actsAs = array(
		'Timestamp',
	);

/**
 * Validation error messages
 *
 * @var array
 */
 	public $error = array(
		'validates_presence_of' => 'Please be sure to input.',
		'validates_uniqueness_of' => 'There are already exists.',
		'validates_length_of' => 'Please input by %2$d or less characters.',
		'validates_format_of' => 'Please input in readable charactors.',
		'validates_inclusion_of' => 'Please input in the range %1$d through %2$d.',
		'validates_numericality_of' => 'Please input the numerical value.',
		'validates_invalid_of' => 'is invalid',
		'validates_uniqueness_of' => 'has already been taken',
		'validates_not_same_project' => 'Doesn\'t belong to the same project.',
		'validates_circular_dependency' => 'This relation would create a circular dependency',
		'validates_not_empty' => 'can\'t be blank',
		'validates_confirmation_of' => 'doesn\'t match confirmation',

		'date_format' => 'Please input in the date format.',
		'compare_from_to' => 'Start time should specify the past from finish time.',
		//'minLength' => 'Please input by %2$d or more characters.',
		'minLength' => 'is too short',
		'email' => 'Please input in mail address form.',
		'harfWidthChar'=> 'Please input a half-width alphanumeric character.',
		'equalPasswords' => 'Invalid Password Confirmation.',
		'select' => 'Please be sure to select.',
		'requireParticipant' => 'Please select a participant.'
	);

/**
 * Constructor
 *
 * Overloaded constructor to provide a mechanism to build validation rules.
 *
 * @param string $id 
 * @param string $table 
 * @param string $ds 
 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->_setupValidation();
	}

/**
 * Default validation setup method, so subclasses don't have to provide empty ones
 * Override this in subclasses to provide validation rules.
 *
 * @return void
 */
	protected function _setupValidation() {
	}

/**
 * Invalid Fields
 *
 * @param array $options Options
 * @return array Errors
 */
	public function invalidFields($options = array()) {
		$errors = parent::invalidFields($options);
		foreach ($errors as $key => $value) {
			$model = false;
			if (is_array($value)) {
				$values = each($value);
				$model = $values['key'];
				$value = $values['value'];
			}
			$rule = array();
			if (!empty($this->validate[$key][$value]['rule'])) {
				$rule = $this->validate[$key][$value]['rule'];
			}
			if (array_key_exists($value, $this->error)) {
				$error = vsprintf(__($this->error[$value]), $rule);
			} else {
				$error = __($value);
			}
			if (!empty($model)) {
				$error = array($model=>$error);
			}
			$errors[$key] = $error;
		}
		$this->validationErrors = $errors;
		return $errors;
	}

/**
 * Before Save Callback
 *
 * @param array $options Options
 * @return boolean True if save should continue
 */
	public function beforeSave($options = array()) {
		$dateFields = array('updated_on');
		if (!isset($this->data[$this->alias][$this->primaryKey])) {
			$dateFields[] = 'created_on';
		}
		if (isset($this->data[$this->alias])) {
			$fields = array_keys($this->data[$this->alias]);
		} else {
			return true;
		}
		$db =& ConnectionManager::getDataSource($this->useDbConfig);
		foreach ($dateFields as $updateCol) {
			if ($this->hasField($updateCol) && !in_array($updateCol, $fields)) {
				$default = array('formatter' => 'date');
				$colType = array_merge($default, $db->columns[$this->getColumnType($updateCol)]);
				if (!array_key_exists('format', $colType)) {
					$time = strtotime('now');
				} else {
					$time = $colType['formatter']($colType['format']);
				}
				if (!empty($this->whitelist)) {
					$this->whitelist[] = $updateCol;
				}
				$this->set($updateCol, $time);
			}
		}
		return true;
	}

/**
 * Parses hours format and returns a float
 *
 * @param string $h Hours
 * @return float Hours as float
 */
	public function to_hours($h) {
		if (preg_match('/^(\d+([.,]\d+)?)h?$/', $h, $matches)) {
			$s = $matches[1];
		} else {
			// 2:30 => 2.5
			$s = !preg_match('/^(\d+):(\d+)$/', $h, $matches) ? false : $matches[1] + ($matches[2] / 60.0);
			// 2h30, 2h, 30m => 2.5, 2, 0.5
			if ($s === false) {
				if (preg_match('/^((\d+)\s*(h|hours)?)?\s*((\d+)\s*(m|min)?)?$/', $h, $matches)) {
					if ((count($matches)>4) && !empty($matches[3]) && ($matches[3][0] == 'h')) {
						$s = $matches[2] + ($matches[5] / 60.0);
					} elseif((count($matches)>2) && !empty($matches[3]) && ($matches[3][0] == 'h')) {
						$s = $matches[2];
					} elseif((count($matches)>4) && !empty($matches[6]) && ($matches[6][0] == 'm')) {
						$s = ($matches[2] * 10 + $matches[5]) / 60.0;
					} else {
						$s = 0;
					}
				}
			}
		}
 		// 2,5 => 2.5
		return str_replace(',', '.', $s);
	}

/**
 * Gets full table name including prefix
 *
 * @param mixed $quote Quote the table name
 * @return string Full quoted table name
 */
	public function fullTableName($quote = true) {  
		$db =& ConnectionManager::getDataSource($this->useDbConfig);
		$model =& $this;
		if (is_string($quote)) {
			$model =& ClassRegistry::init($quote);
			$quote = true;
		}
		return $db->fullTableName($model, $quote);
	}

/**
 * Gets table name including prefix
 * 
 * @param $alias string with the table alias
 * @return string unquoted table name
 */
        public function tableName($alias) {  
		$db =& ConnectionManager::getDataSource($this->useDbConfig);
		$model =& $this;
                $model =& ClassRegistry::init($alias);
                return "{$db->config['prefix']}{$model->table}";
	}
        
/**
 * Quoted date
 *
 * @param string $date Date
 * @param string $colname Column name
 * @return string Quoted date
 */
	public function quoted_date($date, $colname) {
		$db =& ConnectionManager::getDataSource($this->useDbConfig);
		$default = array('formatter' => 'date');
		$colType = array_merge($default, $db->columns[$this->getColumnType($colname)]);
		$time = strtotime($date);
		if (array_key_exists('format', $colType)) {
			$time = $colType['formatter']($colType['format'], strtotime($date));
		}
		return $time;
	}

/**
 * To String
 *
 * @param array $data Data
 * @return string Stringified data
 */
	public function to_string($data = false) {
		$out = '';
		if (!$data) {
			$data = $this->data;
		}
		if (array_key_exists('name', $data[$this->alias])) {
			$out = $data[$this->alias]['name'];
		}
		return $out;
	}

/**
 * Filter Bindings
 *
 * @param array $bindings Bindings
 * @param boolean $reset Reset
 * @return void
 */
	public function filterBindings($bindings = null, $reset = true) { 
		if (empty($bindings) && !is_array($bindings)) {
			return false;
		}
		$relations = array('hasOne', 'hasMany', 'belongsTo', 'hasAndBelongsToMany');
		$unbind = array();
		foreach ($bindings as $binding) {
			foreach ($relations as $relation) {
				if (isset($this->$relation)) {
					$currentRelation = $this->$relation;
					if (isset($currentRelation) && isset($currentRelation[$binding])) {
						$unbind[$relation][] = $binding;
					}
				}
			}
		}
		if (!empty($unbind)) {
			$this->unbindModel($unbind, $reset);
		}
	}
}

/*
class ARCondition
  attr_reader :conditions

  def initialize(condition=nil)
    @conditions = ['1=1']
    add(condition) if condition
  end

  def add(condition)
    if condition.is_a?(Array)
      @conditions.first << " AND (#{condition.first})"
      @conditions += condition[1..-1]
    elsif condition.is_a?(String)
      @conditions.first << " AND (#{condition})"
    else
      raise "Unsupported #{condition.class} condition: #{condition}"
    end
    self
  end

  def <<(condition)
    add(condition)
  end
end*/
