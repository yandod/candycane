<div class="contextual">
<?php if (isset($main_project)) echo $this->Candy->link_to_if_authorized(array('controller' => 'news', 'action' => 'new'), __('Add news'), array('controller' => 'news', 'action' => 'add', 'project_id' => $main_project['Project']['identifier']), array('onclick' => 'Element.show("add-news"); return false;', 'class' => 'icon icon-add', 'accesskey' => 'accesskey(:edit)')); ?>
</div>

<div id="add-news" style="display:none;">
<h2><?php echo __('Add news') ; ?></h2>
<?php
echo $this->Form->create('News', array('url' => "/projects/{$main_project['Project']['identifier']}/news/add", 'id' => 'NewsAddForm'));
	echo $this->element('news/_form');
	echo $this->Form->submit(__('Create'), array('div' => false));
	echo $this->Js->link(
		__('Preview'),
		array(
			'action' => 'preview',
			'project_id' => $this->request->params['project_id']
		),
		array(
			'update' => 'preview',
			'buffer' => false,
			'complete' => "Element.scrollTo('preview')",
			'data' => "Form.serialize('NewsAddForm')",
			'dataExpression' => true
		)
	);

?>
<!-- <%= link_to_remote l(:label_preview), 
                   { :url => { :controller => 'news', :action => 'preview', :project_id => @project },
                     :method => 'post',
                     :update => 'preview',
                     :with => "Form.serialize('news-form')"
                   }, :accesskey => accesskey(:preview) %> | -->
<?php //echo __('Preview'); ?> |
<?php echo $this->Html->link(__('Cancel'), "#", array('onclick' => 'Element.hide("add-news")')); ?>
<?php echo $this->Form->end(); ?>
<div id="preview" class="wiki"></div>
</div>


<h2><?php echo __('News'); ?></h2>

<?php if ( !isset($newss) || !count($newss) ) : ?>
<p class="nodata"><?php echo __('No data to display'); ?></p>
<?php else: ?>
<?php foreach( $newss as $news ) : ?>
    <h3>
    <?php 
      if ( isset($main_project) &&$news['Project']['id'] != $main_project['Project']['id'] ) {
        echo $this->Html->link( h($news['Project']['name']), array( 'controller' => 'projects', 'action' => 'show', 'project_id' => $news['Project']['identifier'])) . ': '; 
      }
    ?>
    <?php echo $this->Html->link( h($news['News']['title']), array( 'controller' => 'news', 'action' => 'show', 'id' => $news['News']['id'], 'project_id' => $news['Project']['identifier']) ) ; ?>
    <?php 
      if ( $news['News']['comments_count'] > 0 ) {
         echo "(".$news['News']['comments_count'] . ' ' . __('Comments') . ')' ; 
      }
    ?>
    </h3>

    <p class="author"><?php echo $this->Candy->authoring( $news['News']['created_on'], $news['Author'] ) ; ?></p>
    <div class="wiki">
    <?php echo $this->Candy->textilizable($news['News']['description']); ?>
    </div>
<?php endforeach; ?>
<?php endif; ?>
<p class="pagination"><?php echo $this->Paginator->prev('<< '.__('Previous'), array(), null, array('style'=>'display:none;'));?><?php echo $this->Paginator->numbers();?><?php echo ' ' . $this->Paginator->next(__('Next').' >>', array(), null, array('style'=>'display:none;'));?>
</p>

<p class="other-formats">
<?php echo __("Also available in:") ; ?>
<span>
<?php if (isset($main_project)) echo $this->Html->link('Atom', array('controller' => 'news', 'action' => 'index', 'project_id' => $main_project['Project']['identifier']/*, 'format' => 'atom', 'key' => '' User.current.rss_key*/), array('class' => 'feed')) ?>
</span>
</p>

<!--  
<% content_for :header_tags do %>
<%= auto_discovery_link_tag(:atom, params.merge({:format => 'atom', :page => nil, :key => User.current.rss_key})) %>
<% end %>
-->
<?php $this->Candy->html_title(__('News')) ; ?>
