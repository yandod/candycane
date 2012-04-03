<?php
/*
module Redmine
  module Acts
    module ActivityProvider
      def self.included(base)
        base.extend ClassMethods
      end

      module ClassMethods
        def acts_as_activity_provider(options = {})
          unless self.included_modules.include?(Redmine::Acts::ActivityProvider::InstanceMethods)
            cattr_accessor :activity_provider_options
            send :include, Redmine::Acts::ActivityProvider::InstanceMethods
          end

          options.assert_valid_keys(:type, :permission, :timestamp, :author_key, :find_options)
          self.activity_provider_options ||= {}

          # One model can provide different event types
          # We store these options in activity_provider_options hash
          event_type = options.delete(:type) || self.name.underscore.pluralize

          options[:permission] = "view_#{self.name.underscore.pluralize}".to_sym unless options.has_key?(:permission)
          options[:timestamp] ||= "#{table_name}.created_on"
          options[:find_options] ||= {}
          options[:author_key] = "#{table_name}.#{options[:author_key]}" if options[:author_key].is_a?(Symbol)
          self.activity_provider_options[event_type] = options
        end
      end

      module InstanceMethods
        def self.included(base)
          base.extend ClassMethods
        end

        module ClassMethods
          # Returns events of type event_type visible by user that occured between from and to
          def find_events(event_type, user, from, to, options)
            provider_options = activity_provider_options[event_type]
            raise "#{self.name} can not provide #{event_type} events." if provider_options.nil?

            scope_options = {}
            cond = ARCondition.new
            if from && to
              cond.add(["#{provider_options[:timestamp]} BETWEEN ? AND ?", from, to])
            end
            if options[:author]
              return [] if provider_options[:author_key].nil?
              cond.add(["#{provider_options[:author_key]} = ?", options[:author].id])
            end
            cond.add(Project.allowed_to_condition(user, provider_options[:permission], options)) if provider_options[:permission]
            scope_options[:conditions] = cond.conditions
            if options[:limit]
              # id and creation time should be in same order in most cases
              scope_options[:order] = "#{table_name}.id DESC"
              scope_options[:limit] = options[:limit]
            end

            with_scope(:find => scope_options) do
              find(:all, provider_options[:find_options].dup)
            end
          end
        end
      end
    end
  end
end
*/

/**
 * Activity Provider Behavior
 *
 * @package candycane
 * @subpackage candycane.models.behaviors
 */
class ActivityProviderBehavior extends ModelBehavior {

/**
 * Default settings
 *
 * @var array
 */
 	protected $_defaults = array(
		'type' => false, 
		'permission' => false, 
		'timestamp' => false, 
		'author_key' => false, 
		'find_options' => false,
	);

/**
 * Setup
 *
 * @param AppModel $Model Model instance
 * @param array $config Configuration options
 * @return boolean
 */
	public function setup(&$Model, $config = array()) {
		$this->addActivityProvider($Model, $config);
		return true;
	}

/**
 * Add Activity Provider
 *
 * @param Model $Model Model
 * @param array $config Configuration options
 * @return mixed
 */
	public function addActivityProvider(&$Model, $config = array()) {
		$config = array_intersect_key($config, $this->_defaults);

		$alias = Inflector::pluralize(Inflector::underscore($Model->alias));
		$this->_defaults = array(
			'type' => $alias,
			'permission' => "view_{$alias}",
			'timestamp' => "{$Model->alias}.created_on",
			'author_key' => false,
			'find_options' => array(),
		);

		$settings = array_merge($this->_defaults, $config);

		// One model can provide different event types
 		// We store these options in activity_provider_options hash
		$event_type = $settings['type'];
		unset($settings['type']);
		if (!empty($settings['author_key'])) {
			$table_name = $Model->alias;
			$settings['author_key'] = "$table_name.".$settings['author_key'];
		}
		$this->settings[$event_type][$Model->alias] = $settings;
		return true;
	}

 	// Returns events of type event_type visible by user that occured between from and to
	public function find_events(&$Model, $event_type, $user, $from, $to, $options) {
		if (is_numeric($from)) {
			$from = date('Y-m-d H:i:s', $from);
		}
		if (is_numeric($to)) {
			$to = date('Y-m-d H:i:s', $to);
		}

		$provider_options = $this->settings[$event_type][$Model->alias];
		if (empty($provider_options)) {
			return $this->cakeError('error', array('message' => "Can not provide $event_type events."));
		}
		$scope_options = array();
		$cond = new Condition();
		if ($from && $to) {
			$cond->add(array($provider_options['timestamp'] . " BETWEEN ? AND ?" => array($from, $to)));
		}
		if (isset($options['author'])) {
			if (empty($provider_options['author_key'])) {
				return array();
			}
			$cond->add(array($provider_options['author_key'] => $options['author']['id']));
		}
		if (isset($provider_options['permission'])) {
			$project =& ClassRegistry::init('Project');
			$cond->add($project->allowed_to_condition($user, $provider_options['permission'], $options));
		}
		$scope_options['conditions'] = $cond->conditions;
		$scope_options['order'] = $Model->alias.".id DESC";
		if (isset($options['limit']) && $options['limit'] > 1) {
 			// id and creation time should be in same order in most cases
			$scope_options['limit'] = $options['limit'];
		}
		if (isset($provider_options['include']) && !(array_key_exists('CustomField', $provider_options['include']) || in_array('CustomField', $provider_options['include']))) {
			$Model->_customFieldAfterFindDisable = false;
		}

		$values = $Model->find('all', array_merge_recursive($provider_options['find_options'], $scope_options));
		$ret = array();
		list($mname, $cname) = explode('.', $provider_options['timestamp']);
		$_ids = array();
		foreach ($values as $value) {
			if (in_array($value[$mname][$Model->primaryKey], $_ids)) {
				continue;
			}
			$_ids[] = $value[$mname][$Model->primaryKey];
			$time = strtotime($value[$mname][$cname]);
			$day = strtotime(date('Y-m-d 00:00:00', strtotime($value[$mname][$cname])));
			if (!isset($ret[$day])) {
				$ret[$day] = array();
			}
			if (!isset($ret[$day][$time])) {
				$ret[$day][$time] = array();
			}
			$ret[$day][$time][] = $value;
		}

		return $ret;
	}
}

/**
 * Condition Class
 *
 * @package candycane
 * @subpackage candycane.models.behaviors
 */
class Condition {

/**
 * Conditions
 *
 * @var array
 */
 	public $conditions = array();

	public function __construct($condition = false) {
		$this->conditions = array(array('1=1'));
		if (!empty($condition)) {
			$this->add($condition);
		}
	}

/**
 * Add condition
 *
 * @param string $condition 
 * @return void
 * @author Predominant
 */
	public function add($condition) {
		if (is_array($condition)) {
			$this->conditions[] = $condition;
		} elseif(is_string($condition)) {
			$this->conditions[] = array($condition);
		} else {
			return $this->cakeError('error', array('message'=>"Unsupported condition."));
		}
	}
}
