<?php
class NewsController extends AppController {
	var $name = 'News';
	var $uses = array( 'News', 'User', 'Project', 'Comment' ) ;
	var $helpers = array('Html', 'Form', 'Candy', 'Ajax');

  var $paginate = array( 'order' => array('News.created_on' => 'desc') ) ;
#class NewsController < ApplicationController
#  accept_key_auth :index
#  

  function beforeFilter()
  {
#  before_filter :find_news, :except => [:new, :index, :preview]
#  before_filter :find_project, :only => [:new, :preview]
#  before_filter :authorize, :except => [:index, :preview]
#  before_filter :find_optional_project, :only => :index
    $filters = array(
      '_authorize' => array('except' => array('index','preview')),
      '_find_news' => array('except' => array('add','index','preview')),
      '_find_project' => array('only' =>  array('add','preview')),
    );
    
    foreach ($filters as $name => $param) {
      
      if (array_key_exists('except',$param)) {
        if (in_array($this->action,$param['except'])) {
          continue;
        }
      }
      if (array_key_exists('only',$param)) {
        if (in_array($this->action,$param['only']) === false) {
          continue;
        }
      }
      $this->$name();
    }
    
    return parent::beforeFilter();
  }
  
  function index()
  {
#  def index
#    @news_pages, @newss = paginate :news,
#                                   :per_page => 10,
#                                   :conditions => (@project ? {:project_id => @project.id} : Project.visible_by(User.current)),
#                                   :include => [:author, :project],
#                                   :order => "#{News.table_name}.created_on DESC"    
#    respond_to do |format|
#      format.html { render :layout => false if request.xhr? }
#      format.atom { render_feed(@newss, :title => (@project ? @project.name : Setting.app_title) + ": #{l(:label_news_plural)}") }
#    end
#  end		
    $options = null ;
    if ( is_array($this->params) && array_key_exists('project_id', $this->params) ) {
      $options = array('Project.identifier'=>$this->params['project_id']) ;
    }
     	// TODO: view format の切り替え
		$this->set('newss', $this->paginate('News', $options));
	}
#  
	function show()
	{
#  def show
#    @comments = @news.comments
#    @comments.reverse! if User.current.wants_comments_in_reverse_order?
#  end
		//$this->data = $this->News->read(null, $this->params['news_id']);
      $this->data = $this->News->find('first', aa('conditions',aa('News.id',$this->params['id']),'recursive',3));
	  $this->set('news', $this->data);
	}

  function add()
  {
#  def new
#    @news = News.new(:project => @project, :author => User.current)
#    if request.post?
#      @news.attributes = params[:news]
#      if @news.save
#        flash[:notice] = l(:notice_successful_create)
#        Mailer.deliver_news_added(@news) if Setting.notified_events.include?('news_added')
#        redirect_to :controller => 'news', :action => 'index', :project_id => @project
#      end
#    end
#  end
		if (!empty($this->data)) {
			$this->News->create();

      $this->News->set( 'author_id', $this->current_user['id'] ) ;
      $this->News->set( 'project_id', $this->_project['Project']['id'] ) ;
      $this->News->set( 'created_on', date('Y-m-d H:i:s',time()) ) ;

			if ($this->News->save($this->data)) {
				$this->Session->setFlash(__('Successful creation.', true), 'default', array('class'=>'flash notice'));
				$this->redirect(array('controller'=>'projects', 'action' => $this->_project['Project']['identifier'], 'news/index'));
			} else {
        $this->Session->setFlash($this->validateErrors($this->News), 'default', array('class'=>'flash flash_error'));
		    $this->render( 'add' ) ;
		  }
		}
  }

  function edit($id = null)
  {
#  def edit
#    if request.post? and @news.update_attributes(params[:news])
#      flash[:notice] = l(:notice_successful_update)
#      redirect_to :action => 'show', :id => @news
#    end
#  end
		if (!empty($this->data)) {
      $this->News->set( 'id', $this->params['id'] ) ;
      $this->News->set( 'created_on', date('Y-m-d H:i:s',time()) ) ;
		    // TODO: パーミッションのチェック,request methodのチェック
			if ($this->News->save($this->data)) {
				$this->Session->setFlash(__('Successful update.',true), 'default', array('class'=>'flash notice'));
				$this->redirect(array('action'=>'show', 'id' => $this->params['id'], 'project_id' =>  $this->_project['Project']['id']));
			}
		}
  }

  function add_comment()
  {
#  def add_comment
#    @comment = Comment.new(params[:comment])
#    @comment.author = User.current
#    if @news.comments << @comment
#      flash[:notice] = l(:label_comment_added)
#      redirect_to :action => 'show', :id => @news
#    else
#      render :action => 'show'
#    end
#  end
    if (!empty($this->data)) {
      $this->Comment->create();
      // TODO: author_idを正しく設定する！
      $this->Comment->set( 'commented_type', 'News' ) ;
      $this->Comment->set( 'commented_id', $this->params['id'] ) ;
      $this->Comment->set( 'author_id', $this->current_user['id'] ) ;
        // $this->data['News'] って気持ち悪いけどどうしたら良い？
      $this->Comment->set( 'comments', $this->data['News']['comments'] ) ;
      $this->Comment->set( 'created_on', date('Y-m-d H:i:s',time()) ) ;
      $this->Comment->set( 'updated_on', date('Y-m-d H:i:s',time()) ) ;

    if ($this->Comment->save($this->data)) {
      $this->Session->setFlash(__('Successful creation.', true), 'default', array('class'=>'flash notice'));
      $this->redirect(array('action'=>'show', 'id' => $this->params['id'], 'project_id' => $this->params['project_id']));
    }
  }
  }
#
#  def destroy_comment
#    @news.comments.find(params[:comment_id]).destroy
#    redirect_to :action => 'show', :id => @news
#  end
#
  function destroy($id) 
  {
    $project = $this->News->findById($this->params['id']);
    if ( !$project ) {
      $this->cakeError('error404');
	}
	  
	if ($this->News->del($this->params['id'])) {
      // TODO: project_idを正しく設定する！
	  $this->Session->setFlash(__('Successful deletion.', true), 'default', array('class'=>'flash notice'));
	  $this->redirect(array(
	      'controller'=>'news',
	      'project_id' => $project['Project']['identifier'],
	      'action' => 'index'
	      ));
    } else {
      $this->cakeError('error404');
	}
  }
#  
#  def preview
#    @text = (params[:news] ? params[:news][:description] : nil)
#    render :partial => 'common/preview'
#  end
#  
#private
#  def find_news
#    @news = News.find(params[:id])
#    @project = @news.project
#  rescue ActiveRecord::RecordNotFound
#    render_404
#  end
#  
  function _find_news()
  {
    $this->_news = $this->News->find('first', array(
      'conditions'=>array('News.id' => $this->params['id']),
      'recursive'=>1
    ));
    if (empty($this->_news) or $this->_news === false) {
      $this->cakeError('error404');
    }
    $this->set(array('news'=>$this->_news));
    $this->params['project_id'] = $this->_news['Project']['identifier'];
  }
  
  function _find_project()
  {
#  def find_project
#    @project = Project.find(params[:project_id])
#  rescue ActiveRecord::RecordNotFound
#    render_404
#  end
    if ($this->_project = $this->Project->find('first', array(
      'conditions' => array(
        'Project.identifier' => $this->params['project_id'],
      ),
    ))) {
      $this->set('project', $this->_project);
    } else {
      $this->cakeError('error404');
    }
  }
  
#  
#  def find_optional_project
#    return true unless params[:project_id]
#    @project = Project.find(params[:project_id])
#    authorize
#  rescue ActiveRecord::RecordNotFound
#    render_404
#  end
#end
}