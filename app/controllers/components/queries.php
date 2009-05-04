<?php
class QueriesComponent extends Component
{
  var $name = 'Queries';
  var $controller;
  var $query_filter_cond = array();
  var $show_filters = array();
  
  function startup(& $controller)
  {
    $this->controller =& $controller;
  }
  
  function retrieve_query($forse_set_filter = null)
  {
    $self = $this->controller;
    $Query =& ClassRegistry::getObject('Query');
    $self->set('force_show_filters', $force_show_filters = $Query->show_filters());
    $show_filters = isset($self->params['url']['set_filter']) || $forse_set_filter ? a() : $force_show_filters;
    $available_filters = $Query->available_filters($self->_project, $self->current_user);
    if (!isset($self->data['Filter'])) $self->data['Filter'] = a();
    foreach ($show_filters as $field => $options) {
      $self->data['Filter']['fields_' . $field] = $field;
      $self->data['Filter']['operators_' . $field] = $options['operator'];
      $self->data['Filter']['values_' . $field] = $options['values'];
    }
    if (isset($self->params['query_id'])) {
    } else {
      if ($self->_project) $this->query_filter_cond[] = array('Issue.project_id' => $self->_project['Project']['id']);
      if (isset($self->params['url']['set_filter'], $self->params['form']['fields']) || $forse_set_filter) {
        foreach ($self->params['form']['fields'] as $field) {
          $operator = $self->params['form']['operators'][$field];
          $value = isset($self->params['form']['values'][$field]) ? $self->params['form']['values'][$field] : null;
          if (isset($available_filters[$field])) {
            $show_filters[$field] = $available_filters[$field];
            $self->data['Filter']['fields_' . $field] = $field;
            $self->data['Filter']['operators_' . $field] = $operator;
            $self->data['Filter']['values_' . $field] = $value;
          }
        }
      }
    }
    foreach ($show_filters as $field => $options) {
      $operator = $self->data['Filter']['operators_' . $field];
      $value = $self->data['Filter']['values_' . $field];
      switch ($field) {
      case 'author_id':
      case 'assigned_to_id':
        if ($value == 'me') {
          if ($self->current_user) {
            $value = $self->current_user['id'];
          } else {
            continue;
          }
        }
        break;
      }
      if ($add_filter_cond = $Query->get_filter_cond('Issue', $field, $operator, $value)) {
        $query['Query']['filter_cond'][] = $add_filter_cond;
      }
    }
    $self->set('available_filters', $available_filters);
    $self->set('show_filters', $this->show_filters = $show_filters);
  }
#  # Retrieve query from session or build a new query
#  def retrieve_query
#    if !params[:query_id].blank?
#      cond = "project_id IS NULL"
#      cond << " OR project_id = #{@project.id}" if @project
#      @query = Query.find(params[:query_id], :conditions => cond)
#      @query.project = @project
#      session[:query] = {:id => @query.id, :project_id => @query.project_id}
#    else
#      if params[:set_filter] || session[:query].nil? || session[:query][:project_id] != (@project ? @project.id : nil)
#        # Give it a name, required to be valid
#        @query = Query.new(:name => "_")
#        @query.project = @project
#        if params[:fields] and params[:fields].is_a? Array
#          params[:fields].each do |field|
#            @query.add_filter(field, params[:operators][field], params[:values][field])
#          end
#        else
#          @query.available_filters.keys.each do |field|
#            @query.add_short_filter(field, params[field]) if params[field]
#          end
#        end
#        session[:query] = {:project_id => @query.project_id, :filters => @query.filters}
#      else
#        @query = Query.find_by_id(session[:query][:id]) if session[:query][:id]
#        @query ||= Query.new(:name => "_", :project => @project, :filters => session[:query][:filters])
#        @query.project = @project
#      end
#    end
#  end
  
}