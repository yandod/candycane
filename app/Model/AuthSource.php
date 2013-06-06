<?php

class AuthSource extends AppModel
{
#  has_many :users
#  
#  validates_presence_of :name
#  validates_uniqueness_of :name
#  validates_length_of :name, :maximum => 60
#
#  def authenticate(login, password)
#  end
#  
#  def test_connection
#  end
#  
#  def auth_method_name
#    "Abstract"
#  end
#
#  # Try to authenticate a user not yet registered against available sources
#  def self.authenticate(login, password)
#    AuthSource.find(:all, :conditions => ["onthefly_register=?", true]).each do |source|
#      begin
#        logger.debug "Authenticating '#{login}' against '#{source.name}'" if logger && logger.debug?
#        attrs = source.authenticate(login, password)
#      rescue => e
#        logger.error "Error during authentication: #{e.message}"
#        attrs = nil
#      end
#      return attrs if attrs
#    end
#    return nil
#  end
}

