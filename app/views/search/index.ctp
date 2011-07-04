<h2><?php echo $candy->html_title(__('Search',true)) ?></h2>

<div class="box">
<?php echo $form->create(false,array('type'=>'get','action'=>'index'))?>
<p><?php echo $form->input('q',array(
  'size' => 60,
  'id' => 'search-input',
  'label' => false,
  'div' => false,
  'name' => 'q',
  'value' => $question
))?>
<?php echo $javascript->codeblock("Field.focus('search-input')")?>
<?php echo $search->project_select_tag(
	$scope,
	$currentuser,
	isset($main_project) ? $main_project : null
  ) ?>
<label for="all_words"><?php echo $form->checkbox('all_words',array(
  'name' => 'all_words',
  'value' => '1',
  'checked' => isset($this->params['url']['all_words']) && $this->params['url']['all_words']
))?> <?php echo __('All words')?></label>
<label for="titles_only"><?php echo $form->checkbox('titles_only',array(
  'name' => 'titles_only',
  'value' => '1',
  'checked' => isset($this->params['url']['titles_only']) && $this->params['url']['titles_only']
))?> <?php echo __('Search titles only')?></label>
</p>
<p>
<?php foreach($object_types as $t): ?>
<label for="<?php echo $t ?>"><?php
// TODO:waiting cake1.3
//  echo $html->tag('checkbox',array(
//    'name' => $t,
//    'value' => 1,
//    'checked' => in_array($t,$scope_types) 
//  ));
  $checked = in_array($t,$scope_types) ? 'checked' : '';
  echo "<input type='checkbox' name='{$t}' value='1' id='{$t}' {$checked}>";
?> <?php echo $search->type_label($t)?></label>
<?php endforeach; ?>
</p>
<p><?php echo $form->submit(__('Submit',true),array('name' => 'submit'))?></p>
<?php echo $form->end();?>
</div>

<?php if (isset($results) && is_array($results) && count($results)): ?>
    <div id="search-results-counts">
    <%= render_results_by_type(@results_by_type) unless @scope.size == 1 %>
    </div>
    
    <h3><?php echo __('Results') ?> (<?php echo array_sum(array_map('count',$results_by_type))?>)</h3>
    <dl id="search-results">
      <?php foreach ($results as $e): ?>
        <dt class="<?php echo $e['type']?>"><?php if(empty($main_project) || (!empty($main_project) && $main_project['Project']['id'] != $e['project']['id'])) {
		  echo $html->tag('span', h($e['project']['name']), array('class' => 'project'));
		} ?> <?php
 //<%= link_to highlight_tokens(truncate(e.event_title, 255), @tokens), e.event_url  
 echo $text->highlight($html->link($candy->format_activity_title($e['title']), $e['url']),$question);
 ?></dt>
        <dd><span class="description"><?php
  //<%= highlight_tokens(e.event_description, @tokens)
  echo $text->highlight($e['description'],$question); ?></span>
        <span class="author"><?php echo $candy->format_date($e['datetime'], false) ?> <?php echo $candy->format_time($e['datetime'], false) ?></span></dd>
      <?php endforeach; ?>
    </dl>
<?php endif; ?>

<p><center>
<?php if(isset($pagination_previous_date)): ?>
<%= link_to_remote ('&#171; ' + l(:label_previous)),
                   {:update => :content,
                    :url => params.merge(:previous => 1, :offset => @pagination_previous_date.strftime("%Y%m%d%H%M%S"))
                   }, :href => url_for(params.merge(:previous => 1, :offset => @pagination_previous_date.strftime("%Y%m%d%H%M%S"))) %>&nbsp;
<?php endif; ?>
<?php if(isset($pagination_next_date)): ?>
<%= link_to_remote (l(:label_next) + ' &#187;'),
                   {:update => :content,
                    :url => params.merge(:previous => nil, :offset => @pagination_next_date.strftime("%Y%m%d%H%M%S"))
                   }, :href => url_for(params.merge(:previous => nil, :offset => @pagination_next_date.strftime("%Y%m%d%H%M%S"))) %>
<?php endif; ?>
</center></p>

