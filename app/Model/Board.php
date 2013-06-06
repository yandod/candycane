<?php

class Board extends AppModel
{
#class Board < ActiveRecord::Base
#  belongs_to :project
#  has_many :topics, :class_name => 'Message', :conditions => "#{Message.table_name}.parent_id IS NULL", :order => "#{Message.table_name}.created_on DESC"
#  has_many :messages, :dependent => :delete_all, :order => "#{Message.table_name}.created_on DESC"
#  belongs_to :last_message, :class_name => 'Message', :foreign_key => :last_message_id
#  acts_as_list :scope => :project_id
#  acts_as_watchable
#  
#  validates_presence_of :name, :description
#  validates_length_of :name, :maximum => 30
#  validates_length_of :description, :maximum => 255
#end
}