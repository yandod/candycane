<?php

/**
 * News Controller
 *
 * @package candycane
 * @package candycane.controllers
 */
class NewsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'News';

    /**
     * Models to use
     *
     * @var array
     */
    public $uses = array('News', 'User', 'Project', 'Comment');

    /**
     * View helpers
     *
     * @var array
     */
    public $helpers = array('Html', 'Form', 'Candy', 'Js' => array('Prototype'));

    /**
     * Components
     *
     * @var array
     */
    public $components = array(
        'RequestHandler',
        'Mailer'
    );

    /**
     * Pagination options
     *
     * @var array
     */
    public $paginate = array('order' => array('News.created_on' => 'desc'));

    #  accept_key_auth :index

    /**
     * Before Filter
     *
     * @return void
     */
    public function beforeFilter()
    {
        #  before_filter :find_news, :except => [:new, :index, :preview]
        #  before_filter :find_project, :only => [:new, :preview]
        #  before_filter :authorize, :except => [:index, :preview]
        #  before_filter :find_optional_project, :only => :index

        $filters = array(
            '_find_news' => array('except' => array('add', 'index', 'preview')),
            '_find_project' => array('only' => array('add', 'preview')),
        );
        $this->authorize = array('except' => array('index', 'preview'));
        foreach ($filters as $name => $param) {
            if (array_key_exists('except', $param)) {
                if (in_array($this->request->action, $param['except'])) {
                    continue;
                }
            }
            if (array_key_exists('only', $param)) {
                if (in_array($this->request->action, $param['only']) === false) {
                    continue;
                }
            }
            $this->$name();
        }

        return parent::beforeFilter();
    }

    /**
     * Index
     *
     * @return void
     */
    public function index()
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

        $options = null;
        if (is_array($this->request->params) && array_key_exists('project_id', $this->request->params)) {
            $options = array('Project.identifier' => $this->request->params['project_id']);
        }
        // TODO: view format の切り替え
        $this->set('newss', $this->paginate('News', $options));
    }

    /**
     * Show
     *
     * @return void
     */
    public function show()
    {
        #  def show
        #    @comments = @news.comments
        #    @comments.reverse! if User.current.wants_comments_in_reverse_order?
        #  end
        //$this->request->data = $this->News->read(null, $this->request->params['news_id']);
        $this->request->data = $this->News->find(
            'first',
            array(
                'conditions' => array(
                    'News.id' => $this->request->params['id']
                ),
                'recursive' => 3
            )
        );
        $this->set('news', $this->request->data);
    }

    /**
     * Add
     *
     * @return void
     */
    public function add()
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
        if (!empty($this->request->data)) {
            $this->News->create();

            $this->News->set('author_id', $this->current_user['id']);
            $this->News->set('project_id', $this->_project['Project']['id']);
            $this->News->set('created_on', date('Y-m-d H:i:s', time()));

            if ($this->News->save($this->request->data)) {
                $this->Session->setFlash(__('Successful creation.'), 'default', array('class' => 'flash notice'));
                $this->News->Project->id = $this->_project['Project']['id'];
                $this->Mailer->deliver_news_added($this->News);
                $this->redirect(array('controller' => 'news', 'action' => 'index', 'project_id' => $this->_project['Project']['identifier']));
            } else {
                $this->Session->setFlash($this->validateErrors($this->News), 'default', array('class' => 'flash flash_error'));
                $this->render('add');
            }
        }
    }

    /**
     * Edit
     *
     * @param string $id ID
     * @return void
     */
    public function edit($id = null)
    {
        #  def edit
        #    if request.post? and @news.update_attributes(params[:news])
        #      flash[:notice] = l(:notice_successful_update)
        #      redirect_to :action => 'show', :id => @news
        #    end
        #  end
        if (!empty($this->request->data)) {
            $this->News->set('id', $this->request->params['id']);
            $this->News->set('created_on', date('Y-m-d H:i:s', time()));
            // TODO: パーミッションのチェック,request methodのチェック
            if ($this->News->save($this->request->data)) {
                $this->Session->setFlash(__('Successful update.'), 'default', array('class' => 'flash notice'));
                $this->redirect(array('action' => 'show', 'id' => $this->request->params['id'], 'project_id' => $this->_project['Project']['id']));
            }
        }
    }

    /**
     * Add comment
     *
     * @return void
     */
    public function add_comment()
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
        if (!empty($this->request->data)) {
            $this->Comment->create();
            // TODO: author_idを正しく設定する！
            $this->Comment->set('commented_type', 'News');
            $this->Comment->set('commented_id', $this->request->params['id']);
            $this->Comment->set('author_id', $this->current_user['id']);
            // $this->request->data['News'] って気持ち悪いけどどうしたら良い？
            $this->Comment->set('comments', $this->request->data['News']['comments']);
            $this->Comment->set('created_on', date('Y-m-d H:i:s', time()));
            $this->Comment->set('updated_on', date('Y-m-d H:i:s', time()));

            if ($this->Comment->save($this->request->data)) {
                $this->Session->setFlash(__('Successful creation.'), 'default', array('class' => 'flash notice'));
                $this->redirect(array('action' => 'show', 'id' => $this->request->params['id'], 'project_id' => $this->request->params['project_id']));
            }
        }
    }

    #  def destroy_comment
    #    @news.comments.find(params[:comment_id]).destroy
    #    redirect_to :action => 'show', :id => @news
    #  end

    /**
     * Destroy
     *
     * @param string $id ID
     * @return void
     */
    public function destroy($id)
    {
        $project = $this->News->findById($this->request->params['id']);
        if (!$project) {
            throw new NotFoundException();
        }

        if ($this->News->delete($this->request->params['id'])) {
            // TODO: project_idを正しく設定する！
            $this->Session->setFlash(__('Successful deletion.'), 'default', array('class' => 'flash notice'));
            $this->redirect(array(
                'controller' => 'news',
                'project_id' => $project['Project']['identifier'],
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException();
        }
    }

    #  def preview
    #    @text = (params[:news] ? params[:news][:description] : nil)
    #    render :partial => 'common/preview'
    #  end

    /**
     * Preview
     *
     * @return void
     */
    public function preview()
    {
        if ($this->RequestHandler->isAjax()) {
            Configure::write('debug', 0);
            $this->layout = 'ajax';
        }

        if (array_key_exists('description', $this->request->data['News'])) {
            $text = $this->request->data['News']['description'];
        } else {
            $text = '';
        }
        $this->set(compact('text'));
        $this->render('/common/_preview');
    }

    #private
    #  def find_news
    #    @news = News.find(params[:id])
    #    @project = @news.project
    #  rescue ActiveRecord::RecordNotFound
    #    render_404
    #  end

    /**
     * Find news
     *
     * @return void
     * @access protected
     */
    protected function _find_news()
    {
        $this->_news = $this->News->find('first', array(
            'conditions' => array('News.id' => $this->request->params['id']),
            'recursive' => 1
        ));
        if (empty($this->_news) || $this->_news === false) {
            throw new NotFoundException();
        }
        $this->set(array('news' => $this->_news));
        $this->request->params['project_id'] = $this->_news['Project']['identifier'];
    }

    #  def find_project
    #    @project = Project.find(params[:project_id])
    #  rescue ActiveRecord::RecordNotFound
    #    render_404
    #  end

    /**
     * Find Project
     *
     * @return void
     * @access protected
     */
    protected function _find_project()
    {
        if ($this->_project = $this->Project->find('first', array(
            'conditions' => array(
                'Project.identifier' => $this->request->params['project_id'],
            ),
        ))
        ) {
            $this->set('project', $this->_project);
        } else {
            throw new NotFoundException();
        }
    }

    #  def find_optional_project
    #    return true unless params[:project_id]
    #    @project = Project.find(params[:project_id])
    #    authorize
    #  rescue ActiveRecord::RecordNotFound
    #    render_404
    #  end
    #end
}