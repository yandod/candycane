<?php
/**
 * QueriesComponent
 * handle search parameter from query strings or saved filter in db.
 */
class QueriesComponent extends Component
{
    public $name = 'Queries';
    public $controller;
    public $query_filter_cond = array();
    public $show_filters = array();

/**
 * startup method
 * @param Controller $controller 
 */
    public function startup(Controller $controller)
    {
        $this->controller = $controller;
    }

/**
 * retrieve_query
 * @param type $query_id
 * @param type $forse_set_filter 
 */
  public function retrieve_query($query_id = 0, $forse_set_filter = null)
  {
    $query_id = (int)$query_id;
    $self = $this->controller;
    $Query = $self->Query;
    
    $available_filters = $Query->available_filters($self->_project, $self->current_user);
    if (!isset($self->request->data['Filter'])) {
        $self->request->data['Filter'] = array();
    }

    //build filter condition from db or query string
    if ($query_id > 0) {
        $this->retrieveFromDb($query_id, $available_filters);
    } else {
        $this->retrieveFromParameter($forse_set_filter, $available_filters);
    }
    if ($self->_project) {
		$this->query_filter_cond = array(
			'Issue.project_id' => $self->_project['Project']['id']
		);
	} else {
        $this->query_filter_cond = $self->Project->get_visible_by_condition($self->current_user);
    }
    foreach ($this->show_filters as $field => $options) {
        if (!isset($self->request->data['Filter']['operators_' . $field])) {
            continue;
        }
      $operator = $self->request->data['Filter']['operators_' . $field];
      $values = $self->request->data['Filter']['values_' . $field];
      switch ($field) {
      case 'author_id':
      case 'assigned_to_id':
		  if (!is_array($values)) {
              $values = array($values);
		  }
        foreach($values as $index => $value) {
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
    $self->set('show_filters', $this->show_filters);
    //$this->show_filters = $show_filters;
  }
  
/**
 * pick value from whatever single item or multiple items.
 * @param mixed $value
 * @return singel value 
 */
    public function get_option_value($value)
    {
        if(is_array($value) && count($value) == 1) {
            return $this->get_option_value($value[0]);
        }
        return $value;
    }

/**
 * build parameter from query_id 
 */
    public function retrieveFromDb($query_id, $available_filters)
    {
        $this->controller->Query->read(null, $query_id);
        $this->show_filters = $this->controller->Query->getFilters();
        // Showing saved queries
        foreach ($this->show_filters as $field => $options) {
            //$self->data['Filter']['fields_' . $field] = $field;
            //$self->data['Filter']['operators_' . $field] = $options['operator'];        
            $temp = array();
            $temp['Filter']['fields_' . $field] = $field;
            $temp['Filter']['operators_' . $field] = $options['operator'];

            switch ($available_filters[$field]['type']) {
                case 'list':
                case 'list_optional':
                case 'list_status':
                case 'list_subprojects':
                    //$self->data['Filter']['values_' . $field] = $options['values'];
                    $temp['Filter']['values_' . $field] = $options['values'];
                    break;
                default :
                    //$self->data['Filter']['values_' . $field] = $this->get_option_value($options['values']);
                    $temp['Filter']['values_' . $field] = $this->get_option_value($options['values']);
                    break;
            }
            $temp['Filter'] = array_merge($this->controller->request->data['Filter'], $temp['Filter']);
            $this->controller->request->data = array_merge($this->controller->request->data, $temp);
        }
    }
    
/**
 * build paremter from query string 
 */
    public function retrieveFromParameter($forse_set_filter, $available_filters)
    {
        $self = $this->controller;
        if (
            !isset($self->request->query['set_filter']) &&
            !$forse_set_filter
        ) {
            $this->show_filters = $this->controller->Query->show_filters();;
            foreach ($this->show_filters as $field => $options) {
                $self->request->data['Filter']['fields_' . $field] = $field;
                $self->request->data['Filter']['operators_' . $field] = $options['operator'];
                $self->request->data['Filter']['values_' . $field] = $options['values'];
            }
            return;
        }
        
        
        if (isset($self->request->query['values']) && is_array($self->request->query['values']) ) {
            $temp = array();
			if (!empty($self->request->query['set_filter'])) {
				
				$temp['set_filter'] = $self->request->query['set_filter'];
			}
            foreach ($self->request->query['values'] as $criteria_name => $criteria_val) {
                //$self->params['form']['fields'][$criteria_name] = $criteria_name;
                //$self->params['form']['operators'][$criteria_name] = '=';
                //$self->params['form']['values'][$criteria_name] = array($criteria_val);
                if (!in_array($criteria_name, $self->request->query['fields'])) {
                    continue;
                }
                $temp['fields'][$criteria_name] = $criteria_name;
                $temp['operators'][$criteria_name] = $self->request->query['operators'][$criteria_name];
                $temp['values'][$criteria_name] = array($criteria_val);
                if ($criteria_name == 'status_id') {
                    //$self->params['form']['operators'][$criteria_name] = $criteria_val;
                    //$temp['operators'][$criteria_name] = $criteria_val[0];
                }                  
            }
            $self->request->query = $temp;		              
        }
        if ( !isset($self->request->query['fields'])) {
            $self->request->query['fields'] = array();
            $self->request->query['operators'] = array();
            $self->request->query['values'] = array();
        }
        
        foreach ($self->request->query['fields'] as $field) {
            $operator = $self->request->query['operators'][$field];
            $values = isset($self->request->query['values'][$field]) ? $self->request->query['values'][$field] : null;
            if (isset($available_filters[$field])) {
                $this->show_filters[$field] = $available_filters[$field];
                // to avoid the error:
                // Indirect modification of overloaded property...
                // we use a temporal array.
                //$self->data['Filter']['fields_' . $field] = $field;
                //$self->data['Filter']['operators_' . $field] = $operator;
                //$self->data['Filter']['values_' . $field] = $values;
                $temp = array();
                $temp['Filter']['fields_' . $field] = $field;
                $temp['Filter']['operators_' . $field] = $operator;
                $temp['Filter']['values_' . $field] = $this->get_option_value($values);
                $temp['Filter'] = array_merge($self->request->data['Filter'], $temp['Filter']);
                $self->request->data = array_merge($self->request->data, $temp);
            }
        }
    }
}
