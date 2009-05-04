<?php

class WikiController extends AppController {
  //var $helpers = array('attachments');
  var $uses = array('Wiki', 'WikiContent', 'Project', 'User');
  var $helpers = array('Time', 'Number', 'Wiki');

  function index() {
    $page_title = null;
    if (isset($this->params['wikipage'])) {
      $page_title = $this->params['wikipage'];
    }
    $page = $this->Wiki->find_or_new_page($page_title);
    if (!isset($page['Page']['id'])) {
      $this->edit();
      $this->render('edit');
      return;
    }
    $version = isset($this->params['url']['version']) ? $this->params['url']['version'] : null;
    // 仮の実装。本当はwiki_content_versionsから取得する実装が必要。
    // @content = @page.content_for_version(params[:version])
    $content = $this->Wiki->Page->Content->find('first',
                                                aa('conditions',
                                                   aa('Content.page_id',
                                                      $page['Page']['id'])));
    $export = isset($this->params['url']['export']) ? $this->params['url']['export'] : null;
    if ($export === 'html') {
      //export = render_to_string :action => 'export', :layout => false
      //send_data(export, :type => 'text/html', :filename => "#{@page.title}.html")
      return;
    } elseif ($export === 'txt') {
      // send_data(@content.text, :type => 'text/plain', :filename => "#{@page.title}.txt")
      return;
    }
    $author = $this->User->findById($content['Content']['author_id']);
    $author['User']['name'] = $author['User']['firstname'].$author['User']['lastname'];
    $this->set('page', $page);
    $this->set('content', $content);
    $this->set('author', $author);
    $this->set('editable', $this->is_editable());
    $this->render('show');
  }

  function special() {
    $page_title = strtolower($this->params['wikipage']);
    switch ($page_title) {
      // show pages index, sorted by title
      case 'page_index':
      case 'date_index':
        // eager load information about last updates, without loading text
        $this->Wiki->Page->recursive = -1;
        $options = array('conditions'
                         => aa('Page.wiki_id', $this->Wiki->field('id')),
                         'fields'
                         => 'Page.*, Content.updated_on',
                         'joins'
                         => a(aa(
                                 "type", 'LEFT',
                                 "table", 'wiki_contents',
                                 "alias", 'Content',
                                 "conditions", 'Content.page_id=Page.id')),
                         'order' => 'Page.title');
        //'order' => 'Content.updated_on DESC');
        $pages = $this->Wiki->Page->find('all', $options);
        $this->set('pages', $pages);

        // 以下、viewのための整形
        foreach ($pages as $page) {
          $day = date('Y-m-d', strtotime($page['Content']['updated_on']));
          $pages_by_date[$day][] = $page;
        }
        krsort($pages_by_date);
        $this->set('pages_by_date', $pages_by_date);

        break;
      case 'export':
        $this->render("export_multiple"); // temporary implementation. fixme.
        return;
        break;
      default:
        // requested special page doesn't exist, redirect to default page
        $this->redirect(array('controller' => 'wiki',
                              'action'=>'index',
                              'project_id'=>$this->params['project_id'],
                              'wikipage' => null));
        break;
    }
    $this->render("special_${page_title}");
  }
  function beforeFilter() {
    parent::beforeFilter();
    $this->_find_wiki();

    $only = a('rename', 'protect', 'history', 'diff', 'annotate', 'add_attachment', 'destroy');
    if (in_array($this->action, $only)) {
      //$this->_find_existing_page();
    }
  }

  // private

  function _find_wiki()
  {
    $project_id = $this->viewVars['main_project']['Project']['id'];
    // projectsとwikisは1:1関係なので、アソシエーションを使わずにアクセス
    $wiki = $this->Wiki->find('first',
                              aa('conditions',
                                 aa('Wiki.project_id', $project_id)));
    if (!$wiki) {
        $this->cakeError('error404');
    }
    $this->Wiki->id = $wiki['Wiki']['id'];
    $this->set('wiki', $wiki);
  }

  function _find_existing_page()
  {
    $page = $this->Wiki->find_page($this->params['wikipage']);
    if (!$page) {
      $this->cakeError('error404');
    }
    return $page;
  }

#require 'diff'
#
#class WikiController < ApplicationController
#  before_filter :find_wiki, :authorize
#  before_filter :find_existing_page, :only => [:rename, :protect, :history, :diff, :annotate, :add_attachment, :destroy]
#  
#  verify :method => :post, :only => [:destroy, :protect], :redirect_to => { :action => :index }
#
#  helper :attachments
#  include AttachmentsHelper   
#  
#  # display a page (in editing mode if it doesn't exist)
#  def index
#    page_title = params[:page]
#    @page = @wiki.find_or_new_page(page_title)
#    if @page.new_record?
#      if User.current.allowed_to?(:edit_wiki_pages, @project)
#        edit
#        render :action => 'edit'
#      else
#        render_404
#      end
#      return
#    end
#    if params[:version] && !User.current.allowed_to?(:view_wiki_edits, @project)
#      # Redirects user to the current version if he's not allowed to view previous versions
#      redirect_to :version => nil
#      return
#    end
#    @content = @page.content_for_version(params[:version])
#    if params[:export] == 'html'
#      export = render_to_string :action => 'export', :layout => false
#      send_data(export, :type => 'text/html', :filename => "#{@page.title}.html")
#      return
#    elsif params[:export] == 'txt'
#      send_data(@content.text, :type => 'text/plain', :filename => "#{@page.title}.txt")
#      return
#    end
#	@editable = editable?
#    render :action => 'show'
#  end
#  

  // edit an existing page or a new one
  function edit() {
    $page_title = null;
    if (isset($this->params['wikipage'])) {
      $page_title = $this->params['wikipage'];
    }
    $page = $this->Wiki->find_or_new_page($page_title);
    $content = null;
    if (isset($page['Page']['id'])) {
      $content = $this->WikiContent->find('first',
                                          aa('conditions',
                                             aa('WikiContent.page_id',
                                                $page['Page']['id'])));
    } else {
      $content = array();
      $content['WikiContent']['version'] = 1; // 暫定
    }
    if (empty($this->data)) {
      if ($content) {
        $this->data = $content;
      }
    } else {
      if (!isset($page['Page']['id'])) {
        // wiki_pagesにレコード新規作成
        $page['Page']['wiki_id'] = $this->Wiki->id;
        $this->Wiki->Page->save($page);
        $this->data['WikiContent']['page_id'] = $this->Wiki->Page->id;
      }
      // wiki_contentsにレコード新規作成or更新
      $data = array_merge($content['WikiContent'],
                          $this->data['WikiContent']);
      $this->WikiContent->save($data);
      $this->redirect(array('controller' => 'wiki',
                            'action'     => 'index',
                            'project_id' => $this->params['project_id'],
                            'wikipage'   => $this->params['wikipage']));
    }
    $this->set('page', $page);
  }

#  # edit an existing page or a new one
#  def edit
#    @page = @wiki.find_or_new_page(params[:page])    
#    return render_403 unless editable?
#    @page.content = WikiContent.new(:page => @page) if @page.new_record?
#    
#    @content = @page.content_for_version(params[:version])
#    @content.text = initial_page_content(@page) if @content.text.blank?
#    # don't keep previous comment
#    @content.comments = nil
#    if request.get?
#      # To prevent StaleObjectError exception when reverting to a previous version
#      @content.version = @page.content.version
#    else
#      if !@page.new_record? && @content.text == params[:content][:text]
#        # don't save if text wasn't changed
#        redirect_to :action => 'index', :id => @project, :page => @page.title
#        return
#      end
#      #@content.text = params[:content][:text]
#      #@content.comments = params[:content][:comments]
#      @content.attributes = params[:content]
#      @content.author = User.current
#      # if page is new @page.save will also save content, but not if page isn't a new record
#      if (@page.new_record? ? @page.save : @content.save)
#        redirect_to :action => 'index', :id => @project, :page => @page.title
#      end
#    end
#  rescue ActiveRecord::StaleObjectError
#    # Optimistic locking exception
#    flash[:error] = l(:notice_locking_conflict)
#  end
#  

  // rename a page
  function rename() {
    $page = $this->_find_existing_page();
    // return render_403 unless editable?
    $page['Page']['redirect_existing_links'] = true;
    $this->set('original_title', $page['Page']['title']);
    if (empty($this->data)) {
      $this->data = $page;
    } else {
      $this->Wiki->Page->save($this->data);
      $this->Session->setFlash(__('Successful update.', true),
                               'default',
                               array('class'=>'flash flash_notice'));
      $this->redirect(array('controller' => 'wiki',
                            'action'     => 'index',
                            'project_id' => $this->params['project_id'],
                            'wikipage'   => $this->data['Page']['title']));
    }
  }

#  # rename a page
#  def rename
#    return render_403 unless editable?
#    @page.redirect_existing_links = true
#    # used to display the *original* title if some AR validation errors occur
#    @original_title = @page.pretty_title
#    if request.post? && @page.update_attributes(params[:wiki_page])
#      flash[:notice] = l(:notice_successful_update)
#      redirect_to :action => 'index', :id => @project, :page => @page.title
#    end
#  end
#  

  function protect() {
    $page = $this->_find_existing_page();
    $page['Page']['protected'] = $this->params['url']['protected'];
    $this->Wiki->Page->save($page);
    $this->redirect(array('controller' => 'wiki',
                          'action'     => 'index',
                          'project_id' => $this->params['project_id'],
                          'wikipage'   => $this->params['wikipage']));
  }

#  def protect
#    @page.update_attribute :protected, params[:protected]
#    redirect_to :action => 'index', :id => @project, :page => @page.title
#  end
#
#  # show page history
#  def history
#    @version_count = @page.content.versions.count
#    @version_pages = Paginator.new self, @version_count, per_page_option, params['p']
#    # don't load text    
#    @versions = @page.content.versions.find :all, 
#                                            :select => "id, author_id, comments, updated_on, version",
#                                            :order => 'version DESC',
#                                            :limit  =>  @version_pages.items_per_page + 1,
#                                            :offset =>  @version_pages.current.offset
#
#    render :layout => false if request.xhr?
#  end
#  
#  def diff
#    @diff = @page.diff(params[:version], params[:version_from])
#    render_404 unless @diff
#  end
#  
#  def annotate
#    @annotate = @page.annotate(params[:version])
#    render_404 unless @annotate
#  end

  // remove a wiki page and its history
  function destroy() {
    $page = $this->_find_existing_page();
    //return render_403 unless editable?
    $this->Wiki->Page->del($page['Page']['id']);
    $this->redirect(array('controller' => 'wiki',
                          'action'     => 'special',
                          'project_id' => $this->params['project_id'],
                          'wikipage'   => 'Page_index'));
  }

#  def destroy
#    return render_403 unless editable?
#    @page.destroy
#    redirect_to :action => 'special', :id => @project, :page => 'Page_index'
#  end
#
#  # display special pages
#  def special
#    page_title = params[:page].downcase
#    case page_title
#    # show pages index, sorted by title
#    when 'page_index', 'date_index'
#      # eager load information about last updates, without loading text
#      @pages = @wiki.pages.find :all, :select => "#{WikiPage.table_name}.*, #{WikiContent.table_name}.updated_on",
#                                      :joins => "LEFT JOIN #{WikiContent.table_name} ON #{WikiContent.table_name}.page_id = #{WikiPage.table_name}.id",
#                                      :order => 'title'
#      @pages_by_date = @pages.group_by {|p| p.updated_on.to_date}
#      @pages_by_parent_id = @pages.group_by(&:parent_id)
#    # export wiki to a single html file
#    when 'export'
#      @pages = @wiki.pages.find :all, :order => 'title'
#      export = render_to_string :action => 'export_multiple', :layout => false
#      send_data(export, :type => 'text/html', :filename => "wiki.html")
#      return      
#    else
#      # requested special page doesn't exist, redirect to default page
#      redirect_to :action => 'index', :id => @project, :page => nil and return
#    end
#    render :action => "special_#{page_title}"
#  end
#  
#  def preview
#    page = @wiki.find_page(params[:page])
#    # page is nil when previewing a new page
#    return render_403 unless page.nil? || editable?(page)
#    if page
#      @attachements = page.attachments
#      @previewed = page.content
#    end
#    @text = params[:content][:text]
#    render :partial => 'common/preview'
#  end
#
#  def add_attachment
#    return render_403 unless editable?
#    attach_files(@page, params[:attachments])
#    redirect_to :action => 'index', :page => @page.title
#  end
#
#private
#  
#  def find_wiki
#    @project = Project.find(params[:id])
#    @wiki = @project.wiki
#    render_404 unless @wiki
#  rescue ActiveRecord::RecordNotFound
#    render_404
#  end
#  
#  # Finds the requested page and returns a 404 error if it doesn't exist
#  def find_existing_page
#    @page = @wiki.find_page(params[:page])
#    render_404 if @page.nil?
#  end
#  

  function is_editable($page = null)
  {
    if ($page === null) {
      //$page = $this->page;
    }
    //$page->editable_by($current_user);
    return true;
  }
#  def editable?(page = @page)
#    page.editable_by?(User.current)
#  end

#  # Returns true if the current user is allowed to edit the page, otherwise false
#  def editable?(page = @page)
#    page.editable_by?(User.current)
#  end
#

  function _initial_page_content($page) {
    //helper = Redmine::WikiFormatting.helper_for(Setting.text_formatting)
    //extend helper unless self.instance_of?(helper)
    //helper.instance_method(:initial_page_content).bind(self).call(page)
  }


#  # Returns the default content of a new wiki page
#  def initial_page_content(page)
#    helper = Redmine::WikiFormatting.helper_for(Setting.text_formatting)
#    extend helper unless self.instance_of?(helper)
#    helper.instance_method(:initial_page_content).bind(self).call(page)
#  end
#end
}
