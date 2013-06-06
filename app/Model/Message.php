<?php

class Message extends AppModel
{
    var $useTable = false;
}
#class Message < ActiveRecord::Base
#  belongs_to :board
#  belongs_to :author, :class_name => 'User', :foreign_key => 'author_id'
#  acts_as_tree :counter_cache => :replies_count, :order => "#{Message.table_name}.created_on ASC"
#  acts_as_attachable
#  belongs_to :last_reply, :class_name => 'Message', :foreign_key => 'last_reply_id'
#  
#  acts_as_searchable :columns => ['subject', 'content'],
#                     :include => {:board => :project},
#                     :project_key => 'project_id',
#                     :date_column => "#{table_name}.created_on"
#  acts_as_event :title => Proc.new {|o| "#{o.board.name}: #{o.subject}"},
#                :description => :content,
#                :type => Proc.new {|o| o.parent_id.nil? ? 'message' : 'reply'},
#                :url => Proc.new {|o| {:controller => 'messages', :action => 'show', :board_id => o.board_id}.merge(o.parent_id.nil? ? {:id => o.id} : 
#                                                                                                                                       {:id => o.parent_id, :anchor => "message-#{o.id}"})}
#
#  acts_as_activity_provider :find_options => {:include => [{:board => :project}, :author]},
#                            :author_key => :author_id
#  acts_as_watchable
#    
#  attr_protected :locked, :sticky
#  validates_presence_of :subject, :content
#  validates_length_of :subject, :maximum => 255
#  
#  after_create :add_author_as_watcher
#  
#  def validate_on_create
#    # Can not reply to a locked topic
#    errors.add_to_base 'Topic is locked' if root.locked? && self != root
#  end
#  
#  def after_create
#    board.update_attribute(:last_message_id, self.id)
#    board.increment! :messages_count
#    if parent
#      parent.reload.update_attribute(:last_reply_id, self.id)
#    else
#      board.increment! :topics_count
#    end
#  end
#  
#  def after_destroy
#    # The following line is required so that the previous counter
#    # updates (due to children removal) are not overwritten
#    board.reload
#    board.decrement! :messages_count
#    board.decrement! :topics_count unless parent
#  end
#  
#  def sticky?
#    sticky == 1
#  end
#  
#  def project
#    board.project
#  end
#
#  def editable_by?(usr)
#    usr && usr.logged? && (usr.allowed_to?(:edit_messages, project) || (self.author == usr && usr.allowed_to?(:edit_own_messages, project)))
#  end
#
#  def destroyable_by?(usr)
#    usr && usr.logged? && (usr.allowed_to?(:delete_messages, project) || (self.author == usr && usr.allowed_to?(:delete_own_messages, project)))
#  end
#  
#  private
#  
#  def add_author_as_watcher
#    Watcher.create(:watchable => self.root, :user => author)
#  end
#end
