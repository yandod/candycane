<?php

class AuthSourceLdap extends AuthSource
{
    var $useTable = false;
#  validates_presence_of :host, :port, :attr_login
#  validates_length_of :name, :host, :account_password, :maximum => 60, :allow_nil => true
#  validates_length_of :account, :base_dn, :maximum => 255, :allow_nil => true
#  validates_length_of :attr_login, :attr_firstname, :attr_lastname, :attr_mail, :maximum => 30, :allow_nil => true
#  validates_numericality_of :port, :only_integer => true
#  
#  before_validation :strip_ldap_attributes
#  
#  def after_initialize
#    self.port = 389 if self.port == 0
#  end
#  
#  def authenticate(login, password)
#    return nil if login.blank? || password.blank?
#    attrs = []
#    # get user's DN
#    ldap_con = initialize_ldap_con(self.account, self.account_password)
#    login_filter = Net::LDAP::Filter.eq( self.attr_login, login ) 
#    object_filter = Net::LDAP::Filter.eq( "objectClass", "*" ) 
#    dn = String.new
#    ldap_con.search( :base => self.base_dn, 
#                     :filter => object_filter & login_filter, 
#                     # only ask for the DN if on-the-fly registration is disabled
#                     :attributes=> (onthefly_register? ? ['dn', self.attr_firstname, self.attr_lastname, self.attr_mail] : ['dn'])) do |entry|
#      dn = entry.dn
#      attrs = [:firstname => AuthSourceLdap.get_attr(entry, self.attr_firstname),
#               :lastname => AuthSourceLdap.get_attr(entry, self.attr_lastname),
#               :mail => AuthSourceLdap.get_attr(entry, self.attr_mail),
#               :auth_source_id => self.id ] if onthefly_register?
#    end
#    return nil if dn.empty?
#    logger.debug "DN found for #{login}: #{dn}" if logger && logger.debug?
#    # authenticate user
#    ldap_con = initialize_ldap_con(dn, password)
#    return nil unless ldap_con.bind
#    # return user's attributes
#    logger.debug "Authentication successful for '#{login}'" if logger && logger.debug?
#    attrs    
#  rescue  Net::LDAP::LdapError => text
#    raise "LdapError: " + text
#  end
#
#  # test the connection to the LDAP
#  def test_connection
#    ldap_con = initialize_ldap_con(self.account, self.account_password)
#    ldap_con.open { }
#  rescue  Net::LDAP::LdapError => text
#    raise "LdapError: " + text
#  end
# 
#  def auth_method_name
#    "LDAP"
#  end
#  
#  private
#  
#  def strip_ldap_attributes
#    [:attr_login, :attr_firstname, :attr_lastname, :attr_mail].each do |attr|
#      write_attribute(attr, read_attribute(attr).strip) unless read_attribute(attr).nil?
#    end
#  end
#  
#  def initialize_ldap_con(ldap_user, ldap_password)
#    options = { :host => self.host,
#                :port => self.port,
#                :encryption => (self.tls ? :simple_tls : nil)
#              }
#    options.merge!(:auth => { :method => :simple, :username => ldap_user, :password => ldap_password }) unless ldap_user.blank? && ldap_password.blank?
#    Net::LDAP.new options
#  end
#  
#  def self.get_attr(entry, attr_name)
#    if !attr_name.blank?
#      entry[attr_name].is_a?(Array) ? entry[attr_name].first : entry[attr_name]
#    end
#  end
}

