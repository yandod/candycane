<?php

App::Import('vendor', 'georgious-cakephp-yaml-migrations-and-fixtures/spyc/spyc');

class Query extends AppModel
{
  var $name = 'Query';
  var $belongsTo = array(
    'Project',
    'User',
  );
  var $actsAs = array(
    'Candy',
  );
  var $validate = array(
    'name' => array(
      'validates_presence_of'=>array('rule'=>array('notEmpty')),
      'validates_length_of'=>array('rule'=>array('maxLength', 255)),
    ),
    'filters' => array(
      'validates_presence_of'=>array('rule'=>array('validate_filters'))
    )
  );

  var $column_names;
  var $operators;
  var $operators_by_filter_type;
  var $default_show_filters;
  var $available_filters;
  var $filters = array();
  
  public function __construct($id = false, $table = null, $ds = null)
  {
    if (!$this->operators) {
      $this->operators = array(
        "="   => __('is'),
        "!"   => __('is not'),
        "o"   => __('open'),
        "c"   => __('closed'),
        "!*"  => __('none'),
        "*"   => __('all'),
        ">="  => '>=',
        "<="  => '<=',
        "<t+" => __('in less than'),
        ">t+" => __('in more than'),
        "t+"  => __('in'),
        "t"   => __('today'),
        "w"   => __('this week'),
        ">t-" => __('less than days ago'),
        "<t-" => __('more than days ago'),
        "t-"  => __('days ago'),
        "~"   => __('contains'),
        "!~"  => __("doesn't contain"),
      );
    }
    if (!$this->operators_by_filter_type) {
      $this->operators_by_filter_type = array(
        'list' => array( "=", "!" ),
        'list_status' => array( "o", "=", "!", "c", "*" ),
        'list_optional' => array( "=", "!", "!*", "*" ),
        'list_subprojects' => array( "*", "!*", "=" ),
        'date' => array( "<t+", ">t+", "t+", "t", "w", ">t-", "<t-", "t-" ),
        'date_past' => array( ">t-", "<t-", "t-", "t", "w" ),
        'string' => array( "=", "~", "!", "!~" ),
        'text' => array(  "~", "!~" ),
        'integer' => array( "=", ">=", "<=", "!*", "*" ),
      );
    }
    if (!$this->default_show_filters) {
      $this->default_show_filters = array(
        'status_id' => array(
          'operator' => "o",
          'values'   => array(""),
        ),
      );
    }
    parent::__construct($id, $table, $ds);
  }
  
  function available_filters($project = array(), $currentuser = array())
  {
    if(!empty($this->available_filters)) {
      return $this->available_filters;
    }    
    //$Status = & ClassRegistry::init('Status');
    $IssueStatus = & ClassRegistry::init('IssueStatus');
    $Enumeration = & ClassRegistry::init('Enumeration');
    $user_values = array();
    $tracker_values = array();
    $version_values = array();
    if ($currentuser) $user_values['me'] = __('me');
    if (isset($project['User'])) {
      foreach ($project['User'] as $user) {
        if ($currentuser && $currentuser['id'] == $user['id']) continue;
        $user_values[$user['id']] = $user['firstname'] . ' ' . $user['lastname'];
      }
    }
    if (isset($project['Tracker'])) {
      foreach ($project['Tracker'] as $tracker) $tracker_values[$tracker['id']] = $tracker['name'];
    }
    if (isset($project['Project']['id'])) {
      $Version = & ClassRegistry::init('Version');
      $version_values = $Version->find('list', array(
        'fields' => array(
          'Version.id',
          'Version.name',
        ),
        'conditions' => array(
            'Version.project_id' => $project['Project']['id']
        )
      ));
    }
    
    $available_filters = array(
      'status_id' => array(
        'type'   => 'list_status',
        'values' => $IssueStatus->find('list', array(
          'fields' => array(
            'IssueStatus.id',
            'IssueStatus.name',
          ),
        )),
        'order' => 1,
      ),
      'fixed_version_id' => array(
        'type' => 'list',
        'values' => $version_values,
        'order' => 10
      ),
      'start_date' => array(
        'type'  => 'date',
        'order' => 11,
      ),
      'estimated_hours' => array(
        'type'  => 'integer',
        'order' => 13,
      ),
      'created_on' => array(
        'type'  => 'date_past',
        'order' => 9,
      ),
      'priority_id' => array(
        'type'   => 'list',
        'values' => $Enumeration->find('list', array(
          'fields' => array(
            'Enumeration.id',
            'Enumeration.name',
          ),
          'conditions' => array(
            'Enumeration.opt' => 'IPRI',
          ),
          'order' => 'Enumeration.position',
        )),
        'order' => 3,
      ),
      'assigned_to_id' => array(
        'type'   => 'list_optional',
        'values' => $user_values,
        'order' => 4,
      ),
      'done_ratio' => array(
        'type'  => 'integer',
        'order' => 14,
      ),
      'updated_on' => array(
        'type'  => 'date_past',
        'order' => 10,
      ),
      'subject' => array(
        'type'  => 'text',
        'order' => 8,
      ),
      'tracker_id' => array(
        'type' => 'list',
        'values' => $tracker_values,
        'order' => 2,
      ),
      'due_date' => array(
        'type'  => 'date',
        'order' => 12,
      ),
      'author_id' => array(
        'type'   => 'list',
        'values' => $user_values,
        'order' => 5,
      ),
    );
    foreach ($available_filters as $k => $v) {
      $available_filters[$k]['operators'] = array();
      foreach ($this->operators_by_filter_type[$v['type']] as $operator) {
        $available_filters[$k]['operators'][$operator] = $this->operators[$operator];
      }
    }
    $this->available_filters = $available_filters;
    return $available_filters;
  }
  function show_filters($options = array())
  {
    $show_filters = $this->default_show_filters;
    return $show_filters;
  }

#  serialize :column_names
#  
#  attr_protected :project_id, :user_id
#  
#  validates_presence_of :name, :on => :save
#  validates_length_of :name, :maximum => 255
#    
#  @@operators = { "="   => :label_equals, 
#                  "!"   => :label_not_equals,
#                  "o"   => :label_open_issues,
#                  "c"   => :label_closed_issues,
#                  "!*"  => :label_none,
#                  "*"   => :label_all,
#                  ">="   => '>=',
#                  "<="   => '<=',
#                  "<t+" => :label_in_less_than,
#                  ">t+" => :label_in_more_than,
#                  "t+"  => :label_in,
#                  "t"   => :label_today,
#                  "w"   => :label_this_week,
#                  ">t-" => :label_less_than_ago,
#                  "<t-" => :label_more_than_ago,
#                  "t-"  => :label_ago,
#                  "~"   => :label_contains,
#                  "!~"  => :label_not_contains }
#
#  cattr_reader :operators
#    
#  @@operators_by_filter_type = { :list => [ "=", "!" ],
#                                 :list_status => [ "o", "=", "!", "c", "*" ],
#                                 :list_optional => [ "=", "!", "!*", "*" ],
#                                 :list_subprojects => [ "*", "!*", "=" ],
#                                 :date => [ "<t+", ">t+", "t+", "t", "w", ">t-", "<t-", "t-" ],
#                                 :date_past => [ ">t-", "<t-", "t-", "t", "w" ],
#                                 :string => [ "=", "~", "!", "!~" ],
#                                 :text => [  "~", "!~" ],
#                                 :integer => [ "=", ">=", "<=", "!*", "*" ] }
#
#  cattr_reader :operators_by_filter_type
#
#  @@available_columns = [
#    QueryColumn.new(:tracker, :sortable => "#{Tracker.table_name}.position"),
#    QueryColumn.new(:status, :sortable => "#{IssueStatus.table_name}.position"),
#    QueryColumn.new(:priority, :sortable => "#{Enumeration.table_name}.position", :default_order => 'desc'),
#    QueryColumn.new(:subject, :sortable => "#{Issue.table_name}.subject"),
#    QueryColumn.new(:author),
#    QueryColumn.new(:assigned_to, :sortable => "#{User.table_name}.lastname"),
#    QueryColumn.new(:updated_on, :sortable => "#{Issue.table_name}.updated_on", :default_order => 'desc'),
#    QueryColumn.new(:category, :sortable => "#{IssueCategory.table_name}.name"),
#    QueryColumn.new(:fixed_version, :sortable => "#{Version.table_name}.effective_date", :default_order => 'desc'),
#    QueryColumn.new(:start_date, :sortable => "#{Issue.table_name}.start_date"),
#    QueryColumn.new(:due_date, :sortable => "#{Issue.table_name}.due_date"),
#    QueryColumn.new(:estimated_hours, :sortable => "#{Issue.table_name}.estimated_hours"),
#    QueryColumn.new(:done_ratio, :sortable => "#{Issue.table_name}.done_ratio"),
#    QueryColumn.new(:created_on, :sortable => "#{Issue.table_name}.created_on", :default_order => 'desc'),
#  ]
#  cattr_reader :available_columns
#  
#  def initialize(attributes = nil)
#    super attributes
#    self.filters ||= { 'status_id' => {:operator => "o", :values => [""]} }
#    set_language_if_valid(User.current.language)
#  end
#  
#  def after_initialize
#    # Store the fact that project is nil (used in #editable_by?)
#    @is_for_all = project.nil?
#  end
#  
  function validate_filters() {
    foreach($this->filters as $field => $values) {
      # filter requires one or more values
      # filter doesn't require any value
      $value = $this->values_for($field);
      if(!(!is_null($value) && !empty($value[0]) || 
         in_array($this->operator_for($field), array("o", "c", "!*", "*", "t", "w"))
      )) {
        $this->invalidate($this->label_for($field), 'Please be sure to input.');
      }
    }
    return true;
  }
#  
#  def editable_by?(user)
#    return false unless user
#    # Admin can edit them all and regular users can edit their private queries
#    return true if user.admin? || (!is_public && self.user_id == user.id)
#    # Members can not edit public queries that are for all project (only admin is allowed to)
#    is_public && !@is_for_all && user.allowed_to?(:manage_public_queries, project)
#  end
#  
#  def available_filters
#    return @available_filters if @available_filters
#    
#    trackers = project.nil? ? Tracker.find(:all, :order => 'position') : project.rolled_up_trackers
#    
#    @available_filters = { "status_id" => { :type => :list_status, :order => 1, :values => IssueStatus.find(:all, :order => 'position').collect{|s| [s.name, s.id.to_s] } },       
#                           "tracker_id" => { :type => :list, :order => 2, :values => trackers.collect{|s| [s.name, s.id.to_s] } },                                                                                                                
#                           "priority_id" => { :type => :list, :order => 3, :values => Enumeration.find(:all, :conditions => ['opt=?','IPRI'], :order => 'position').collect{|s| [s.name, s.id.to_s] } },
#                           "subject" => { :type => :text, :order => 8 },  
#                           "created_on" => { :type => :date_past, :order => 9 },                        
#                           "updated_on" => { :type => :date_past, :order => 10 },
#                           "start_date" => { :type => :date, :order => 11 },
#                           "due_date" => { :type => :date, :order => 12 },
#                           "estimated_hours" => { :type => :integer, :order => 13 },
#                           "done_ratio" =>  { :type => :integer, :order => 14 }}
#    
#    user_values = []
#    user_values << ["<< #{l(:label_me)} >>", "me"] if User.current.logged?
#    if project
#      user_values += project.users.sort.collect{|s| [s.name, s.id.to_s] }
#    else
#      # members of the user's projects
#      user_values += User.current.projects.collect(&:users).flatten.uniq.sort.collect{|s| [s.name, s.id.to_s] }
#    end
#    @available_filters["assigned_to_id"] = { :type => :list_optional, :order => 4, :values => user_values } unless user_values.empty?
#    @available_filters["author_id"] = { :type => :list, :order => 5, :values => user_values } unless user_values.empty?
#  
#    if project
#      # project specific filters
#      unless @project.issue_categories.empty?
#        @available_filters["category_id"] = { :type => :list_optional, :order => 6, :values => @project.issue_categories.collect{|s| [s.name, s.id.to_s] } }
#      end
#      unless @project.versions.empty?
#        @available_filters["fixed_version_id"] = { :type => :list_optional, :order => 7, :values => @project.versions.sort.collect{|s| [s.name, s.id.to_s] } }
#      end
#      unless @project.active_children.empty?
#        @available_filters["subproject_id"] = { :type => :list_subprojects, :order => 13, :values => @project.active_children.collect{|s| [s.name, s.id.to_s] } }
#      end
#      add_custom_fields_filters(@project.all_issue_custom_fields)
#    else
#      # global filters for cross project issue list
#      add_custom_fields_filters(IssueCustomField.find(:all, :conditions => {:is_filter => true, :is_for_all => true}))
#    end
#    @available_filters
#  end
#  
  function get_filter_cond($model, $field, $operator, $values)
  {
    $negation = false;
    switch ($operator) {
    case '=':
      $operator = '';
      break;
	case '<=':
	case '>=':
	  return array(
		$model . '.' . $field . $operator . $values,
	  );
	  break;
    case '!':
      $operator = '';
      $negation = true;
      break;
    case '*':
      return null;
      break;
    case '!*':
      return array('or' => array(
        $field . ' !=' => null,
        $field => '',
      ));
      break;
    case 'o':
      if ($field != 'status_id') return;
      $model = 'Status';
      $field = 'is_closed';
      $operator = '';
      $values = false;
      break;
    case 'c':
      if ($field != 'status_id') return;
      $model = 'Status';
      $field = 'is_closed';
      $operator = '';
      $values = true;
      break;
    case '>t-':
      return $this->date_range_clause($model, $field, - $values, 0);
    case '<t-':
      return $this->date_range_clause($model, $field, null, - $values);
    case 't-':
      return $this->date_range_clause($model, $field, $values, - $values);
    case '>t+':
      return $this->date_range_clause($model, $field, $values, null);
    case '<t+':
      return $this->date_range_clause($model, $field, 0, $values);
    case 't+':
      return $this->date_range_clause($model, $field, $values, $values);
    case 't':
      return $this->date_range_clause($model, $field, 0, 0);
    case 'w':
      $from = __("'7'") == '7' ?
        (date('N') == 7 ? date('Y-m-d 00:00:00') : date('Y-m-d 00:00:00', time() - date('w') * 86400 - 86400)) :
          date('Y-m-d 00:00:00', time() - date('w') * 86400);
      return array(
        $model . '.' . $field . ' BETWEEN ? AND ?' => array(
          $from,
          date('Y-m-d H:i:s', strtotime($from) + 7 * 86400),
        ),
      );
#      from = l(:general_first_day_of_week) == '7' ?
#      # week starts on sunday
#      ((Date.today.cwday == 7) ? Time.now.at_beginning_of_day : Time.now.at_beginning_of_week - 1.day) :
#        # week starts on monday (Rails default)
#        Time.now.at_beginning_of_week
#      sql = "#{db_table}.#{db_field} BETWEEN '%s' AND '%s'" % [connection.quoted_date(from), connection.quoted_date(from + 7.days)]
    case '~':
      $operator = ' like';
      $values = '%' . str_replace('%', '%%', $values) . '%';
      break;
    case '!~':
      $operator = 'like';
      $values = '%' . str_replace('%', '%%', $values) . '%';
      return array('not' => array(
        $model . '.' . $field . ' ' . $operator => $values,
      ));
      break;
    }
    if(!$negation){
    	return array(
    	  $model . '.' . $field . (strlen($operator) ? $operator : '') => $values,
    	);
    }
    else{
    	return array('NOT' => array(
    	  $model . '.' . $field . (strlen($operator) ? $operator : '') => $values,
    	));
 
    }

#  def sql_for_field(field, value, db_table, db_field, is_custom_filter)
#    sql = ''
#    case operator_for field
#    when "="
#      sql = "#{db_table}.#{db_field} IN (" + value.collect{|val| "'#{connection.quote_string(val)}'"}.join(",") + ")"
#    when "!"
#      sql = "(#{db_table}.#{db_field} IS NULL OR #{db_table}.#{db_field} NOT IN (" + value.collect{|val| "'#{connection.quote_string(val)}'"}.join(",") + "))"
#    when "!*"
#      sql = "#{db_table}.#{db_field} IS NULL"
#      sql << " OR #{db_table}.#{db_field} = ''" if is_custom_filter
#    when "*"
#      sql = "#{db_table}.#{db_field} IS NOT NULL"
#      sql << " AND #{db_table}.#{db_field} <> ''" if is_custom_filter
#    when ">="
#      sql = "#{db_table}.#{db_field} >= #{value.first.to_i}"
#    when "<="
#      sql = "#{db_table}.#{db_field} <= #{value.first.to_i}"
#    when "o"
#      sql = "#{IssueStatus.table_name}.is_closed=#{connection.quoted_false}" if field == "status_id"
#    when "c"
#      sql = "#{IssueStatus.table_name}.is_closed=#{connection.quoted_true}" if field == "status_id"
#    when ">t-"
#      sql = date_range_clause(db_table, db_field, - value.first.to_i, 0)
#    when "<t-"
#      sql = date_range_clause(db_table, db_field, nil, - value.first.to_i)
#    when "t-"
#      sql = date_range_clause(db_table, db_field, - value.first.to_i, - value.first.to_i)
#    when ">t+"
#      sql = date_range_clause(db_table, db_field, value.first.to_i, nil)
#    when "<t+"
#      sql = date_range_clause(db_table, db_field, 0, value.first.to_i)
#    when "t+"
#      sql = date_range_clause(db_table, db_field, value.first.to_i, value.first.to_i)
#    when "t"
#      sql = date_range_clause(db_table, db_field, 0, 0)
#    when "w"
#      from = l(:general_first_day_of_week) == '7' ?
#      # week starts on sunday
#      ((Date.today.cwday == 7) ? Time.now.at_beginning_of_day : Time.now.at_beginning_of_week - 1.day) :
#        # week starts on monday (Rails default)
#        Time.now.at_beginning_of_week
#      sql = "#{db_table}.#{db_field} BETWEEN '%s' AND '%s'" % [connection.quoted_date(from), connection.quoted_date(from + 7.days)]
#    when "~"
#      sql = "#{db_table}.#{db_field} LIKE '%#{connection.quote_string(value.first)}%'"
#    when "!~"
#      sql = "#{db_table}.#{db_field} NOT LIKE '%#{connection.quote_string(value.first)}%'"
#    end
#    
#    return sql
#  end

  }
  function add_filter($field, $operator, $values) {
    if(empty($values)) {
      return;
    }
    # values must be an array
    if(!is_array($values)) {
      $values = array($values);
    }
    # check if field is defined as an available filter
    if (array_key_exists($field, $this->available_filters)) {
      $this->filter_options = $this->available_filters[$field];
      # check if operator is allowed for that filter
      #if @@operators_by_filter_type[filter_options[:type]].include? operator
      #  allowed_values = values & ([""] + (filter_options[:values] || []).collect {|val| val[1]})
      #  filters[field] = {:operator => operator, :values => allowed_values } if (allowed_values.first and !allowed_values.first.empty?) or ["o", "c", "!*", "*", "t"].include? operator
      #end
      $this->filters[$field] = compact('operator', 'values');
    }
  }
  
  function add_short_filter($field, $expression) {
    if (empry($expression)) return;
    preg_match('/^(o|c|\!|\*)?(.*)$/', $expression, $matches);
    $parms = $matches[0];
    $operator = empty($parms[0]) ? "=" : $parms[0];
    $values = empty($parms[1]) ? "" : $parms[1];
    $this->add_filter($field, $operator, $values);
  }
  
  function has_filter($field) {
    return !empty($this->filters[$field]);
  }
  
  function operator_for($field) {
    return $this->has_filter($field) ? $this->filters[$field]['operator'] : null;
  }
  
  function values_for($field) {
    return $this->has_filter($field) ? $this->filters[$field]['values'] : null;
  }
  
  function label_for($field) {
    if (array_key_exists($field, $this->available_filters)) {
      $label = $this->available_filters[$field]['name'];
    }
    if(empty($label)) {
      $label = preg_replace('/\_id$/', "", $field);
    }
    return $label;    
  }

	public function available_columns() {
#    return @available_columns if @available_columns
#    @available_columns = Query.available_columns
#    @available_columns += (project ? 
#                            project.all_issue_custom_fields :
#                            IssueCustomField.find(:all, :conditions => {:is_for_all => true})
#                           ).collect {|cf| QueryCustomFieldColumn.new(cf) }

#  @@available_columns = [
#    QueryColumn.new(:tracker, :sortable => "#{Tracker.table_name}.position"),
#    QueryColumn.new(:status, :sortable => "#{IssueStatus.table_name}.position"),
#    QueryColumn.new(:priority, :sortable => "#{Enumeration.table_name}.position", :default_order => 'desc'),
#    QueryColumn.new(:subject, :sortable => "#{Issue.table_name}.subject"),
#    QueryColumn.new(:author),
#    QueryColumn.new(:assigned_to, :sortable => "#{User.table_name}.lastname"),
#    QueryColumn.new(:updated_on, :sortable => "#{Issue.table_name}.updated_on", :default_order => 'desc'),
#    QueryColumn.new(:category, :sortable => "#{IssueCategory.table_name}.name"),
#    QueryColumn.new(:fixed_version, :sortable => "#{Version.table_name}.effective_date", :default_order => 'desc'),
#    QueryColumn.new(:start_date, :sortable => "#{Issue.table_name}.start_date"),
#    QueryColumn.new(:due_date, :sortable => "#{Issue.table_name}.due_date"),
#    QueryColumn.new(:estimated_hours, :sortable => "#{Issue.table_name}.estimated_hours"),
#    QueryColumn.new(:done_ratio, :sortable => "#{Issue.table_name}.done_ratio"),
#    QueryColumn.new(:created_on, :sortable => "#{Issue.table_name}.created_on", :default_order => 'desc'),
#  ]
		$columns = array(
			array(
				'name' => 'tracker',
				'sortable' => 'Tracker.postion',
				'default_order' => '',
				'caption' => __('tracker')
			),
			array(
				'name' => 'status',
				'sortable' => 'Tracker.postion',
				'default_order' => '',
				'caption' => __('status')
			),
			array(
				'name' => 'priority',
				'sortable' => 'Tracker.postion',
				'default_order' => '',
				'caption' => __('priority')
			),
			array(
				'name' => 'subject',
				'sortable' => 'Tracker.postion',
				'default_order' => '',
				'caption' => __('subject')
			),
			array(
				'name' => 'author',
				'sortable' => 'Tracker.postion',
				'default_order' => '',
				'caption' => __('author')
			),
			array(
				'name' => 'assigned_to',
				'sortable' => 'Tracker.postion',
				'default_order' => '',
				'caption' => __('assigned_to')
			),
			array(
				'name' => 'updated_on',
				'sortable' => 'Tracker.postion',
				'default_order' => '',
				'caption' => __('updated_on')
			),
			array(
				'name' => 'category',
				'sortable' => 'Tracker.postion',
				'default_order' => '',
				'caption' => __('category')
			),
			array(
				'name' => 'fixed_version',
				'sortable' => 'Tracker.postion',
				'default_order' => '',
				'caption' => __('fixed_version')
			),
			array(
				'name' => 'start_date',
				'sortable' => 'Tracker.postion',
				'default_order' => '',
				'caption' => __('start_date')
			),
			array(
				'name' => 'due_date',
				'sortable' => 'Tracker.postion',
				'default_order' => '',
				'caption' => __('due_date')
			),
			array(
				'name' => 'estimated_hours',
				'sortable' => 'Tracker.postion',
				'default_order' => '',
				'caption' => __('estimated_hours')
			),
			array(
				'name' => 'done_ratio',
				'sortable' => 'Tracker.postion',
				'default_order' => '',
				'caption' => __('done_ratio')
			),
			array(
				'name' => 'created_on',
				'sortable' => 'Tracker.postion',
				'default_order' => '',
				'caption' => __('created_on')
			),
		);
		return $columns;
	}
#  def columns
#    if has_default_columns?
#      available_columns.select {|c| Setting.issue_list_default_columns.include?(c.name.to_s) }
#    else
#      # preserve the column_names order
#      column_names.collect {|name| available_columns.find {|col| col.name == name}}.compact
#    end
#  end
#  
#  def column_names=(names)
#    names = names.select {|n| n.is_a?(Symbol) || !n.blank? } if names
#    names = names.collect {|n| n.is_a?(Symbol) ? n : n.to_sym } if names
#    write_attribute(:column_names, names)
#  end
#  
#  def has_column?(column)
#    column_names && column_names.include?(column.name)
#  end
#  
#  def has_default_columns?
#    column_names.nil? || column_names.empty?
#  end
#  
  function project_statement($query)
  {
    
  }
#  def project_statement
#    project_clauses = []
#    if project && !@project.active_children.empty?
#      ids = [project.id]
#      if has_filter?("subproject_id")
#        case operator_for("subproject_id")
#        when '='
#          # include the selected subprojects
#          ids += values_for("subproject_id").each(&:to_i)
#        when '!*'
#          # main project only
#        else
#          # all subprojects
#          ids += project.child_ids
#        end
#      elsif Setting.display_subprojects_issues?
#        ids += project.child_ids
#      end
#      project_clauses << "#{Project.table_name}.id IN (%s)" % ids.join(',')
#    elsif project
#      project_clauses << "#{Project.table_name}.id = %d" % project.id
#    end
#    project_clauses <<  Project.allowed_to_condition(User.current, :view_issues)
#    project_clauses.join(' AND ')
#  end
#
#  def statement
#    # filters clausess
#    filters_clauses = []
#    filters.each_key do |field|
#      next if field == "subproject_id"
#      v = values_for(field).clone
#      next unless v and !v.empty?
#            
#      sql = ''
#      is_custom_filter = false
#      if field =~ /^cf_(\d+)$/
#        # custom field
#        db_table = CustomValue.table_name
#        db_field = 'value'
#        is_custom_filter = true
#        sql << "#{Issue.table_name}.id IN (SELECT #{Issue.table_name}.id FROM #{Issue.table_name} LEFT OUTER JOIN #{db_table} ON #{db_table}.customized_type='Issue' AND #{db_table}.customized_id=#{Issue.table_name}.id AND #{db_table}.custom_field_id=#{$1} WHERE "
#      else
#        # regular field
#        db_table = Issue.table_name
#        db_field = field
#        sql << '('
#      end
#      
#      # "me" value subsitution
#      if %w(assigned_to_id author_id).include?(field)
#        v.push(User.current.logged? ? User.current.id.to_s : "0") if v.delete("me")
#      end
#      
#      sql = sql + sql_for_field(field, v, db_table, db_field, is_custom_filter)
#      
#      sql << ')'
#      filters_clauses << sql
#    end if filters and valid?
#    
#    (filters_clauses << project_statement).join(' AND ')
#  end
#  
#  private
#  
#  # Helper method to generate the WHERE sql for a +field+ with a +value+
#  def sql_for_field(field, value, db_table, db_field, is_custom_filter)
#    sql = ''
#    case operator_for field
#    when "="
#      sql = "#{db_table}.#{db_field} IN (" + value.collect{|val| "'#{connection.quote_string(val)}'"}.join(",") + ")"
#    when "!"
#      sql = "(#{db_table}.#{db_field} IS NULL OR #{db_table}.#{db_field} NOT IN (" + value.collect{|val| "'#{connection.quote_string(val)}'"}.join(",") + "))"
#    when "!*"
#      sql = "#{db_table}.#{db_field} IS NULL"
#      sql << " OR #{db_table}.#{db_field} = ''" if is_custom_filter
#    when "*"
#      sql = "#{db_table}.#{db_field} IS NOT NULL"
#      sql << " AND #{db_table}.#{db_field} <> ''" if is_custom_filter
#    when ">="
#      sql = "#{db_table}.#{db_field} >= #{value.first.to_i}"
#    when "<="
#      sql = "#{db_table}.#{db_field} <= #{value.first.to_i}"
#    when "o"
#      sql = "#{IssueStatus.table_name}.is_closed=#{connection.quoted_false}" if field == "status_id"
#    when "c"
#      sql = "#{IssueStatus.table_name}.is_closed=#{connection.quoted_true}" if field == "status_id"
#    when ">t-"
#      sql = date_range_clause(db_table, db_field, - value.first.to_i, 0)
#    when "<t-"
#      sql = date_range_clause(db_table, db_field, nil, - value.first.to_i)
#    when "t-"
#      sql = date_range_clause(db_table, db_field, - value.first.to_i, - value.first.to_i)
#    when ">t+"
#      sql = date_range_clause(db_table, db_field, value.first.to_i, nil)
#    when "<t+"
#      sql = date_range_clause(db_table, db_field, 0, value.first.to_i)
#    when "t+"
#      sql = date_range_clause(db_table, db_field, value.first.to_i, value.first.to_i)
#    when "t"
#      sql = date_range_clause(db_table, db_field, 0, 0)
#    when "w"
#      from = l(:general_first_day_of_week) == '7' ?
#      # week starts on sunday
#      ((Date.today.cwday == 7) ? Time.now.at_beginning_of_day : Time.now.at_beginning_of_week - 1.day) :
#        # week starts on monday (Rails default)
#        Time.now.at_beginning_of_week
#      sql = "#{db_table}.#{db_field} BETWEEN '%s' AND '%s'" % [connection.quoted_date(from), connection.quoted_date(from + 7.days)]
#    when "~"
#      sql = "#{db_table}.#{db_field} LIKE '%#{connection.quote_string(value.first)}%'"
#    when "!~"
#      sql = "#{db_table}.#{db_field} NOT LIKE '%#{connection.quote_string(value.first)}%'"
#    end
#    
#    return sql
#  end
#  
#  def add_custom_fields_filters(custom_fields)
#    @available_filters ||= {}
#    
#    custom_fields.select(&:is_filter?).each do |field|
#      case field.field_format
#      when "text"
#        options = { :type => :text, :order => 20 }
#      when "list"
#        options = { :type => :list_optional, :values => field.possible_values, :order => 20}
#      when "date"
#        options = { :type => :date, :order => 20 }
#      when "bool"
#        options = { :type => :list, :values => [[l(:general_text_yes), "1"], [l(:general_text_no), "0"]], :order => 20 }
#      else
#        options = { :type => :string, :order => 20 }
#      end
#      @available_filters["cf_#{field.id}"] = options.merge({ :name => field.name })
#    end
#  end
#  
#  # Returns a SQL clause for a date or datetime field.
  function date_range_clause($model, $field, $from, $to)
  {
    $cond = array();
    if (strlen($from)) $cond[] = array($model . '.' . $field . ' >' => date('Y-m-d 23:59:59', strtotime($from . ' day')));
    if (strlen($to))   $cond[] = array($model . '.' . $field . ' <=' => date('Y-m-d 23:59:59', strtotime($to . ' day')));
    return $cond;
  }
#  def date_range_clause(table, field, from, to)
#    s = []
#    if from
#      s << ("#{table}.#{field} > '%s'" % [connection.quoted_date((Date.yesterday + from).to_time.end_of_day)])
#    end
#    if to
#      s << ("#{table}.#{field} <= '%s'" % [connection.quoted_date((Date.today + to).to_time.end_of_day)])
#    end
#    s.join(' AND ')
#  end
#end
  function beforeSave()
  {
    if (isset($this->filters)) {
      $rb_filters = array();
      // Convert for ruby serialize format
      foreach($this->filters as $field=>$filter) {
        $rb_operator = '"'.$filter['operator'].'"';
        $rb_values = array();
        foreach($filter['values'] as $value) {
          $rb_values[] = is_numeric($value) ? '"'.$value.'"' : $value;
        }
        $rb_filters[$field] = array(':operator'=>$rb_operator, ':values'=>$rb_values);
      }
      // To YAML format
      $this->data[$this->name]['filters'] = Spyc::YAMLDump($rb_filters);
    }
    return true;
  }
  
  function getFilters($query = false) {
    if(!$query) {
      $query = $this->data[$this->name];
    }
    $filters = array();
    $rb_filters = Spyc::YAMLLoad($query['filters']);
    foreach($rb_filters as $field=>$filter) {
      if($filter[0] == 'values:') {
        // For Ruby serialize format:
        $values = array();
        foreach($filter as $value) {
          if($value == 'values:') {
            continue;
          } elseif(is_array($value) && !empty($value['operator'])) {
            $operator = $value['operator'];
          } else {
            $values[] = $value;
          }
        }
      } else {
        // For PHP yaml dump format:
        $operator = $filter[0]['operator'];
        $values = $filter[1];
      }
      $filters[$field] = compact('operator', 'values');
    }
    return $filters;
  }

}
