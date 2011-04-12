<div class="contextual">
<?php if (isset($main_project)) echo $candy->link_to_if_authorized(aa('controller','news', 'action','new'), __('Add news', true), array('controller' => 'news', 'action' => 'add', 'project_id' => $main_project['Project']['identifier']), array('onclick' => 'Element.show("add-news"); return false;', 'class' => 'icon icon-add', 'accesskey' => 'accesskey(:edit)')); ?>
</div>

<div id="add-news" style="display:none;">
<h2><? __('Add news') ; ?></h2>
<?php echo $form->create('News', array('url' => "/projects/{$main_project['Project']['identifier']}/news/add")) ; ?>
<!-- <%= render :partial => 'news/form', :locals => { :f => f } %> -->
<?php echo $this->renderElement('news/_form') ; ?>
<?php echo $form->submit( __('Create',true), aa('div', false) ) ; ?>
<!-- <%= link_to_remote l(:label_preview), 
                   { :url => { :controller => 'news', :action => 'preview', :project_id => @project },
                     :method => 'post',
                     :update => 'preview',
                     :with => "Form.serialize('news-form')"
                   }, :accesskey => accesskey(:preview) %> | -->
<?php echo __('Preview',true); ?> |
<?php echo $html->link( __('Cancel',true), "#", aa('onclick', 'Element.hide("add-news")') );  ?>
<?php echo $form->end(); ?>
<div id="preview" class="wiki"></div>
</div>


<h2><?php __('News'); ?></h2>

<?php if ( !isset($newss) || !count($newss) ) : ?>
<p class="nodata"><?php __('No data to display'); ?></p>
<?php else: ?>
<?php foreach( $newss as $news ) : ?>
    <h3>
    <?php 
      if ( isset($main_project) &&$news['Project']['id'] != $main_project['Project']['id'] ) {
        echo $html->link( h($news['Project']['name']), array( 'controller' => 'projects', 'action' => 'show', 'id' => $news['Project']['id'])) . ': '; 
      }
    ?>
    <?php echo $html->link( h($news['News']['title']), array( 'controller' => 'news', 'action' => 'show', 'id' => $news['News']['id'], 'project_id' => $news['Project']['id']) ) ; ?>
    <?php 
      if ( $news['News']['comments_count'] > 0 ) {
         echo "(".$news['News']['comments_count'] . ' ' . __('Comments',true) . ')' ; 
      }
    ?>
    </h3>

    <p class="author"><?php echo $candy->authoring( $news['News']['created_on'], $news['Author'] ) ; ?></p>
    <div class="wiki">
    <?php echo $candy->textilizable($news['News']['description']); ?>
    </div>
<?php endforeach; ?>
<?php endif; ?>
<p class="pagination"><?php echo $paginator->prev('<< '.__('Previous', true), array(), null, array('style'=>'display:none;'));?><?php echo $paginator->numbers();?><?php echo ' ' . $paginator->next(__('Next', true).' >>', array(), null, array('style'=>'display:none;'));?>
</p>

<p class="other-formats">
<?php __("Also available in:") ; ?>
<span>
<?php if (isset($main_project)) echo $html->link('Atom', array('controller' => 'news', 'action' => 'index', 'project_id' => $main_project['Project']['identifier']/*, 'format' => 'atom', 'key' => '' User.current.rss_key*/), array('class' => 'feed')) ?>
</span>
</p>

<!--  
<% content_for :header_tags do %>
<%= auto_discovery_link_tag(:atom, params.merge({:format => 'atom', :page => nil, :key => User.current.rss_key})) %>
<% end %>
-->
<?php $candy->html_title(__('News', true)) ; ?>
