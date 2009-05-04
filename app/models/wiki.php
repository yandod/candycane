<?php
class Wiki extends AppModel
{
  var $name = 'Wiki';
  var $hasMany = array(
                       'Page' => array(
                                       'className' => 'WikiPage',
                                       'dependent' => true, // :dependent => :destroy
                                       'order' => 'title',
                                       ),
                       'Redirect' => array(
                                           'className' => 'WikiRedirect',
                                           'dependent' => true, // :dependent => :delete_all
                                           ),
                       );

  // titleに合うpageのモデル配列を取得する。
  // DB上に無い場合はidの無いsave用のdataを返す。
  function find_or_new_page($title)
  {
    if ($title === null || $title === "") {
      $title = $this->field('start_page');
    }
    $page  =$this->find_page($title);
    if (!$page) {
      $page = aa('Page',
                 aa('wiki_id', $this->id,
                    'title', Wiki::titleize($title)));
    }
    return $page;
  }

  function find_page($title, $options = array())
  {
    $param = array();
    if ($title === "") {
      $title = $this->field('start_page');
    }
    $title = Wiki::titleize($title);
    $page = $this->Page->find('first',
                              aa('conditions',
                                 aa('Page.wiki_id', $this->id,
                                    'Page.title', $title)));
    if (isset($page['Page']['id'])) {
      $this->Page->id = $page['Page']['id'];
    }
    //    if !page && !(options[:with_redirect] == false)
    //      # search for a redirect
    //      redirect = redirects.find_by_title(title)
    //      page = find_page(redirect.redirects_to, :with_redirect => false) if redirect
    //    end
    return $page;
  }

  function titleize($title) {
    // replace spaces with _ and remove unwanted caracter
    $title = preg_replace('/\s+/', '_', $title);
    // upcase the first letter
    $title = preg_replace('/^([a-z])/e', 'strtoupper("\\1")', $title);
    return $title;
  }
}

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
#class Wiki < ActiveRecord::Base
#  belongs_to :project
#  has_many :pages, :class_name => 'WikiPage', :dependent => :destroy, :order => 'title'
#  has_many :redirects, :class_name => 'WikiRedirect', :dependent => :delete_all
#  
#  validates_presence_of :start_page
#  validates_format_of :start_page, :with => /^[^,\.\/\?\;\|\:]*$/
#  
#  # find the page with the given title
#  # if page doesn't exist, return a new page
#  def find_or_new_page(title)
#    title = start_page if title.blank?
#    find_page(title) || WikiPage.new(:wiki => self, :title => Wiki.titleize(title))
#  end
#  
#  # find the page with the given title
#  def find_page(title, options = {})
#    title = start_page if title.blank?
#    title = Wiki.titleize(title)
#    page = pages.find_by_title(title)
#    if !page && !(options[:with_redirect] == false)
#      # search for a redirect
#      redirect = redirects.find_by_title(title)
#      page = find_page(redirect.redirects_to, :with_redirect => false) if redirect
#    end
#    page
#  end
#  
#  # Finds a page by title
#  # The given string can be of one of the forms: "title" or "project:title"
#  # Examples:
#  #   Wiki.find_page("bar", project => foo)
#  #   Wiki.find_page("foo:bar")
#  def self.find_page(title, options = {})
#    project = options[:project]
#    if title.to_s =~ %r{^([^\:]+)\:(.*)$}
#      project_identifier, title = $1, $2
#      project = Project.find_by_identifier(project_identifier) || Project.find_by_name(project_identifier)
#    end
#    if project && project.wiki
#      page = project.wiki.find_page(title)
#      if page && page.content
#        page
#      end
#    end
#  end
#  
#  # turn a string into a valid page title
#  def self.titleize(title)
#    # replace spaces with _ and remove unwanted caracters
#    title = title.gsub(/\s+/, '_').delete(',./?;|:') if title
#    # upcase the first letter
#    title = (title.slice(0..0).upcase + (title.slice(1..-1) || '')) if title
#    title
#  end  
#end
