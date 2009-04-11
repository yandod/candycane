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
class ActivityProviderBehavior extends ModelBehavior {
  var $_defaults = array(
    'type'=>false, 
    'permission'=>false, 
    'timestamp'=>false, 
    'author_key'=>false, 
    'find_options'=>false);

  function setup(&$Model, $config = array()) {
    $table_name = $Model->table;
    $alias = Inflector::pluralize(Inflector::underscore($Model->alias));
    $this->_defaults = array(
      'type'=>$alias, 
      'permission'=>"view_$alias", 
      'timestamp'=>"$table_name.created_on", 
      'author_key'=>false, 
      'find_options'=>array()
    );

    $diff = array_diff_key($config, $this->_defaults);
    if(!empty($diff)) {
      return false;
    }
    $settings = array_merge($this->_defaults, $config);

    # One model can provide different event types
    # We store these options in activity_provider_options hash
    $event_type = $settings['type'];
    unset($settings['type']);
    if(!empty($settings['author_key'])) {
      $settings['author_key'] = "$table_name.".$settings['author_key'];
    }
    $this->settings[$event_type] = $settings;
    return true;
  }

  # Returns events of type event_type visible by user that occured between from and to
  function find_events(&$Model, $event_type, $user, $from, $to, $options) {
    $provider_options = $this->settings[$event_type];
    if(empty($provider_options)) {
      return $this->cakeError('error', "Can not provide $event_type events.");
    }
    $scope_options = array();
    $cond = new Condition();
    if($from && $to) {
      $cond->add(array($this->settings['timestamp']." BETWEEN ? AND ?"=>array($from, $to)));
    }
    if(isset($options['author'])) {
      if(empty($provider_options['author_key'])) {
        return array();
      }
      $cond->add(array($this->settings['author_key'] => $options['author']['id']));
    }
    if(isset($options['permission'])) {
      $project = & ClassRegistry::init('Project');
      $cond->add($project->allowed_to_condition($user, $options['permission'], options));
    }
    $scope_options['conditions'] = $cond->conditions;
    if(isset($options['limit'])) {
      # id and creation time should be in same order in most cases
      $scope_options['order'] = $Model->table.".id DESC";
      $scope_options['limit'] = $options['limit'];
    }
    return $Model->find('all', array_merge($provider_options['find_options'], $scope_options));
  }
 
}
class Condition {
  var $conditions = array();
  function __construct($condition=false) {
    $this->conditions = array(array('1=1'));
    if(!empty($condition)) {
      $this->add($condition);
    }
  }
  function add($condition) {
    if(is_array($condition)) {
      $this->conditions[] = $condition;
    } elseif(is_string($condition)) {
      $this->conditions[] = array($condition);
    } else {
      return $this->cakeError('error', "Unsupported condition.");
    }
  }
}

?>