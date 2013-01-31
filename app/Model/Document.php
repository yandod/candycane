<?php
## redMine - project management software
## Copyright (C) 2006  Jean-Philippe Lang
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
class Document extends AppModel
{
  var $name = 'Document';
  var $belongsTo = array(
    'Project',
  );
#  belongs_to :category, :class_name => "Enumeration", :foreign_key => "category_id"
#  acts_as_attachable :delete_permission => :manage_documents
#
#  acts_as_searchable :columns => ['title', "#{table_name}.description"], :include => :project
#  acts_as_event :title => Proc.new {|o| "#{l(:label_document)}: #{o.title}"},
#                :author => Proc.new {|o| (a = o.attachments.find(:first, :order => "#{Attachment.table_name}.created_on ASC")) ? a.author : nil },
#                :url => Proc.new {|o| {:controller => 'documents', :action => 'show', :id => o.id}}
#  acts_as_activity_provider :find_options => {:include => :project}
  var $actsAs = array(
    'Attachable'=>array(':delete_permission' => ':manage_documents'),
    'ActivityProvider'=>array('find_options'=>array('include'=>array('Project'))),
    'Event' => array('title' => array('Proc' => '_event_title'),
                      'author'=> array('Proc' => '_event_author'),
                      'url'   => array('Proc' => '_event_url')),
  );
  function _event_title($data) {
     return __('Document').': '.$data[$this->alias]['title'];
  }
  function _event_author($data) {
    $attachments = $this->findAttachments($data[$this->alias]['id']);
    if(!empty($attachment)) {
      $User =& ClassRegistry::init('User');
      return $User->to_string(array('User'=>$attachments[0]['Author']));
    }
     return null;
  }
  function _event_url($data) {
    return  array('controller'=>'documents', 'action'=>'show', 'id'=>$data[$this->alias]['id']);
  }
#  
#  validates_presence_of :project, :title, :category
#  validates_length_of :title, :maximum => 60
#  
#  def after_initialize
#    if new_record?
#      self.category ||= Enumeration.default('DCAT')
#    end
#  end
}