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
            'group' =>'Attachment.id',
        ),
      ),
    'Event' => array('title'       => 'filename',
                      'url'         => array('Proc' => '_event_url')),
  );
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
            'group' =>'Attachment.id',
        ),
      ));
  }
  function _event_url($data) {
    return  array('controller'=>'attachments', 'action'=>'download', 'id'=>$data['Attachment']['id'], '?' => array('filename'=>$data['Attachment']['filename']));
  }

#  cattr_accessor :storage_path
#  @@storage_path = "#{RAILS_ROOT}/files"
#  
#  def validate
#    errors.add_to_base :too_long if self.filesize > Setting.attachment_max_size.to_i.kilobytes
#  end
#
#  def file=(incoming_file)
#    unless incoming_file.nil?
#      @temp_file = incoming_file
#      if @temp_file.size > 0
#        self.filename = sanitize_filename(@temp_file.original_filename)
#        self.disk_filename = Attachment.disk_filename(filename)
#        self.content_type = @temp_file.content_type.to_s.chomp
#        self.filesize = @temp_file.size
#      end
#    end
#  end
#	
#  def file
#    nil
#  end
#
#  # Copy temp file to its final location
#  def before_save
#    if @temp_file && (@temp_file.size > 0)
#      logger.debug("saving '#{self.diskfile}'")
#      File.open(diskfile, "wb") do |f| 
#        f.write(@temp_file.read)
#      end
#      self.digest = self.class.digest(diskfile)
#    end
#    # Don't save the content type if it's longer than the authorized length
#    if self.content_type && self.content_type.length > 255
#      self.content_type = nil
#    end
#  end
#
#  # Deletes file on the disk
#  def after_destroy
#    File.delete(diskfile) if !filename.blank? && File.exist?(diskfile)
#  end
#
#  # Returns file's location on disk
#  def diskfile
#    "#{@@storage_path}/#{self.disk_filename}"
#  end
#  
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
#  def sanitize_filename(value)
#    # get only the filename, not the whole path
#    just_filename = value.gsub(/^.*(\\|\/)/, '')
#    # NOTE: File.basename doesn't work right with Windows paths on Unix
#    # INCORRECT: just_filename = File.basename(value.gsub('\\\\', '/')) 
#
#    # Finally, replace all non alphanumeric, hyphens or periods with underscore
#    @filename = just_filename.gsub(/[^\w\.\-]/,'_') 
#  end
#  
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

