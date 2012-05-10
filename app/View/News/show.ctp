<div class="contextual">
<?php echo $this->Candy->link_to_if_authorized(array('controller' => 'news','action' => 'edit'), __('Edit'), '#', array('class' => 'icon icon-edit', 'onclick' => 'Element.show("edit-news"); return false;')) ?>
<?php echo $this->Candy->link_to_if_authorized(array('controller' => 'news','action' => 'destroy'),  __('Delete'), array( 
	'controller' => 'news', 
	'action' => 'destroy',
	'project_id' => $news['Project']['identifier'], 
	'id' => $news['News']['id']), array('class' => 'icon icon-del', 'onclick' => "return (confirm('" . __('Are you sure ?') . "'));")); ?>
</div>

<h2><?php echo $news['News']['title'] ?></h2>

<div id="edit-news" style="display:none;">
<?php echo $this->Form->create('News', array(
	'url' =>array(
		'controller' => 'news', 
		'action' => 'edit', 
		//'news_id', $news['News']['id'], 
		'project_id' => $this->request->params['project_id']
		)
	)
);?>
<?php echo $this->element('news/_form', array('news' => $news)) ; ?>
<?php echo $this->Form->submit( __('Save'), array('div' => false) ) ; ?>
<!-- <%= link_to_remote l(:label_preview), 
                   { :url => { :controller => 'news', :action => 'preview', :project_id => @project },
                     :method => 'post',
                     :update => 'preview',
                     :with => "Form.serialize('news-form')"
                   }, :accesskey => accesskey(:preview) %> | -->
<?php 	echo $this->Js->link(
		__('Preview'),
		array(
			'action' => 'preview',
			'project_id' => $this->request->params['project_id']
		),
		array(
			'update' => 'preview',
			'buffer' => false,
			'complete' => "Element.scrollTo('preview')",
			'data' => "Form.serialize('NewsShowForm')",
			'dataExpression' => true
		)
	);
?> |
<?php echo $this->Html->link( __('Cancel'), "#", array('onclick' => 'Element.hide("edit-news")') );  ?>
<?php echo $this->Form->end(); ?>
<div id="preview" class="wiki"></div>
</div>

<p><em><?php if ( $news['News']['summary'] ) : ?><?php echo h( $news['News']['summary']) ; ?><br /><?php endif; ?>
<span class="author"><?php echo $this->Candy->authoring($news['News']['created_on'], $news['Author']) ; ?></span></em></p>
<div class="wiki">
<?php echo $this->Candy->textilizable($news['News']['description']) ; ?>
</div>
<br />

<div id="comments" style="margin-bottom:16px;">
<h3 class="icon22 icon22-comment"><?php echo __('Comments') ?></h3>
<?php foreach( $news['Comments'] as $comment ) : ?>
<!--    <% next if comment.new_record? %> -->
    <?php if ( array_key_exists( 'new_record', $comment ) ) : continue ; endif; ?>
    <div class="contextual">
<!--    <%= link_to_if_authorized image_tag('delete.png'), {:controller => 'news', :action => 'destroy_comment', :id => @news, :comment_id => comment},
                                                       :confirm => l(:text_are_you_sure), :method => :post, :title => l(:button_delete) %> -->
    <?php $this->Candy->link_to_if_authorized(null, $this->Html->image('delete.png'), array( 'controller' => 'news', 'action' => 'destroy_comment', 'id' => $news['News']['id'], 'comment_id' => $comment['id']),
                                                       array( 'confirm' => __('Are you sure ?'), 'method' => 'post', 'title' => __('Delete')), false) ; ?>

    </div>
    <h4><?php echo $this->Candy->authoring($comment['created_on'], $comment['Author']) ; ?></h4>
    <?php echo $this->Candy->textilizable($comment['comments']) ; ?>
<?php endforeach; ?>
</div>

<?php if($this->Candy->authorize_for(array('controller'=>'news', 'action'=>'add_comment'))): ?>
<!-- <p><%= toggle_link l(:label_comment_add), "add_comment_form", :focus => "comment_comments" %></p>
<% form_tag({:action => 'add_comment', :id => @news}, :id => "add_comment_form", :style => "display:none;") do %>
<%= text_area 'comment', 'comments', :cols => 80, :rows => 15, :class => 'wiki-edit' %> -->
<p><?php echo $this->Html->link( __('Add a comment'), '#', array('onclick' => "Element.toggle('add_comment_form'); Form.Element.focus('comment_comments'); return false;")) ?></p>
<?php echo $this->Form->create('News', array('url' => array(
	 'action' => 'add_comment',
	 'project_id' => $news['Project']['identifier']

), 'id' => 'add_comment_form', 'style' => 'display:none;')) ; ?>
<?php echo $this->Form->textarea( 'comments', array('id' => 'comment_comments', 'cols' => 80, 'rows' => 15, 'class' => 'wiki-edit' )) ; ?>
<?php echo $this->Form->submit( __('Add'), array('div' => false) ) ; ?>
<?php echo $this->Form->end(); ?>
<!--<%= wikitoolbar_for 'comment_comments' %>-->
<?php endif; ?>
<!--<% end %>-->

<?php $this->Candy->html_title( $news['News']['title'] ) ; ?>

<!--
<% content_for :header_tags do %>
  <%= stylesheet_link_tag 'scm' %>
<% end %>
-->
