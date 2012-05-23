<h2><?php echo $this->Candy->html_title(__('Search')) ?></h2>

<div class="box">
<?php echo $this->Form->create(false,array('type'=>'get','action'=>'index'))?>
<p><?php echo $this->Form->input('q',array(
  'size' => 60,
  'id' => 'search-input',
  'label' => false,
  'div' => false,
  'name' => 'q',
  'value' => $question
))?>
<?php echo $this->Html->scriptBlock("Field.focus('search-input')")?>
<?php echo $this->Search->project_select_tag(
	$scope,
	$currentuser,
	isset($main_project) ? $main_project : null
  ) ?>
<label for="all_words"><?php echo $this->Form->checkbox('all_words',array(
  'name' => 'all_words',
  'value' => '1',
  'checked' => isset($this->request->query['all_words']) && $this->request->query['all_words']
))?> <?php echo __('All words')?></label>
<label for="titles_only"><?php echo $this->Form->checkbox('titles_only',array(
  'name' => 'titles_only',
  'value' => '1',
  'checked' => isset($this->request->query['titles_only']) && $this->request->query['titles_only']
))?> <?php echo __('Search titles only')?></label>
</p>
<p>
<?php foreach($object_types as $t): ?>
<label for="<?php echo $t ?>"><?php
  $checked = in_array($t,$scope_types) ? 'checked' : '';
  echo "<input type='checkbox' name='{$t}' value='1' id='{$t}' {$checked}>";
?> <?php echo $this->Search->type_label($t)?></label>
<?php endforeach; ?>
</p>
<p><?php echo $this->Form->submit(__('Submit'),array('name' => 'submit'))?></p>
<?php echo $this->Form->end();?>
</div>

<?php if (isset($results) && is_array($results) && count($results)): ?>
    <div id="search-results-counts">
	<?php if(count($scope_types) > 1) {
		echo $this->Search->render_results_by_type($results_by_type,$this->request);
	}?>
    </div>
    
    <h3><?php echo __('Results') ?> (<?php echo array_sum(array_map('count',$results_by_type))?>)</h3>
    <dl id="search-results">
      <?php foreach ($results as $e): ?>
        <dt class="<?php echo $e['type']?>"><?php if(empty($main_project) || (!empty($main_project) && $main_project['Project']['id'] != $e['project']['id'])) {
		  echo $this->Html->tag('span', h($e['project']['name']), array('class' => 'project'));
		} ?> <?php
 //<%= link_to highlight_tokens(truncate(e.event_title, 255), @tokens), e.event_url  
echo $this->Html->link(
	$this->Text->highlight(
		h($this->Candy->format_activity_title($e['title'])),
		$question
	),
	$e['url'],
	array('escape' => false)
); ?></dt>
        <dd><span class="description"><?php
  //<%= highlight_tokens(e.event_description, @tokens)
  echo $this->Text->highlight($e['description'],$question); ?></span>
        <span class="author"><?php echo $this->Candy->format_date($e['datetime'], false) ?> <?php echo $this->Candy->format_time($e['datetime'], false) ?></span></dd>
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

