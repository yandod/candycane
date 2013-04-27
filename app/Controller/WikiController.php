<?php

class WikiController extends AppController
{
    //var $helpers = array('attachments');
    var $uses = array('Wiki', 'WikiContent', 'Project', 'User');
    var $helpers = array(
        'Time',
        'Number',
        'Wiki',
        'Js' => 'Prototype');

    function index()
    {
        $page_title = null;
        if (isset($this->request->params['wikipage'])) {
            $page_title = $this->request->params['wikipage'];
        }
        $page = $this->Wiki->find_or_new_page($page_title);
        if (!isset($page['WikiPage']['id'])) {
            $this->edit();
            $this->render('edit');
            return;
        }

        // Managing wiki breadcrumb through CakePHP Session
        if ($page_title == null) {
            $this->Session->write('Wiki_pages', array());
        }
        $wiki_pages = $this->Session->read('Wiki_pages');
        $new_page = array($page['WikiPage']['title'], $this->request->params['project_id']);
        if (($key = array_search($new_page, $wiki_pages)) !== false) {
            unset($wiki_pages[$key]);
        }
        $wiki_pages[] = $new_page;
        if (count($wiki_pages) > 5) {
            array_shift($wiki_pages);
        }
        $this->Session->write('Wiki_pages', $wiki_pages);
        $this->set('wiki_pages', $wiki_pages);

        $version = isset($this->request->query['version']) ? $this->request->query['version'] : null;
        $content = $this->Wiki->WikiPage->content_for_version($version);
        $export = isset($this->request->query['export']) ? $this->request->query['export'] : null;
        if ($export === 'html') {
            //export = render_to_string :action => 'export', :layout => false
            //send_data(export, :type => 'text/html', :filename => "#{@page.title}.html")
            return;
        } elseif ($export === 'txt') {
            // send_data(@content.text, :type => 'text/plain', :filename => "#{@page.title}.txt")
            return;
        }
        $this->set('page', $page);
        $this->set('content', $content);
        $this->set('editable', $this->is_editable());
        $attachments = $this->Wiki->WikiPage->findAttachments($this->Wiki->WikiPage->id); //data['WikiPage']['id']);
        $attachments_deletable = $this->Wiki->WikiPage->is_attachments_deletable($this->current_user, $this->_project);
        $this->set(compact('attachments'));
        $this->set(compact('attachments_deletable'));
        $this->render('show');
    }

    function special()
    {
        $page_title = strtolower($this->request->params['wikipage']);
        switch ($page_title) {
            // show pages index, sorted by title
            case 'page_index':
            case 'date_index':
                // eager load information about last updates, without loading text
                $this->Wiki->WikiPage->recursive = -1;
                $options = array('conditions'
                => array(
                    'WikiPage.wiki_id' => $this->Wiki->field('id')
                ),
                    'fields'
                    => 'WikiPage.*, WikiContent.updated_on',
                    'joins'
                    => array(array(
                        "type" => 'LEFT',
                        "table" => 'wiki_contents',
                        "alias" => 'WikiContent',
                        "conditions" => 'WikiContent.page_id=WikiPage.id')),
                    'order' => 'WikiPage.title');
                //'order' => 'Content.updated_on DESC');
                $pages = $this->Wiki->WikiPage->find('all', $options);
                $this->set('pages', $pages);

                // 以下、viewのための整形
                foreach ($pages as $page) {
                    $day = date('Y-m-d', strtotime($page['WikiContent']['updated_on']));
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
                    'action' => 'index',
                    'project_id' => $this->request->params['project_id'],
                    'wikipage' => null));
                break;
        }
        $this->render("special_${page_title}");
    }

    function beforeFilter()
    {
        parent::beforeFilter();
        $this->_find_wiki();

        $only = array('rename', 'protect', 'history', 'diff', 'annotate', 'add_attachment', 'destroy');
        if (in_array($this->request->action, $only)) {
            //$this->_find_existing_page();
        }
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
    function edit()
    {
        $page_title = null;
        if (isset($this->request->params['wikipage'])) {
            $page_title = $this->request->params['wikipage'];
        }
        $page = $this->Wiki->find_or_new_page($page_title);

        if (empty($this->request->data)) {
            $this->request->data = $page;
        } else {
            $save_data = array();
            if (!isset($save_data['WikiPage']['id'])) {
                // wiki_pagesにレコード新規作成
                $save_data = $page;
                $save_data['WikiPage']['wiki_id'] = $this->Wiki->id;
            } else {
                // wiki_pagesは既に存在
                if ($page['WikiContent']['text'] == $this->request->data['WikiContent']['text']) {
                    // don't save if text wasn't changed
                    $this->redirect(array('controller' => 'wiki',
                        'action' => 'index',
                        'project_id' => $this->request->params['project_id'],
                        'wikipage' => $this->request->params['wikipage']));
                    return;
                }
                // fixme: wiki_pagesに更新が無いのにUPDATE文が走ってしまう。
                $save_data['WikiPage']['id'] = $page['WikiPage']['id'];
            }
            // wiki_contentsにレコード新規作成or更新
            $save_data['WikiContent'] = $this->request->data['WikiContent'];
            if (isset($page['WikiContent']['id'])) {
                $save_data['WikiContent']['id'] = $page['WikiContent']['id'];
            }
            if (empty($save_data['WikiContent']['version'])) {
                $save_data['WikiContent']['version'] = 1; // 暫定
            }
            if ($this->Wiki->WikiPage->saveAll($save_data)) {
                $event = new CakeEvent(
                    'Controller.Candy.wikiEditAfterSave',
                    $this,
                    array(
                        'wiki' => $this->Wiki
                    )
                );
                $this->getEventManager()->dispatch($event);

                $this->redirect(array('controller' => 'wiki',
                    'action' => 'index',
                    'project_id' => $this->request->params['project_id'],
                    'wikipage' => $this->request->params['wikipage']));
            }
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
    function rename()
    {
        $page = $this->_find_existing_page();
        // return render_403 unless editable?
        $page['WikiPage']['redirect_existing_links'] = true;
        $this->set('original_title', $page['WikiPage']['title']);
        if (empty($this->request->data)) {
            $this->request->data = $page;
        } else {
            if ($this->Wiki->WikiPage->save($this->request->data)) {
                $this->Session->setFlash(__('Successful update.'),
                    'default',
                    array('class' => 'flash flash_notice'));
                $this->redirect(array('controller' => 'wiki',
                    'action' => 'index',
                    'project_id' => $this->request->params['project_id'],
                    'wikipage' => $this->request->data['WikiPage']['title']));
            }
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

    function protect()
    {
        $page = $this->_find_existing_page();
        $page['WikiPage']['protected'] = $this->request->query['protected'];
        $this->Wiki->WikiPage->save($page);
        $this->redirect(array('controller' => 'wiki',
            'action' => 'index',
            'project_id' => $this->request->params['project_id'],
            'wikipage' => $this->request->params['wikipage']));
    }

#  def protect
#    @page.update_attribute :protected, params[:protected]
#    redirect_to :action => 'index', :id => @project, :page => @page.title
#  end
#

    // show page history
    function history()
    {
        $page = $this->_find_existing_page();
        $this->set('page', $page);
        $conditions = array('WikiContentVersion.page_id' => $page['WikiPage']['id']);
        $this->paginate = array('fields' => array('WikiContentVersion.id',
            'Author.firstname',
            'Author.lastname',
            'Author.login',
            'WikiContentVersion.comments',
            'WikiContentVersion.updated_on',
            'WikiContentVersion.version'),
            'conditions' => $conditions,
            'order' => array('WikiContentVersion.version' => 'DESC')
        );
        $versions = $this->paginate($this->Wiki->WikiPage->WikiContent->WikiContentVersion);
        $this->set('versions', $versions);
    }

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
    function destroy()
    {
        $page = $this->_find_existing_page();
        //return render_403 unless editable?
        $this->Wiki->WikiPage->delete($page['WikiPage']['id']);
        $this->redirect(array('controller' => 'wiki',
            'action' => 'special',
            'project_id' => $this->request->params['project_id'],
            'wikipage' => 'Page_index'));
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

    function preview()
    {
        $this->layout = 'ajax';
        $this->set('content', $this->request->data);
        $this->render('/Elements/wiki/content');
    }

    function add_attachment()
    {
        $project_id = $this->viewVars['main_project']['Project']['project_id'];

        $page = $this->Wiki->find_or_new_page($this->request->params['wikipage']);
        if (!empty($this->request->params['form'])) {
            $attachments = $this->Wiki->WikiPage->attach_files($this->request->params['form'], $this->current_user);
            if (!empty($attachments['unsaved'])) {
                $this->Session->setFlash(sprintf(__("%d file(s) could not be saved."), count($attachments['unsaved'])), 'default', array('class' => 'flash flash_warning'));
            }

        }
        $this->redirect(array('controller' => 'wiki',
            'action' => 'index',
            'project_id' => $this->request->params['project_id'],
            'wikipage' => $this->request->params['wikipage']));

    }

    // private

    function _find_wiki()
    {
        $project_id = $this->viewVars['main_project']['Project']['id'];
        // projectsとwikisは1:1関係なので、アソシエーションを使わずにアクセス
        $this->Wiki->recursive = -1;
        $wiki = $this->Wiki->findByProjectId($project_id);
        if (!$wiki) {
            throw new NotFoundException();
        }
        $this->Wiki->recursive = 1;
        $this->Wiki->id = $wiki['Wiki']['id'];
        $this->set('wiki', $wiki);
    }

    function _find_existing_page()
    {
        $page = $this->Wiki->find_page($this->request->params['wikipage']);
        if (!$page) {
            throw new NotFoundException();
        }
        return $page;
    }

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

    function _initial_page_content($page)
    {
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