<?php
/*
module ActiveRecord
  module Acts #:nodoc:
    module List #:nodoc:
      def self.included(base)
        base.extend(ClassMethods)
      end

      # This +acts_as+ extension provides the capabilities for sorting and reordering a number of objects in a list.
      # The class that has this specified needs to have a +position+ column defined as an integer on
      # the mapped database table.
      #
      # Todo list example:
      #
      #   class TodoList < ActiveRecord::Base
      #     has_many :todo_items, :order => "position"
      #   end
      #
      #   class TodoItem < ActiveRecord::Base
      #     belongs_to :todo_list
      #     acts_as_list :scope => :todo_list
      #   end
      #
      #   todo_list.first.move_to_bottom
      #   todo_list.last.move_higher
      module ClassMethods
        # Configuration options are:
        #
        # * +column+ - specifies the column name to use for keeping the position integer (default: +position+)
        # * +scope+ - restricts what is to be considered a list. Given a symbol, it'll attach <tt>_id</tt> 
        #   (if it hasn't already been added) and use that as the foreign key restriction. It's also possible 
        #   to give it an entire string that is interpolated if you need a tighter scope than just a foreign key.
        #   Example: <tt>acts_as_list :scope => 'todo_list_id = #{todo_list_id} AND completed = 0'</tt>
        def acts_as_list(options = {})
          configuration = { :column => "position", :scope => "1 = 1" }
          configuration.update(options) if options.is_a?(Hash)

          configuration[:scope] = "#{configuration[:scope]}_id".intern if configuration[:scope].is_a?(Symbol) && configuration[:scope].to_s !~ /_id$/

          if configuration[:scope].is_a?(Symbol)
            scope_condition_method = %(
              def scope_condition
                if #{configuration[:scope].to_s}.nil?
                  "#{configuration[:scope].to_s} IS NULL"
                else
                  "#{configuration[:scope].to_s} = \#{#{configuration[:scope].to_s}}"
                end
              end
            )
          else
            scope_condition_method = "def scope_condition() \"#{configuration[:scope]}\" end"
          end

          class_eval <<-EOV
            include ActiveRecord::Acts::List::InstanceMethods

            def acts_as_list_class
              ::#{self.name}
            end

            def position_column
              '#{configuration[:column]}'
            end

            #{scope_condition_method}

            before_destroy :remove_from_list
            before_create  :add_to_list_bottom
          EOV
        end
      end

      # All the methods available to a record that has had <tt>acts_as_list</tt> specified. Each method works
      # by assuming the object to be the item in the list, so <tt>chapter.move_lower</tt> would move that chapter
      # lower in the list of all chapters. Likewise, <tt>chapter.first?</tt> would return +true+ if that chapter is
      # the first in the list of all chapters.
      module InstanceMethods
        # Insert the item at the given position (defaults to the top position of 1).
        def insert_at(position = 1)
          insert_at_position(position)
        end

        # Swap positions with the next lower item, if one exists.
        def move_lower
          return unless lower_item

          acts_as_list_class.transaction do
            lower_item.decrement_position
            increment_position
          end
        end

        # Swap positions with the next higher item, if one exists.
        def move_higher
          return unless higher_item

          acts_as_list_class.transaction do
            higher_item.increment_position
            decrement_position
          end
        end

        # Move to the bottom of the list. If the item is already in the list, the items below it have their
        # position adjusted accordingly.
        def move_to_bottom
          return unless in_list?
          acts_as_list_class.transaction do
            decrement_positions_on_lower_items
            assume_bottom_position
          end
        end

        # Move to the top of the list. If the item is already in the list, the items above it have their
        # position adjusted accordingly.
        def move_to_top
          return unless in_list?
          acts_as_list_class.transaction do
            increment_positions_on_higher_items
            assume_top_position
          end
        end

        # Removes the item from the list.
        def remove_from_list
          if in_list?
            decrement_positions_on_lower_items
            update_attribute position_column, nil
          end
        end

        # Increase the position of this item without adjusting the rest of the list.
        def increment_position
          return unless in_list?
          update_attribute position_column, self.send(position_column).to_i + 1
        end

        # Decrease the position of this item without adjusting the rest of the list.
        def decrement_position
          return unless in_list?
          update_attribute position_column, self.send(position_column).to_i - 1
        end

        # Return +true+ if this object is the first in the list.
        def first?
          return false unless in_list?
          self.send(position_column) == 1
        end

        # Return +true+ if this object is the last in the list.
        def last?
          return false unless in_list?
          self.send(position_column) == bottom_position_in_list
        end

        # Return the next higher item in the list.
        def higher_item
          return nil unless in_list?
          acts_as_list_class.find(:first, :conditions =>
            "#{scope_condition} AND #{position_column} = #{(send(position_column).to_i - 1).to_s}"
          )
        end

        # Return the next lower item in the list.
        def lower_item
          return nil unless in_list?
          acts_as_list_class.find(:first, :conditions =>
            "#{scope_condition} AND #{position_column} = #{(send(position_column).to_i + 1).to_s}"
          )
        end

        # Test if this record is in a list
        def in_list?
          !send(position_column).nil?
        end

        private
          def add_to_list_top
            increment_positions_on_all_items
          end

          def add_to_list_bottom
            self[position_column] = bottom_position_in_list.to_i + 1
          end

          # Overwrite this method to define the scope of the list changes
          def scope_condition() "1" end

          # Returns the bottom position number in the list.
          #   bottom_position_in_list    # => 2
          def bottom_position_in_list(except = nil)
            item = bottom_item(except)
            item ? item.send(position_column) : 0
          end

          # Returns the bottom item
          def bottom_item(except = nil)
            conditions = scope_condition
            conditions = "#{conditions} AND #{self.class.primary_key} != #{except.id}" if except
            acts_as_list_class.find(:first, :conditions => conditions, :order => "#{position_column} DESC")
          end

          # Forces item to assume the bottom position in the list.
          def assume_bottom_position
            update_attribute(position_column, bottom_position_in_list(self).to_i + 1)
          end

          # Forces item to assume the top position in the list.
          def assume_top_position
            update_attribute(position_column, 1)
          end

          # This has the effect of moving all the higher items up one.
          def decrement_positions_on_higher_items(position)
            acts_as_list_class.update_all(
              "#{position_column} = (#{position_column} - 1)", "#{scope_condition} AND #{position_column} <= #{position}"
            )
          end

          # This has the effect of moving all the lower items up one.
          def decrement_positions_on_lower_items
            return unless in_list?
            acts_as_list_class.update_all(
              "#{position_column} = (#{position_column} - 1)", "#{scope_condition} AND #{position_column} > #{send(position_column).to_i}"
            )
          end

          # This has the effect of moving all the higher items down one.
          def increment_positions_on_higher_items
            return unless in_list?
            acts_as_list_class.update_all(
              "#{position_column} = (#{position_column} + 1)", "#{scope_condition} AND #{position_column} < #{send(position_column).to_i}"
            )
          end

          # This has the effect of moving all the lower items down one.
          def increment_positions_on_lower_items(position)
            acts_as_list_class.update_all(
              "#{position_column} = (#{position_column} + 1)", "#{scope_condition} AND #{position_column} >= #{position}"
           )
          end

          # Increments position (<tt>position_column</tt>) of all items in the list.
          def increment_positions_on_all_items
            acts_as_list_class.update_all(
              "#{position_column} = (#{position_column} + 1)",  "#{scope_condition}"
            )
          end

          def insert_at_position(position)
            remove_from_list
            increment_positions_on_lower_items(position)
            self.update_attribute(position_column, position)
          end
      end 
    end
  end
end
*/
class ListBehavior extends ModelBehavior {
  var $_defaults = array(
    'column' => "position",
    'scope' => '1 = 1',
  );
  function setup(&$Model, $config = array()) {
    if (!is_array($config)) {
      $config = array('type' => $config);
    }
    $settings = array_merge($this->_defaults, $config);

    if (in_array($settings['scope'], $Model->getAssociated('belongsTo'))) {
      $data = $Model->getAssociated($settings['scope']);
      $parent =& $Model->{$settings['scope']};
      $settings['scope'] = $Model->alias . '.' . $data['foreignKey'] . ' = ' . $parent->alias . '.' . $parent->primaryKey;
      $settings['recursive'] = 0;
    }
    $this->settings[$Model->alias] = $settings;
  }

  function move_to_top(&$Model) {
    $position_column = $this->settings[$Model->alias]['column'];
    $position = $Model->data[$Model->alias][$position_column];
    if($position == 1) {
      return true;
    }
    $db =& ConnectionManager::getDataSource($Model->useDbConfig);
    $db->begin($Model);
    $result = $this->increment_positions_on_higher_items($Model, $position);
    if($result) $result = $this->assume_top_position($Model);
    if($result) {
      $db->commit($Model);
    } else {
      $db->rollback($Model);
    }
    return $result;
  }
  # Swap positions with the next higher item, if one exists.
  function move_higher(&$Model) {
    $position_column = $this->settings[$Model->alias]['column'];
    $position = $Model->data[$Model->alias][$position_column];
    if($position == 1) {
      return true;
    }
    $increment = $Model->find('first', array('conditions'=>array($position_column=>$position - 1)));
    if(!$increment) {
      return true;
    }
    $db =& ConnectionManager::getDataSource($Model->useDbConfig);
    $db->begin($Model);
    $result = $this->increment_position($Model, $increment[$Model->alias]['id']);
    if($result) $result = $this->decrement_position($Model, $Model->id);
    if($result) {
      $db->commit($Model);
    } else {
      $db->rollback($Model);
    }
    return $result;
  }
  # Swap positions with the next lower item, if one exists.
  function move_lower($Model) {
    $position_column = $this->settings[$Model->alias]['column'];
    $position = $Model->data[$Model->alias][$position_column];
    $decrement = $Model->find('first', array('conditions'=>array($position_column=>$position + 1)));
    if(!$decrement) {
      return true;
    }
    $db =& ConnectionManager::getDataSource($Model->useDbConfig);
    $db->begin($Model);
    $result = $this->decrement_position($Model, $decrement[$Model->alias]['id']);
    if($result) $result = $this->increment_position($Model, $Model->id);
    if($result) {
      $db->commit($Model);
    } else {
      $db->rollback($Model);
    }
    return $result;
  }
  # Move to the bottom of the list. If the item is already in the list, the items below it have their
  # position adjusted accordingly.
  function move_to_bottom($Model) {
    $position_column = $this->settings[$Model->alias]['column'];
    $position = $Model->data[$Model->alias][$position_column];
    $db =& ConnectionManager::getDataSource($Model->useDbConfig);
    $db->begin($Model);
    $result = $this->decrement_positions_on_lower_items($Model, $position);
    if($result) $result = $this->assume_bottom_position($Model);
    if($result) {
      $db->commit($Model);
    } else {
      $db->rollback($Model);
    }
    return $result;
  }

  
  # This has the effect of moving all the higher items down one.
  function increment_positions_on_higher_items(&$Model, $position) {
    $position_column = $this->settings[$Model->alias]['column'];
    $scope_condition = $this->settings[$Model->alias]['scope'];
    return $Model->updateAll(
      array($position_column => "($position_column + 1)"), 
      "$scope_condition AND $position_column < $position"
    );
  }
  function assume_top_position(&$Model) {
    $position_column = $this->settings[$Model->alias]['column'];
    return $Model->saveField($position_column, "1");
  }
  # Increase the position of this item without adjusting the rest of the list.
  function increment_position(&$Model, $id) {
    $position_column = $this->settings[$Model->alias]['column'];
    $primaryKey = $Model->primaryKey;
    $scope_condition = $this->settings[$Model->alias]['scope'];
    return $Model->updateAll(
      array($position_column => "($position_column + 1)"), 
      "$scope_condition AND $primaryKey = $id"
    );
  }
  function decrement_position(&$Model, $id) {
    $position_column = $this->settings[$Model->alias]['column'];
    $primaryKey = $Model->primaryKey;
    $scope_condition = $this->settings[$Model->alias]['scope'];
    return $Model->updateAll(
      array($position_column => "($position_column - 1)"), 
      "$scope_condition AND $primaryKey = $id"
    );
  }
  # This has the effect of moving all the lower items up one.
  function decrement_positions_on_lower_items(&$Model, $position) {
    $position_column = $this->settings[$Model->alias]['column'];
    $scope_condition = $this->settings[$Model->alias]['scope'];
    return $Model->updateAll(
      array($position_column => "($position_column - 1)"), 
      "$scope_condition AND $position_column > $position"
    );
  }
  # Returns the bottom position number in the list.
  #   bottom_position_in_list    # => 2
  function bottom_position_in_list($Model, $except = null) {
    $position_column = $this->settings[$Model->alias]['column'];
    $item = $this->bottom_item($Model, $except);
    return $item ? $item[$Model->alias][$position_column] : 0;
  }
  # Returns the bottom item
  function bottom_item($Model, $except = null) {
    $position_column = $this->settings[$Model->alias]['column'];
    $conditions = $this->settings[$Model->alias]['scope'];
    $primaryKey = $Model->primaryKey;
    $result = false;
    if(!empty($except)) {
      $id = $except[$Model->alias][$primaryKey];
      $conditions = "$conditions AND $primaryKey != $id";
      $result = $Model->find('first', array('conditions'=>$conditions, 'order'=>"$position_column DESC"));
    }
    return $result;
  }
  # Forces item to assume the bottom position in the list.
  function assume_bottom_position(&$Model) {
    $position_column = $this->settings[$Model->alias]['column'];
    $position = $this->bottom_position_in_list($Model, $Model->data) + 1;
    return $Model->saveField($position_column, $position);

  }
  
  function beforeSave(&$Model) {
    $result = true;
    if(empty($Model->id)) {
      $position_column = $this->settings[$Model->alias]['column'];
      $conditions = $this->settings[$Model->alias]['scope'];
      $result = $Model->find('first', array('conditions'=>$conditions, 'order'=>"$position_column DESC"));
      if($result) {
        $Model->data[$Model->alias][$position_column] = $result[$Model->alias][$position_column] + 1;
      }
    }
    return true;
  }
 
  function beforeDelete(&$Model) {
    $position_column = $this->settings[$Model->alias]['column'];
    $result = $Model->read(null,$Model->id);
    return $this->decrement_positions_on_lower_items($Model, $result[$Model->alias][$position_column]) ;
  }
  
}
?>