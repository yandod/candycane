<div class="contextual">
<%= link_to_if_authorized(l(:label_news_new),
                          {:controller => 'news', :action => 'new', :project_id => @project},
                          :class => 'icon icon-add',
                          :onclick => 'Element.show("add-news"); return false;') if @project %>
</div>

<div id="add-news" style="display:none;">
<h2><%=l(:label_news_new)%></h2>
<% labelled_tabular_form_for :news, @news, :url => { :controller => 'news', :action => 'new', :project_id => @project },
                                           :html => { :id => 'news-form' } do |f| %>
<%= render :partial => 'news/form', :locals => { :f => f } %>
<%= submit_tag l(:button_create) %>
<%= link_to_remote l(:label_preview), 
                   { :url => { :controller => 'news', :action => 'preview', :project_id => @project },
                     :method => 'post',
                     :update => 'preview',
                     :with => "Form.serialize('news-form')"
                   }, :accesskey => accesskey(:preview) %> |
<%= link_to l(:button_cancel), "#", :onclick => 'Element.hide("add-news")' %>
<% end if @project %>
<div id="preview" class="wiki"></div>
</div>

<h2><?php __('News'); ?></h2>

<?php if ( !isset($newss) || !count($newss) ) : ?>
<p class="nodata"><?php __('No data to display'); ?></p>
<?php else: ?>
<?php foreach( $newss as $news ) : ?>
<!--    <h3><%= link_to(h(news.project.name), :controller => 'projects', :action => 'show', :id => news.project) + ': ' unless news.project == @project %>
    <%= link_to h(news.title), :controller => 'news', :action => 'show', :id => news %>
    <%= "(#{news.comments_count} #{lwr(:label_comment, news.comments_count).downcase})" if news.comments_count > 0 %></h3> -->
    <h3><?php echo $html->link( h($news['Project']['name']), array( 'controller' => 'projects', 'action' => 'show', 'id' => $news['Project']['id'])) . ': '; ?>
    <?php echo $html->link( h($news['News']['title']), array( 'controller' => 'news', 'action' => 'show', 'id' => $news['News']['id'] ) ) ; ?>
    <?php if ( $news['News']['comments_count'] > 0 ) : echo "(".$news['News']['comments_count'] . ' ' . __('Comments',true) . ')' ; endif; ?></h3>

    <p class="author"><?php echo $candy->authoring( $news['News']['created_on'], $news['Author'] ) ; ?></p>
    <div class="wiki">
    <?php echo $candy->textilizable($news['News']['description']); ?>
    </div>
<?php endforeach; ?>
<?php endif; ?>
<p class="pagination"><?php echo $paginator->prev('<< '.__('Previous', true), array(), null, array('style'=>'display:none;'));?><?php echo $paginator->numbers();?><?php echo ' ' . $paginator->next(__('Next', true).' >>', array(), null, array('style'=>'display:none;'));?>
</p>

<p class="other-formats">
<?php __("'Also available in:'") ; ?>
<span><%= link_to 'Atom', {:format => 'atom', :key => User.current.rss_key}, :class => 'feed' %></span>
</p>

<% content_for :header_tags do %>
  <%= auto_discovery_link_tag(:atom, params.merge({:format => 'atom', :page => nil, :key => User.current.rss_key})) %>
<% end %>

<?php $candy->html_title(__('News', true)) ; ?>
