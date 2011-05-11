<?php
class QueriesComponent extends Object
{
  var $name = 'Queries';
  var $controller;
  var $query_filter_cond = array();
  var $show_filters = array();
  
  function startup(& $controller)
  {
    $this->controller =& $controller;
  }
  
  function retrieve_query($query_id = 0, $forse_set_filter = null)
  {
    $query_id = (int)$query_id;

    $self = $this->controller;
    $Query = $self->Query;
    $force_show_filters = $Query->show_filters();
    $self->set('force_show_filters', $force_show_filters);
    $show_filters = $forse_set_filter ? a() : $force_show_filters;
    $available_filters = $Query->available_filters($self->_project, $self->current_user);
    if (!isset($self->data['Filter'])) {
        $self->data['Filter'] = a();
    }

    foreach ($show_filters as $field => $options) {
      $self->data['Filter']['fields_' . $field] = $field;
      $self->data['Filter']['operators_' . $field] = $options['operator'];
      $self->data['Filter']['values_' . $field] = $options['values'];
    }

    if ($query_id > 0) {
      /* 
      $conditions = array("Query.project_id" => null);
      if(!empty($self->_project)) {
        $conditions['OR'] = array('project_id' => $self->_project['Project']['id']);
      }
      */
      $Query->read(null, $query_id);
      $show_filters = $Query->getFilters();
      foreach ($show_filters as $field => $options) {
        $self->data['Filter']['fields_' . $field] = $field;
        $self->data['Filter']['operators_' . $field] = $options['operator'];        
        switch ($available_filters[$field]['type']) {
        case 'list':
        case 'list_optional':
        case 'list_status':
        case 'list_subprojects':
          $self->data['Filter']['values_' . $field] = $options['values'];
          break;
        default :
          $self->data['Filter']['values_' . $field] = $this->get_option_value($options['values']);
          break;
        }
      }
    } else {
      if (isset($self->params['url']['set_filter']) || $forse_set_filter) {
          if ( !is_array($self->params['form'])) {
              $self->params['form'] = array(
                  'fields' => array(),
                  'operators' => array(),
                  'values' => array(),
              );
          }
          if (isset($self->params['url']) && is_array($self->params['url']) ) {
              foreach ($self->params['url'] as $criteria_name => $criteria_val) {
                  $self->params['form']['fields'][$criteria_name] = $criteria_name;
                  $self->params['form']['operators'][$criteria_name] = '=';
                  $self->params['form']['values'][$criteria_name] = array($criteria_val);
                  if ($criteria_name == 'status_id') {
                      $self->params['form']['operators'][$criteria_name] = $criteria_val;
                  }                  
              }
          }
        foreach ($self->params['form']['fields'] as $field) {
          $operator = $self->params['form']['operators'][$field];
          $values = isset($self->params['form']['values'][$field]) ? $self->params['form']['values'][$field] : null;
          if (isset($available_filters[$field])) {
            $show_filters[$field] = $available_filters[$field];
            $self->data['Filter']['fields_' . $field] = $field;
            $self->data['Filter']['operators_' . $field] = $operator;
            $self->data['Filter']['values_' . $field] = $values;
          }
        }
      }
    }
    if ($self->_project) $this->query_filter_cond = array('Issue.project_id' => $self->_project['Project']['id']);
    foreach ($show_filters as $field => $options) {
      $operator = $self->data['Filter']['operators_' . $field];
      $values = $self->data['Filter']['values_' . $field];
      switch ($field) {
      case 'author_id':
      case 'assigned_to_id':
        foreach($values as $index=>$value) {
          if ($value == 'me') {
            if ($self->current_user) {
              $values[$index] = $self->current_user['id'];
            }
          }
        }
        break;
      }
      if ($add_filter_cond = $Query->get_filter_cond('Issue', $field, $operator, $values)) {
        $query['Query']['filter_cond'][] = $add_filter_cond;
        $this->query_filter_cond = am($this->query_filter_cond, $add_filter_cond);
      }
    }
    $self->set('available_filters', $available_filters);
    $self->set('show_filters', $show_filters);
    $this->show_filters = $show_filters;
  }
  
  function get_option_value($value) {
    if(is_array($value)) {
      return $this->get_option_value($value[0]);
    }
    return $value;
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
