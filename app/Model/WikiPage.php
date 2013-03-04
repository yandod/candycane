<?php
/**
 * Wiki Page
 *
 * @package candycane
 * @subpackage candycane.models
 */
class WikiPage extends AppModel {

/**
 * Model name
 *
 * @var string
 */
 	public $name = 'WikiPage';

/**
 * "Belongs To" Associations
 *
 * @var array
 */
 	public $belongsTo = array('Wiki');

/**
 * "Has One" Associations
 *
 * @var array
 */
 	public $hasOne = array(
		'WikiContent' => array(
			'className' => 'WikiContent',
			'foreignKey' => 'page_id',
			'dependent' => true
			//:dependent => :destroy
		)
	);

/**
 * Validation rules
 *
 * @var array
 */
 	public $validate = array(
		'title' => array(
			'validates_presence_of' => array(
				'rule' => 'notEmpty'
			),
			'validates_format_of' => array(
				'rule' => array('custom', '/^[^,\.\/\?\;\|\:]*$/')
			),
			'validates_uniqueness_of' => array(
				'rule' => '_isUniqueTitle'
			),
		),
	);

/**
 * Behaviors
 *
 * @var array
 */
	public $actsAs = array(
		'Searchable' => array(),
		'Event' => array(
			'title'  => array('Proc' => '_event_title'), 
			'description' => array('Proc' => '_event_description'),
			'datetime' => 'created_on',
			'url' => array('Proc' => '_event_url')
		),
		'Attachable'

	);

/**
 * Filter Args
 *
 * @var array
 */
 	public $filterArgs = array(
		array('name' => 'title', 'type' => 'like'),
		array('name' => 'WikiContent.text', 'type' => 'like'),
	);

/**
 * Event Title
 *
 * @param array $data WikiPage data
 * @return string Event title
 * @access protected
 */
	function _event_title($data){
		return __('Wiki').': '.$data['WikiPage']['title'];
	}

/**
 * Event URL
 *
 * @param array $data WikiPage data
 * @return array Array based URL for Event
 * @access protected
 */
	function _event_url($data) {
		return  array('controller'=>'wiki','wikipage'=>$data['WikiPage']['title'], 'project_id' => $data['Project']['identifier']);
	}

/**
 * Event Description
 *
 * @param array $data WikiPage data
 * @return string Event description
 * @access protected
 */
	function _event_description($data){
		return $data['WikiContent']['text'];
	}

#require 'diff'
#require 'enumerator'
#
#class WikiPage < ActiveRecord::Base
#  belongs_to :wiki
#  has_one :content, :class_name => 'WikiContent', :foreign_key => 'page_id', :dependent => :destroy
#  acts_as_attachable :delete_permission => :delete_wiki_pages_attachments
#  acts_as_tree :order => 'title'
#  
#  acts_as_event :title => Proc.new {|o| "#{l(:label_wiki)}: #{o.title}"},
#                :description => :text,
#                :datetime => :created_on,
#                :url => Proc.new {|o| {:controller => 'wiki', :id => o.wiki.project_id, :page => o.title}}
#
#  acts_as_searchable :columns => ['title', 'text'],
#                     :include => [{:wiki => :project}, :content],
#                     :project_key => "#{Wiki.table_name}.project_id"
#
#  attr_accessor :redirect_existing_links
#  
#  validates_presence_of :title
#  validates_format_of :title, :with => /^[^,\.\/\?\;\|\s]*$/
#  validates_uniqueness_of :title, :scope => :wiki_id, :case_sensitive => false
#  validates_associated :content
#
#  def title=(value)
#    value = Wiki.titleize(value)
#    @previous_title = read_attribute(:title) if @previous_title.blank?
#    write_attribute(:title, value)
#  end
#
#  def before_save
#    self.title = Wiki.titleize(title)    
#    # Manage redirects if the title has changed
#    if !@previous_title.blank? && (@previous_title != title) && !new_record?
#      # Update redirects that point to the old title
#      wiki.redirects.find_all_by_redirects_to(@previous_title).each do |r|
#        r.redirects_to = title
#        r.title == r.redirects_to ? r.destroy : r.save
#      end
#      # Remove redirects for the new title
#      wiki.redirects.find_all_by_title(title).each(&:destroy)
#      # Create a redirect to the new title
#      wiki.redirects << WikiRedirect.new(:title => @previous_title, :redirects_to => title) unless redirect_existing_links == "0"
#      @previous_title = nil
#    end
#  end
#  
#  def before_destroy
#    # Remove redirects to this page
#    wiki.redirects.find_all_by_redirects_to(title).each(&:destroy)
#  end
#  
#  def pretty_title
#    WikiPage.pretty_title(title)
#  end
#  

/**
 * Return wiki_content and author for specified version
 *
 * @param string $version Version
 * @return array WikiContent data
 */
	public function content_for_version($version = null) {
		$result = null;
		$conditions = array('page_id' => $this->field('id'));
		if ($version) {
			$conditions['version'] = $version;
			// temporary implementation
			$result = $this->WikiContent->WikiContentVersion->find('first', array(
				'conditions' => $conditions,
				'recursive' => 0
			));
			$result['WikiContent'] = $result['WikiContentVersion'];
			$result['WikiContent']['text'] = $result['WikiContent']['data'];
			unset($result['WikiContent']['data']);
			unset($result['WikiContent']['compression']);
		}
		if (empty($result)) {
			$result = $this->WikiContent->find('first', array(
				'conditions' => $conditions,
				'recursive' => 0
			));
		}
		return $result;
	}

#  def content_for_version(version=nil)
#    result = content.versions.find_by_version(version.to_i) if version
#    result ||= content
#    result
#  end
#  
#  def diff(version_to=nil, version_from=nil)
#    version_to = version_to ? version_to.to_i : self.content.version
#    version_from = version_from ? version_from.to_i : version_to - 1
#    version_to, version_from = version_from, version_to unless version_from < version_to
#    
#    content_to = content.versions.find_by_version(version_to)
#    content_from = content.versions.find_by_version(version_from)
#    
#    (content_to && content_from) ? WikiDiff.new(content_to, content_from) : nil
#  end
#  
#  def annotate(version=nil)
#    version = version ? version.to_i : self.content.version
#    c = content.versions.find_by_version(version)
#    c ? WikiAnnotate.new(c) : nil
#  end
#  
#  def self.pretty_title(str)
#    (str && str.is_a?(String)) ? str.tr('_', ' ') : str
#  end
#  
#  def project
#    wiki.project
#  end
#  
#  def text
#    content.text if content
#  end
#  
#  # Returns true if usr is allowed to edit the page, otherwise false
#  def editable_by?(usr)
#    !protected? || usr.allowed_to?(:protect_wiki_pages, wiki.project)
#  end
#        
#  def attachments_deletable?(usr=User.current)
#    editable_by?(usr) && super(usr)
#  end
#  
#  def parent_title
#    @parent_title || (self.parent && self.parent.pretty_title)
#  end
#  
#  def parent_title=(t)
#    @parent_title = t
#    parent_page = t.blank? ? nil : self.wiki.find_page(t)
#    self.parent = parent_page
#  end
#  
#  protected
#  
#  def validate
#    errors.add(:parent_title, :activerecord_error_invalid) if !@parent_title.blank? && parent.nil?
#    errors.add(:parent_title, :activerecord_error_circular_dependency) if parent && (parent == self || parent.ancestors.include?(self))
#    errors.add(:parent_title, :activerecord_error_not_same_project) if parent && (parent.wiki_id != wiki_id)
#  end
#end
#
#class WikiDiff
#  attr_reader :diff, :words, :content_to, :content_from
#  
#  def initialize(content_to, content_from)
#    @content_to = content_to
#    @content_from = content_from
#    @words = content_to.text.split(/(\s+)/)
#    @words = @words.select {|word| word != ' '}
#    words_from = content_from.text.split(/(\s+)/)
#    words_from = words_from.select {|word| word != ' '}    
#    @diff = words_from.diff @words
#  end
#end
#
#class WikiAnnotate
#  attr_reader :lines, :content
#  
#  def initialize(content)
#    @content = content
#    current = content
#    current_lines = current.text.split(/\r?\n/)
#    @lines = current_lines.collect {|t| [nil, nil, t]}
#    positions = []
#    current_lines.size.times {|i| positions << i}
#    while (current.previous)
#      d = current.previous.text.split(/\r?\n/).diff(current.text.split(/\r?\n/)).diffs.flatten
#      d.each_slice(3) do |s|
#        sign, line = s[0], s[1]
#        if sign == '+' && positions[line] && positions[line] != -1
#          if @lines[positions[line]][0].nil?
#            @lines[positions[line]][0] = current.version
#            @lines[positions[line]][1] = current.author
#          end
#        end
#      end
#      d.each_slice(3) do |s|
#        sign, line = s[0], s[1]
#        if sign == '-'
#          positions.insert(line, -1)
#        else
#          positions[line] = nil
#        end
#      end
#      positions.compact!
#      # Stop if every line is annotated
#      break unless @lines.detect { |line| line[0].nil? }
#      current = current.previous
#    end
#    @lines.each { |line| line[0] ||= current.version }
#  end
#end
	function project() {
		if (isset($this->data['Wiki'])) {
			$wiki = $this->data;
		} else {
			$wiki = $this->read('Wiki.*', $this->data['WikiPage']['id']);
		}
		$project = $this->Wiki->find('first', array('conditions' => array('Project.id' =>  $wiki['Wiki']['project_id'])));

		return $project;
	}

/**
 * Check if the title is unique
 *
 * @param string $check Title to check
 * @return boolean True if the title is unique
 * @access protected
 */
	function _isUniqueTitle($check) {
		$conditions = array();
		if (!empty($this->id) && $this->field('wiki_id')) {
			$conditions[] = array('WikiPage.id <>' => $this->id);
			$conditions[] = array('WikiPage.wiki_id' => $this->field('wiki_id'));
		} elseif (isset($this->data['WikiPage']['wiki_id'])) {
			$conditions[] = array('WikiPage.wiki_id' => $this->data['WikiPage']['wiki_id']);
		}
		$conditions[] = array('LOWER(WikiPage.title)' => strtolower($check["title"]));
		$count = $this->find('count', array(
			'conditions' => $conditions,
			'recursive' => -1
		));
		return ($count === 0);
	}
}
