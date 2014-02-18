<?php
# Redmine - project management software
# Copyright (C) 2006-2008  Jean-Philippe Lang
#
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

class AttachableBehavior extends ModelBehavior {
  /**
   *  has_many :attachments, options.merge(:as => :container,
                                           :order => "#{Attachment.table_name}.created_on",
                                           :dependent => :destroy)
   */
  var $Attachment = false;
  /**
   * attachable_options[:view_permission] = options.delete(:view_permission) || "view_#{self.name.pluralize.underscore}".to_sym
   * attachable_options[:delete_permission] = options.delete(:delete_permission) || "edit_#{self.name.pluralize.underscore}".to_sym
   */
  function setup(&$Model, $config = array()) {
    $defaults = array(
      ':view_permission'=>'view_'.Inflector::tableize($Model->name),
      ':delete_permission'=>'edit_'.Inflector::tableize($Model->name)
    );
    $settings = array_merge($defaults, $config);
    $this->settings[$Model->alias] = $settings;
    
    return true;
  }
  /**
   * @param $user is current user. ex.$user['id']
   * @param $project is current project. ex.$project['Project']['id']
   */
  function is_attachments_visible(&$Model, $user, $project) {
    $User = & ClassRegistry::init('User');
    return $User->is_allowed_to($user, $this->settings[$Model->alias][':view_permission'], $project);
  }
  function is_attachments_deletable(&$Model, $user, $project) {
    $User = & ClassRegistry::init('User');
    return $User->is_allowed_to($user, $this->settings[$Model->alias][':delete_permission'], $project);
  }
  function findAttachableById(&$Model, $id) {
    $this->__initAttachment();
    return $this->Attachment->find('first', array('conditions'=>array(
      'container_type'=>$Model->name,
      'id' => $id),
      'recursive'=>-1
    ));
  }
  function findAttachments(&$Model, $id=false) {
    if(!$id) $id = $Model->id;
    $this->__initAttachment();
    return $this->Attachment->find('all', array('conditions'=>array(
      'container_type'=>$Model->name,
      'container_id' => $id),
      'recursive'=>0
    ));
  }
  function __initAttachment() {
    if(!$this->Attachment) {
      $this->Attachment = & ClassRegistry::init('Attachment');
    }
  }

  /**
   * @param attachments is followings:
   * Array
        (
            [attachments_description] => Array
                (
                    [1] => banner1
                    [2] => banner2
                )

            [attachments_file] => Array
                (
                    [1] => Array
                        (
                            [name] => pokenjp_katakana_logo_20090.jpg
                            [type] => image/jpeg
                            [tmp_name] => /tmp/phpls7W9G
                            [error] => 0
                            [size] => 13956
                        )

                    [2] => Array
                        (
                            [name] => 000313_m.jpg
                            [type] => image/jpeg
                            [tmp_name] => /tmp/phpr3xunv
                            [error] => 0
                            [size] => 996530
                        )

                )

        )
   */
  function attach_files(&$Model, $attachments, $current_user) {
    $attached = array();
    $unsaved = array();
    if (!empty($attachments) && is_array($attachments)) {
      $this->__initAttachment();
      extract($attachments);
      foreach ($attachments_file as $i => $file) {
        extract($file);
        if ($size <= 0) {
          continue;
        }
        $attachment = $this->Attachment->create();
        $data = array(
          'container_id' => $Model->id, 
          'container_type' => $Model->name, 
          'filename' => $name, 
          'filesize' => $size,
          'content_type' => $type,
          'description' => trim($attachments_description[$i]),
          'author_id' => $current_user['id'],
          'temp_file' => $tmp_name,
        );
        $result = $this->Attachment->save($data);
        $data['id'] = $this->Attachment->getLastInsertID();
        !$result ? ($unsaved[] = $data) : ($attached[] = $data);
      }
#      if unsaved.any?
#        flash[:warning] = l(:warning_attachments_not_saved, unsaved.size)
#      end
    }
    return compact('attached', 'unsaved');
  }
  
}
