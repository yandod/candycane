<?php
## redMine - project management software
## Copyright (C) 2006-2007  Jean-Philippe Lang
##
## This program is free software; you can redistribute it and/or
## modify it under the terms of the GNU General Public License
## as published by the Free Software Foundation; either version 2
## of the License, or (at your option) any later version.
## 
## This program is distributed in the hope that it will be useful,
## but WITHOUT ANY WARRANTY; without even the implied warranty of
## MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
## GNU General Public License for more details.
## 
## You should have received a copy of the GNU General Public License
## along with this program; if not, write to the Free Software
## Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
#
#class Watcher < ActiveRecord::Base
#  belongs_to :watchable, :polymorphic => true
#  belongs_to :user
#  
#  validates_presence_of :user
#  validates_uniqueness_of :user_id, :scope => [:watchable_type, :watchable_id]
#  
#  protected
#  
#  def validate
#    errors.add :user_id, :activerecord_error_invalid unless user.nil? || user.active?
#  end
#end
class Watcher extends AppModel {
	var $name = 'Watcher';
	var $belongsTo = array('User');

  var $validate = array(
	'user_id' => array(
	  'validates_presence_of'=>array('rule'=>array('activeUser')),
	  'validates_uniqueness_of'=>array('rule'=>array('isUnique')),
	),
  );
  function activeUser($data) {
	if(empty($this->data[$this->name]['user_id'])) return false;
	return $this->User->is_active($this->data[$this->name]['user_id']);
  }
  function isUnique($field, $data) {
	return parent::isUnique(array('user_id', 'watchable_type', 'watchable_id'), false);
  }
}