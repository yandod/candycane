<?php
class SearchController extends AppController
{
  var $uses = array('Issue');
#  before_filter :find_optional_project
#
  var $helpers = array('Search','Text');
#  helper :messages
#  include MessagesHelper
#
  function index(){
    $question = isset($this->params['url']['q']) ? trim($this->params['url']['q']) : '';
    #    @all_words = params[:all_words] || (params[:submit] ? false : true)
    $all_words = isset($this->params['url']['all_words']) && $this->params['url']['all_words'];
    $titles_only = isset($this->params['url']['titles_only']) && $this->params['url']['titles_only'];

    $scope = isset($this->params['url']['scope']) ? trim($this->params['url']['scope']) : '';
    $projects_to_search = null;
#    projects_to_search =
#      case params[:scope]
#      when 'all'
#        nil
#      when 'my_projects'
#        User.current.memberships.collect(&:project)
#      when 'subprojects'
#        @project ? ([ @project ] + @project.active_children) : nil
#      else
#        @project
#      end
#
    $offset = null;          
#    offset = nil
#    begin; offset = params[:offset].to_time if params[:offset]; rescue; end
#    
#    # quick jump to an issue
    if (preg_match('/^#?(\d+)$/', $question, $match)) {
        $conditions = $this->Project->get_visible_by_condition($this->current_user);
        $conditions['Issue.id'] = $question;
        $ret = $this->Issue->find('first',array(
            'fields' => array('Issue.id'),
            'conditions' => $conditions
        ));
        if (!empty($ret)) {
            $this->redirect(array(
              'controller' => 'issues',
              'action' => 'show',
              'id' => $question
            ));
        }
    }
#    
#    @object_types = %w(issues news documents changesets wiki_pages messages projects)
    $object_types = array('issues','news','wiki_pages','projects');
#    if projects_to_search.is_a? Project
#      # don't search projects
#      @object_types.delete('projects')
#      # only show what the user is allowed to view
#      @object_types = @object_types.select {|o| User.current.allowed_to?("view_#{o}".to_sym, projects_to_search)}
#    end
#      
#    @scope = @object_types.select {|t| params[t]}
#    @scope = @object_types if @scope.empty?
    $scope_types = array_intersect($object_types,array_keys($this->params['url']));
    if (empty($scope_types)) {
      $scope_types = $object_types;
    }
#    
#    # extract tokens from the question
#    # eg. hello "bye bye" => ["hello", "bye bye"]
#    @tokens = @question.scan(%r{((\s|^)"[\s\w]+"(\s|$)|\S+)}).collect {|m| m.first.gsub(%r{(^\s*"\s*|\s*"\s*$)}, '')}
#    # tokens must be at least 3 character long
#    @tokens = @tokens.uniq.select {|w| w.length > 2 }
#    
#    if !@tokens.empty?
    if (!empty($question)) {
#      # no more than 5 tokens to search for
#      @tokens.slice! 5..-1 if @tokens.size > 5
#      # strings used in sql like statement
#      like_tokens = @tokens.collect {|w| "%#{w.downcase}%"}      

      $results = array();
      $results_by_type = array();
      $limit = 10;

      foreach ($scope_types as $s) {
        $model = ClassRegistry::init(Inflector::classify($s));
        $fields = Set::classicExtract($model->filterArgs,'{n}.name');
        $conditions = array();
        if ($s !== 'wiki_pages') { //TODO: wiki_pages relation
          $conditions = $this->Project->get_visible_by_condition($this->current_user);
        }
        if ($titles_only) {
            $fields = array_intersect(array('title','subject','name'),$fields);
        }
        $or_conditions = $model->parseCriteria(array_fill_keys($fields,$question));
        $conditions['OR'] = $or_conditions;
        $r = $model->find('all',array(
            'conditions' => $conditions,
            'recursive' => 2
        ));
        foreach ($r as $k => $v) {
            //wiki_page doesn't relay to Project directory.
            if (isset($v['Wiki']['Project'])) {
                $v['Project'] = $v['Wiki']['Project'];
            }
            $r[$k] = $v + $model->create_event_data($v);
        }
        $results += $r;
        $results_by_type[$s] = $r;
#        r, c = s.singularize.camelcase.constantize.search(like_tokens, projects_to_search,
#          :all_words => @all_words,
#          :titles_only => @titles_only,
#          :limit => (limit+1),
#          :offset => offset,
#          :before => params[:previous].nil?)
#        @results += r
#        @results_by_type[s] += c
      }
#      @results = @results.sort {|a,b| b.event_datetime <=> a.event_datetime}
#      if params[:previous].nil?
#        @pagination_previous_date = @results[0].event_datetime if offset && @results[0]
#        if @results.size > limit
#          @pagination_next_date = @results[limit-1].event_datetime 
#          @results = @results[0, limit]
#        end
#      else
#        @pagination_next_date = @results[-1].event_datetime if offset && @results[-1]
#        if @results.size > limit
#          @pagination_previous_date = @results[-(limit)].event_datetime 
#          @results = @results[-(limit), limit]
#        end
#      end
#    else
#      @question = ""
    }
#    render :layout => false if request.xhr?
    $this->set('question',$question);
    $this->set('scope',$scope);
    $this->set('object_types',$object_types);
    $this->set('scope_types',$scope_types);
    $this->set('results',$results);
    $this->set('results_by_type',$results_by_type);
  }
#
#private  
#  def find_optional_project
#    return true unless params[:id]
#    @project = Project.find(params[:id])
#    check_project_privacy
#  rescue ActiveRecord::RecordNotFound
#    render_404
#  end
#end
}
