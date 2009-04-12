<?php
/*
module Redmine
  module Acts
    module Watchable
      def self.included(base) 
        base.extend ClassMethods
      end 

      module ClassMethods
        def acts_as_watchable(options = {})
          return if self.included_modules.include?(Redmine::Acts::Watchable::InstanceMethods)          
          send :include, Redmine::Acts::Watchable::InstanceMethods

          class_eval do
            has_many :watchers, :as => :watchable, :dependent => :delete_all
            has_many :watcher_users, :through => :watchers, :source => :user

            attr_protected :watcher_ids, :watcher_user_ids
          end
        end
      end

      module InstanceMethods
        def self.included(base)
          base.extend ClassMethods
        end

        # Returns an array of users that are proposed as watchers
        def addable_watcher_users
          self.project.users.sort - self.watcher_users
        end

        # Adds user as a watcher
        def add_watcher(user)
          self.watchers << Watcher.new(:user => user)
        end

        # Removes user from the watchers list
        def remove_watcher(user)
          return nil unless user && user.is_a?(User)
          Watcher.delete_all "watchable_type = '#{self.class}' AND watchable_id = #{self.id} AND user_id = #{user.id}"
        end

        # Adds/removes watcher
        def set_watcher(user, watching=true)
          watching ? add_watcher(user) : remove_watcher(user)
        end

        # Returns if object is watched by user
        def watched_by?(user)
          !self.watchers.find(:first,
                              :conditions => ["#{Watcher.table_name}.user_id = ?", user.id]).nil?
        end

        # Returns an array of watchers' email addresses
        def watcher_recipients
          self.watchers.collect { |w| w.user.mail if w.user.active? }.compact
        end

        module ClassMethods
          # Returns the objects that are watched by user
          def watched_by(user)
            find(:all, 
                 :include => :watchers,
                 :conditions => ["#{Watcher.table_name}.user_id = ?", user.id])
          end
        end
      end
    end
  end
end

*/
class WatchableBehavior extends ModelBehavior {
  function setup(&$Model, $config = array()) {
    $settings = $config;
    //  *** proc on afterFind   
    //  has_many :watcher_users, :through => :watchers, :source => :user
    $this->settings[$Model->alias] = $settings;
    return true;
  }
  # Returns an array of users that are proposed as watchers
  # TODO maybe not use...
  //function addable_watcher_users(&$Model) {
  //  project.users.sort - self.watcher_users
  //}

  # Adds user as a watcher
  function add_watcher(&$Model, $user) {
    $model = & ClassRegistry::init('Watcher');
    $model->create();
    $data = array('watchable_type'=>$Model->name, 'watchable_id '=>$Model->id, 'user_id'=>$user['User']['id']);
    if($model->hasAny($data)) {
      return true;
    }
    return $Model->Watcher->save(array('Watcher'=>data));
  }

  # Removes user from the watchers list
  function remove_watcher(&$Model, $user) {
    if(empty($user)) {
      return false;
    }
    $model = & ClassRegistry::init('Watcher');
    $conditions = array('watchable_type'=>$Model->name, 'watchable_id '=>$Model->id, 'user_id'=>$user['User']['id']);
    return $model->deleteAll($conditions);
  }

  # Adds/removes watcher
  function set_watcher(&$Model, $user, $watching=true) {
    return $watching ? $this->add_watcher($Model, $user) : $this->remove_watcher($Model, $user);
  }

  # Returns if object is watched by user
  function watched_by(&$Model, $user) {
    $model = & ClassRegistry::init('Watcher');
    $watcher = $model->find('first',array('conditions' => array("Watcher.user_id"=>$user['User']['id'])));
    if(!$watcher) {
      return false;
    }
    $belongsModel = & ClassRegistry::init($watcher['Watcher']['watchable_type']);
    $belongsData = $belongsModel->read(null, $watcher['Watcher']['watchable_id']);
    $userModel = & ClassRegistry::init('User');
    $user = $userModel->read(null, $watcher['Watcher']['user_id']);
    return array_merge($watcher, $belongsData, $user);
  }

  # Returns an array of watchers' email addresses
  function watcher_recipients(&$Model) {
    $model = & ClassRegistry::init('Watcher');
    $model->bindModel(array('belongsTo'=>array('User')), false);
    $watchers = $model->find('all', array('conditions' => array('watchable_type'=>$Model->name, 'watchable_id'=>$Model->id)));
    if(empty($watchers)) {
      return array();
    }
    $mails = array();
    foreach($watchers as $watcher) {
      if($watcher['User']['status'] == USER_STATUS_ACTIVE) {
        $mails[] = $watcher['User']['mail'];
      }
    }
    return $mails;
  }
}

?>
