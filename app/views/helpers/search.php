<?php
class SearchHelper extends AppHelper {
    var $helpers = array('Html','Form');
#  def highlight_tokens(text, tokens)
#    return text unless text && tokens && !tokens.empty?
#    re_tokens = tokens.collect {|t| Regexp.escape(t)}
#    regexp = Regexp.new "(#{re_tokens.join('|')})", Regexp::IGNORECASE    
#    result = ''
#    text.split(regexp).each_with_index do |words, i|
#      if result.length > 1200
#        # maximum length of the preview reached
#        result << '...'
#        break
#      end
#      if i.even?
#        result << h(words.length > 100 ? "#{words[0..44]} ... #{words[-45..-1]}" : words)
#      else
#        t = (tokens.index(words.downcase) || 0) % 4
#        result << content_tag('span', h(words), :class => "highlight token-#{t}")
#      end
#    end
#    result
#  end
#  
  function type_label($t) {
    return __(ucfirst(strtolower(Inflector::singularize(Inflector::humanize($t)))),true);
  }
  
  function project_select_tag($scope,$currentuser,$mainproject){
    $options = array('all' => __('All Projects',true));
    if (!empty($currentuser['memberships'])) {
      $options['my_projects'] = __('My projects',true);
    }
#    options << [l(:label_and_its_subprojects, @project.name), 'subprojects'] unless @project.nil? || @project.active_children.empty?
#    options << [@project.name, ''] unless @project.nil?
#    select_tag('scope', options_for_select(options, params[:scope].to_s)) if options.size > 1
    return $this->Form->select('scope',$options,null,array('name'=>'scope'),false);
  }
#  
#  def render_results_by_type(results_by_type)
#    links = []
#    # Sorts types by results count
#    results_by_type.keys.sort {|a, b| results_by_type[b] <=> results_by_type[a]}.each do |t|
#      c = results_by_type[t]
#      next if c == 0
#      text = "#{type_label(t)} (#{c})"
#      links << link_to(text, :q => params[:q], :titles_only => params[:title_only], :all_words => params[:all_words], :scope => params[:scope], t => 1)
#    end
#    ('<ul>' + links.map {|link| content_tag('li', link)}.join(' ') + '</ul>') unless links.empty?
#  end
}
