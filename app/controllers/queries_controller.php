<?php
class QueriesController extends AppController
{
  var $name = 'Queries';
  var $uses = array(
    'User',
    'Query',
    'Project',
  );
  var $helpers = array(
    'Queries',
    'QueryColumn',
    'CustomField',
    'Number',
    'Watchers',
    'Journals'
  );
  var $components = array(
    'RequestHandler',
    'Queries',
  );
  var $_query;
  var $_show_filters;
  var $_project;

#  menu_item :issues
  function beforeFilter()
  {
    $this->MenuManager->menu_item('issues');
    if (isset($this->params['query_id'])) {
        
        $query = $this->Query->find('first',
            array('conditions' => array('Query.id' => $this->params['query_id']))
        );
        $this->params['project_id'] = $query['Project']['project_id'];
    }
    return parent::beforeFilter();
  }
  
#  before_filter :find_query, :except => :new
#  before_filter :find_optional_project, :only => :new
#  
  function add() {
    $this->Queries->retrieve_query(0, true);
    $this->set('query_new_record', true);
    
    $query = $this->Query->defaults();
    $query['project'] = $this->_project;
    $query['user'] = $this->current_user;
    $query['is_public'] = $query['project'] && $this->User->is_allowed_to($this->current_user, ':manage_public_queries', $this->_project) || $this->current_user['admin'] ? true : false;
    $query['default_columns'] = true;

    if(!empty($this->data) && $this->RequestHandler->isPost() && $this->_get_param('confirm') && !$this->RequestHandler->isAjax()) {
      $this->data['Query']['project_id'] = $this->_project['Project']['id'];
      $this->data['Query']['user_id'] = $this->current_user['id'];
      
      foreach($this->params['form']['fields'] as $field) {
        $this->Query->add_filter($field, $this->params['form']['operators'][$field], $this->params['form']['values'][$field]);
      }
      $this->Query->save($this->data);
      if(empty($this->Query->validationErrors)) {
        $this->Session->setFlash(__('Successful creation.', true), 'default', array('class'=>'flash flash_notice'));
        $this->redirect(array('controller'=>'issues', 'action'=>'index', 'project_id'=>$this->_project['Project']['identifier']));
      }
    }

    if (isset($this->data['Query'])) $query = am($query, $this->data['Query']);
    $this->data = am($this->data, array(
      'Query' => $query,
    ));    
    if ($this->RequestHandler->isAjax()) $this->layout = 'ajax';
  }

  function edit() {
      
    if(!empty($this->data)) {
      $this->Queries->retrieve_query($this->params['query_id'], true);
      $query = $this->data['Query'];
      $query['project'] = empty($this->Query->data['Project']) ? a() : array('Project' => $this->Query->data['Project']);
      $query['project_id'] = $this->Query->data['Project']['id'];
      $query['user_id'] = $this->Query->data['User']['id'];
      $query['filters'] = array();

      foreach($this->params['form']['fields'] as $field) {
        $this->Query->add_filter($field, $this->params['form']['operators'][$field], $this->params['form']['values'][$field]);
      }
      # @query.attributes = params[:query]
      if(!empty($query['query_is_for_all'])) $query['project_id'] = null;
      $query['is_public'] = $query['project'] && $this->User->is_allowed_to($this->current_user, ':manage_public_queries', $query['project']) || $this->current_user['admin'] ? true : false;
      # @query.column_names = nil if params[:default_columns]
      $this->Query->save($query);
      if(empty($this->Query->validationErrors)) {
        $this->Session->setFlash(__('Successful creation.', true), 'default', array('class'=>'flash flash_notice'));
        $this->redirect(array('controller'=>'issues', 'action'=>'index', 'project_id'=>$this->params['project_id']));
      }
      return;
    } elseif (isset($this->params['query_id'])) {
      $this->Queries->retrieve_query($this->params['query_id'], true);
      $this->data['Query'] = $this->Query->data['Query'];
      $this->data['Query']['default_columns'] = true;
      if(empty($this->Query->data['Query']['project_id'])) $this->data['Query']['query_is_for_all'] = "1";
      return;
    }
    $this->cakeError('error404');
  }

#  def edit
#    if request.post?
#      @query.filters = {}
#      params[:fields].each do |field|
#        @query.add_filter(field, params[:operators][field], params[:values][field])
#      end if params[:fields]
#      @query.attributes = params[:query]
#      @query.project = nil if params[:query_is_for_all]
#      @query.is_public = false unless (@query.project && current_role.allowed_to?(:manage_public_queries)) || User.current.admin?
#      @query.column_names = nil if params[:default_columns]
#      
#      if @query.save
#        flash[:notice] = l(:notice_successful_update)
#        redirect_to :controller => 'issues', :action => 'index', :project_id => @project, :query_id => @query
#      end
#    end
#  end
#
  function destroy() {
    if ($this->params['query_id']) {
        $this->Query->delete($this->params['query_id']);
        $this->redirect(array('controller'=>'issues', 'action'=>'index', 'project_id'=>$this->params['project_id'], '?'=>array('set_filter' => 1)));
    }
    $this->cakeError('error404');
  }
#  
#private
#  def find_query
#    @query = Query.find(params[:id])
#    @project = @query.project
#    render_403 unless @query.editable_by?(User.current)
#  rescue ActiveRecord::RecordNotFound
#    render_404
#  end
#  
#  def find_optional_project
#    @project = Project.find(params[:project_id]) if params[:project_id]
#    User.current.allowed_to?(:save_queries, @project, :global => true)
#  rescue ActiveRecord::RecordNotFound
#    render_404
#  end
#end
}
