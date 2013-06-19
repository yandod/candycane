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
        'Journals',
        'Js'
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
        if (isset($this->request->params['query_id'])) {

            $query = $this->Query->find('first',
                array('conditions' => array('Query.id' => $this->request->params['query_id']))
            );
            $this->request->params['project_id'] = $query['Project']['project_id'];
        }
        return parent::beforeFilter();
    }

#  before_filter :find_query, :except => :new
#  before_filter :find_optional_project, :only => :new
#  
    function add()
    {
        $this->Queries->retrieve_query(0, true);
        $this->set('query_new_record', true);

        $query = $this->Query->defaults();
        $query['project'] = $this->_project;
        $query['user'] = $this->current_user;
        $query['is_public'] = $query['project'] && $this->User->is_allowed_to($this->current_user, ':manage_public_queries', $this->_project) || $this->current_user['admin'] ? true : false;
        $query['default_columns'] = true;

        if (!empty($this->request->data) && $this->RequestHandler->isPost() && $this->_get_param('confirm') && !$this->RequestHandler->isAjax()) {
            $this->request->data['Query']['project_id'] = $this->_project['Project']['id'];
            $this->request->data['Query']['user_id'] = $this->current_user['id'];

            foreach ($this->request->data['fields'] as $field) {
                $this->Query->add_filter($field, $this->request->data['operators'][$field], $this->request->data['values'][$field]);
            }
            $this->Query->save($this->request->data);
            if (empty($this->Query->validationErrors)) {
                $this->Session->setFlash(__('Successful creation.'), 'default', array('class' => 'flash flash_notice'));
                $this->redirect(array('controller' => 'issues', 'action' => 'index', 'project_id' => $this->_project['Project']['identifier']));
            }
        }

        if (isset($this->request->data['Query'])) $query = am($query, $this->request->data['Query']);
        $this->request->data = am($this->request->data, array(
            'Query' => $query,
        ));
        if ($this->RequestHandler->isAjax()) $this->layout = 'ajax';
    }

    function edit()
    {

        if (!empty($this->request->data)) {
            $this->Queries->retrieve_query($this->request->params['query_id'], true);
            $query = $this->request->data['Query'];
            $query['project'] = empty($this->Query->data['Project']) ? array() : array('Project' => $this->Query->data['Project']);
            $query['project_id'] = $this->Query->data['Project']['id'];
            $query['user_id'] = $this->Query->data['User']['id'];
            $query['filters'] = array();

            foreach ($this->request->data['fields'] as $field) {
                $this->Query->add_filter($field, $this->request->data['operators'][$field], $this->request->data['values'][$field]);
            }
            # @query.attributes = params[:query]
            if (!empty($query['query_is_for_all'])) $query['project_id'] = null;
            $query['is_public'] = $query['project'] && $this->User->is_allowed_to($this->current_user, ':manage_public_queries', $query['project']) || $this->current_user['admin'] ? true : false;
            # @query.column_names = nil if params[:default_columns]
            $this->Query->save($query);
            if (empty($this->Query->validationErrors)) {
                $this->Session->setFlash(__('Successful creation.'), 'default', array('class' => 'flash flash_notice'));
                $project_id = $this->request->params['project_id'];
                if (empty($project_id)) {
                    $project_id = null;
                }
                $this->redirect(array('controller' => 'issues', 'action' => 'index', 'project_id' => $project_id));
            }
            return;
        } elseif (isset($this->request->params['query_id'])) {
            $this->Queries->retrieve_query($this->request->params['query_id'], true);
            $this->request->data['Query'] = $this->Query->data['Query'];
            $this->request->data['Query']['default_columns'] = true;
            if (empty($this->Query->data['Query']['project_id'])) $this->request->data['Query']['query_is_for_all'] = "1";
            return;
        }
        throw new NotFoundException();
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
    function destroy()
    {
        if ($this->request->params['query_id']) {
            $this->Query->delete($this->request->params['query_id']);
            $this->redirect(array('controller' => 'issues', 'action' => 'index', 'project_id' => $this->request->params['project_id'], '?' => array('set_filter' => 1)));
        }
        throw new NotFoundException();
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
