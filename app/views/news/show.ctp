<div class="contextual">
<?php echo $candy->link_to_if_authorized(aa('controller','news','action','edit'), __('Edit',true), '#', aa('class', 'icon icon-edit', 'onclick', 'Element.show("edit-news"); return false;')) ?>
<?php echo $candy->link_to_if_authorized(aa('controller','news','action','destroy'),  __('Delete',true), array( 
	'controller' => 'news', 
	'action' => 'destroy',
	'project_id' => $news['Project']['identifier'], 
	'id' => $news['News']['id']), aa('class', 'icon icon-del', 'onclick', "return (confirm('" . __('Are you sure ?',true) . "'));")); ?>
</div>

<h2><?php echo $news['News']['title'] ?></h2>

<div id="edit-news" style="display:none;">
<?php echo $form->create('News', array(
	'url' =>aa(
		'controller', 'news', 
		'action', 'edit', 
		//'news_id', $news['News']['id'], 
		'project_id', $this->params['project_id']
		)
	)
);?>
<?php echo $this->renderElement('news/_form', array('news' => $news)) ; ?>
<?php echo $form->submit( __('Save',true), aa('div', false) ) ; ?>
<!-- <%= link_to_remote l(:label_preview), 
                   { :url => { :controller => 'news', :action => 'preview', :project_id => @project },
                     :method => 'post',
                     :update => 'preview',
                     :with => "Form.serialize('news-form')"
                   }, :accesskey => accesskey(:preview) %> | -->
<?php echo __('Preview',true); ?> |
<?php echo $html->link( __('Cancel',true), "#", aa('onclick', 'Element.hide("edit-news")') );  ?>
<?php echo $form->end(); ?>
<div id="preview" class="wiki"></div>
</div>

<p><em><?php if ( $news['News']['summary'] ) : ?><?php echo h( $news['News']['summary']) ; ?><br /><?php endif; ?>
<span class="author"><?php echo $candy->authoring($news['News']['created_on'], $news['Author']) ; ?></span></em></p>
<div class="wiki">
<?php echo $candy->textilizable($news['News']['description']) ; ?>
</div>
<br />

<div id="comments" style="margin-bottom:16px;">
<h3 class="icon22 icon22-comment"><?php __('Comments') ?></h3>
<?php foreach( $news['Comments'] as $comment ) : ?>
<!--    <% next if comment.new_record? %> -->
    <?php if ( array_key_exists( 'new_record', $comment ) ) : continue ; endif; ?>
    <div class="contextual">
<!--    <%= link_to_if_authorized image_tag('delete.png'), {:controller => 'news', :action => 'destroy_comment', :id => @news, :comment_id => comment},
                                                       :confirm => l(:text_are_you_sure), :method => :post, :title => l(:button_delete) %> -->
    <?php $candy->link_to_if_authorized(null, $html->image('delete.png'), array( 'controller' => 'news', 'action' => 'destroy_comment', 'id' => $news['News']['id'], 'comment_id' => $comment['id']),
                                                       array( 'confirm' => __('Are you sure ?',true), 'method' => 'post', 'title' => __('Delete',true)), false) ; ?>

    </div>
    <h4><?php echo $candy->authoring($comment['created_on'], $comment['Author']) ; ?></h4>
    <?php echo $candy->textilizable($comment['comments']) ; ?>
<?php endforeach; ?>
</div>

<?php if($candy->authorize_for(array('controller'=>'news', 'action'=>'add_comment'))): ?>
<!-- <p><%= toggle_link l(:label_comment_add), "add_comment_form", :focus => "comment_comments" %></p>
<% form_tag({:action => 'add_comment', :id => @news}, :id => "add_comment_form", :style => "display:none;") do %>
<%= text_area 'comment', 'comments', :cols => 80, :rows => 15, :class => 'wiki-edit' %> -->
<p><?php echo $html->link( __('Add a comment',true), '#', aa('onclick', "Element.toggle('add_comment_form'); Form.Element.focus('comment_comments'); return false;")) ?></p>
<?php echo $form->create('News', aa('url', array(
	 'action' => 'add_comment',
	 'project_id' => $news['Project']['identifier']

), 'id', 'add_comment_form', 'style', 'display:none;')) ; ?>
<?php echo $form->textarea( 'comments', aa('id', 'comment_comments', 'cols', 80, 'rows', 15, 'class', 'wiki-edit' )) ; ?>
<?php echo $form->submit( __('Add',true), aa('div', false) ) ; ?>
<?php echo $form->end(); ?>
<!--<%= wikitoolbar_for 'comment_comments' %>-->
<?php endif; ?>
<!--<% end %>-->

<?php $candy->html_title( $news['News']['title'] ) ; ?>

<!--
<% content_for :header_tags do %>
  <%= stylesheet_link_tag 'scm' %>
<% end %>
-->