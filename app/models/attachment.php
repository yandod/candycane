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
#require "digest/md5"
#
class Attachment extends AppModel
{
  var $belongsTo = array(
    'Author' => array(
      'className'=>'User',
      'foreignKey'=>'author_id',
    ),
  );
  var $actsAs = array(
    'ActivityProvider'=> array(
        'type'=>'files',
        'permission'=>'view_files',
        'author_key'=>'author_id',
        'find_options'=> array(
            'fields'=> array('Attachment.*', 'Project.*', 'Author.*'),
            'joins' => array(
              array(
                'type'=>'LEFT',
                'table' => '', // set by construct
                'alias' => 'Version',
                'conditions'=>'Attachment.container_type=\'Version\' AND Version.id = Attachment.container_id',
              ),
              array(
                'type'=>'LEFT',
                'table' => '', // set by construct
                'alias' => 'Project',
                'conditions'=>'Project.id=Version.project_id',
              ),
            ),
        ),
      ),
    'Event' => array('title'       => 'filename',
                      'url'         => array('Proc' => '_event_url')),
  );
  var $storage_path = null;
#  belongs_to :container, :polymorphic => true
#  belongs_to :author, :class_name => "User", :foreign_key => "author_id"
#  
#  validates_presence_of :container, :filename, :author
#  validates_length_of :filename, :maximum => 255
#  validates_length_of :disk_filename, :maximum => 255
#
  function __construct($id = false, $table = null, $ds = null) {
    foreach($this->actsAs['ActivityProvider']['find_options']['joins'] as $index=>$join) {
      $this->actsAs['ActivityProvider']['find_options']['joins'][$index]['table'] = $this->fullTableName($join['alias']);
    }
    parent::__construct($id, $table, $ds);

    // Add multi provider
    $this->addActivityProvider(array(
        'type'=>'documents',
        'permission'=>'view_documents',
        'author_key'=>'author_id',
        'find_options'=> array(
            'fields'=> array('Attachment.*', 'Project.*', 'Author.*'),
            'joins' => array(
              array(
                'type'=>'LEFT',
                'table' => $this->fullTableName('Document'),
                'alias' => 'Document',
                'conditions'=>'Attachment.container_type=\'Document\' AND Document.id = Attachment.container_id',
              ),
              array(
                'type'=>'LEFT',
                'table' => $this->fullTableName('Project'),
                'alias' => 'Project',
                'conditions'=>'Project.id=Document.project_id',
              ),
            ),
        ),
      ));
    
    $this->storage_path = APP.DS."files".DS;
  }
  function _event_url($data) {
    return  array('controller'=>'attachments', 'action'=>'download', 'id'=>$data['Attachment']['id'], '?' => array('filename'=>$data['Attachment']['filename']));
  }

#  def validate
#    errors.add_to_base :too_long if self.filesize > Setting.attachment_max_size.to_i.kilobytes
#  end

  function create_disk_filename($incoming_file, $original_filename) {
    if (!empty($incoming_file)) {
      $temp_file = $incoming_file;
      $filename = $this->sanitize_filename($original_filename);
      $disk_filename = $this->disk_filename($filename);
    }
    return $disk_filename;
  }
	
#  def file
#    nil
#  end

  # Copy temp file to its final location
  function beforeSave($options = array()) {
    parent::beforeSave($options);
    if (!empty($this->data[$this->alias]['temp_file']) && !empty($this->data[$this->alias]['filesize'])) {
      $this->data[$this->alias]['disk_filename'] = $this->create_disk_filename($this->data[$this->alias]['temp_file'], $this->data[$this->alias]['filename']);
      $this->log("saving '{$this->diskfile()}'", LOG_DEBUG);
      @copy($this->data[$this->alias]['temp_file'], $this->diskfile());
      unset($this->data[$this->alias]['temp_file']);
      $this->data[$this->alias]['digest'] = $this->digest($this->diskfile());
    }
    # Don't save the content type if it's longer than the authorized length
    if (!empty($this->data[$this->alias]['content_type']) && (strlen($this->data[$this->alias]['content_type']) > 255)) {
      unset($this->data[$this->alias]['content_type']);
    }
    return true;
  }

  # Deletes file on the disk
  function beforeDelete($cascade = true) {
    $this->read('disk_filename');
    return true;
  }
  function afterDelete() {
    @unlink($this->diskfile());
  }

  # Returns file's location on disk
  function diskfile() {
    return $this->storage_path.$this->data[$this->alias]['disk_filename'];
  }
  
#  def increment_download
#    increment!(:downloads)
#  end
#
#  def project
#    container.project
#  end
#  
#  def visible?(user=User.current)
#    container.attachments_visible?(user)
#  end
#  
#  def deletable?(user=User.current)
#    container.attachments_deletable?(user)
#  end
#  
#  def image?
#    self.filename =~ /\.(jpe?g|gif|png)$/i
#  end
#  
#  def is_text?
#    Redmine::MimeType.is_type?('text', filename)
#  end
#  
#  def is_diff?
#    self.filename =~ /\.(patch|diff)$/i
#  end
#  
#private
  function sanitize_filename($value) {
    # get only the filename, not the whole path
    $just_filename = preg_replace('/^.*(\\|\/)/', '', $value);
    # NOTE: File.basename doesn't work right with Windows paths on Unix
    # INCORRECT: just_filename = File.basename(value.gsub('\\\\', '/')) 

    # Finally, replace all non alphanumeric, hyphens or periods with underscore
    return preg_replace('/[^\w\.\-]/','_', $just_filename);
  }
  
  function disk_filename($filename)
  {
    $df = strftime("%y%m%d%H%M%S") . "_";
    if (preg_match('/^[a-zA-Z0-9_\.\-]*$/', $filename)) {
      $df .= $filename;
    } else {
      $df .= md5($filename);
      if (preg_match('/(\.[a-zA-Z0-9]+)$/', $filename, $matches)) {
        $df .= $matches[0];
      }
    }

    return $df;
  }
#  # Returns an ASCII or hashed filename
#  def self.disk_filename(filename)
#    df = DateTime.now.strftime("%y%m%d%H%M%S") + "_"
#    if filename =~ %r{^[a-zA-Z0-9_\.\-]*$}
#      df << filename
#    else
#      df << Digest::MD5.hexdigest(filename)
#      # keep the extension if any
#      df << $1 if filename =~ %r{(\.[a-zA-Z0-9]+)$}
#    end
#    df
#  end
#  
  function digest($filename)
  {
    return md5_file($filename);
  }
#  # Returns the MD5 digest of the file at given path
#  def self.digest(filename)
#    File.open(filename, 'rb') do |f|
#      Digest::MD5.hexdigest(f.read)
#    end
#  end
}

