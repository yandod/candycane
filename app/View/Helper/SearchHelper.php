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
    return __(ucfirst(strtolower(Inflector::singularize(Inflector::humanize($t)))));
  }
  
  function project_select_tag($scope,$currentuser,$mainproject){
    $options = array('all' => __('All Projects'));
    if (!empty($currentuser['memberships'])) {
      $options['my_projects'] = __('My projects');
    }
#    options << [l(:label_and_its_subprojects, @project.name), 'subprojects'] unless @project.nil? || @project.active_children.empty?
#    options << [@project.name, ''] unless @project.nil?
#    select_tag('scope', options_for_select(options, params[:scope].to_s)) if options.size > 1
    return $this->Form->select(
		'scope',
		$options,
		array(
			'name' => 'scope',
			'empty' => false
		)
	);
  }
#  
  function render_results_by_type($results_by_type,$params){
    $links = array();
    # Sorts types by results count
    foreach ($results_by_type as $k => $v) {
      if (count($v)) {
        $results_by_type[$k][0]['scope_type'] = $k;
      }
    }
    usort($results_by_type,array($this,'sort_callback'));
#    results_by_type.keys.sort {|a, b| results_by_type[b] <=> results_by_type[a]}.each do |t|
    foreach ($results_by_type as $t) {
      $c = count($t);
      if ($c == 0) {
        continue;
      }
#      text = "#{type_label(t)} (#{c})"
      $text = $this->type_label($t[0]['scope_type'])." ({$c})";
      $links[] = $this->Html->link(
        $text,
        array('?' => array(
          'q' => $params->query['q'],
          'titles_only' => $params->query['all_words'],
          'all_words' => $params->query['q'],
          'scope' => $params->query['scope'],
          $t[0]['scope_type'] => 1,
        ))
      );
#      links << link_to(text, :q => params[:q], :titles_only => params[:title_only], :all_words => params[:all_words], :scope => params[:scope], t => 1)
    }
#    ('<ul>' + links.map {|link| content_tag('li', link)}.join(' ') + '</ul>') unless links.empty?
    if (empty($links)) {
      return;
    }
    return '<ul><li>'.implode('</li> <li>',$links).'</li></ul>';
  }
  function sort_callback($a,$b){
    if (count($a) == count($b)) {
      return 0;
    }
    return (count($a) > count($b)) ? -1 : 1;
  }
}
