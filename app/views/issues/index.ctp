<?php if (true): ?>
<!--<% if @query.new_record? %>-->
    <h2><?php __('Issues') ?></h2>
    <?php  $candy->html_title(__('Issues', true)) ?>
    
    <form action="<?php echo h($html->url(array('controller' => 'queries', 'action' => 'add'))) ?>" method="get" id="query_form">
    <!--<% form_tag({ :controller => 'queries', :action => 'new' }, :id => 'query_form') do %>-->
    <?php if (isset($project)): ?><input type="hidden" name="project_id" value="<?php echo h($project['Project']['id']) ?>" /><?php endif ?>
    <!--<%= hidden_field_tag('project_id', @project.id) if @project %>-->
    <fieldset id="filters"><legend><?php __('Filters') ?></legend>
    <?php echo $this->renderElement('queries/filters', array('query' => $query)) ?>
    <!--<%= render :partial => 'queries/filters', :locals => {:query => @query} %>-->
    <p class="buttons">
      <?php echo $ajax->link(__('Apply', true), array('controller' => 'issues', 'action' => 'index', 'project_id' => $project['Project']['identifier'], '?set_filter=1'), array('update' => 'content', 'with' => "Form.serialize('query_form')", 'class' => 'icon icon-checked')) ?>
      <?php echo $ajax->link(__('Clear', true), array('controller' => 'issues', 'action' => 'index', 'project_id' => $project['Project']['identifier'], '?set_filter=1'), array('update' => 'content', 'class' => 'icon icon-reload')) ?>
    <!--
    <%= link_to_remote l(:button_apply), 
                       { :url => { :set_filter => 1 },
                         :update => "content",
                         :with => "Form.serialize('query_form')"
                       }, :class => 'icon icon-checked' %>
                       
    <%= link_to_remote l(:button_clear),
                       { :url => { :set_filter => 1 }, 
                         :update => "content",
                       }, :class => 'icon icon-reload'  %>
                       
    <% if User.current.allowed_to?(:save_queries, @project, :global => true) %>
    <%= link_to l(:button_save), {}, :onclick => "$('query_form').submit(); return false;", :class => 'icon icon-save' %>
    <% end %>
    -->
    </p>
    </fieldset>
    <!--<% end %>-->
<?php else: ?>
    <div class="contextual">
    <?php if ($queries->editable($query, $currentuser)): ?>
    <!--<% if @query.editable_by?(User.current) %>-->
    <?php echo $html->link(__('Edit', true), array('controller' => 'queries', 'action' => 'edit', 'id' => $query['Query']['id']), array('class' => 'icon icon-edit')) ?>
    <?php echo $html->link(__('Delete', true), array('controller' => 'queries', 'action' => 'destroy', 'id' => $query['Query']['id'], 'confirm' => __('Are you sure ?', true)), array('class' => 'icon icon-del')) ?>
    <!--
    <%= link_to l(:button_edit), {:controller => 'queries', :action => 'edit', :id => @query}, :class => 'icon icon-edit' %>
    <%= link_to l(:button_delete), {:controller => 'queries', :action => 'destroy', :id => @query}, :confirm => l(:text_are_you_sure), :method => :post, :class => 'icon icon-del' %>
    -->
    <!--<% end %>-->
    <?php endif ?>
    </div>
    <h2><?php echo h(__($queries->name($query), true)) ?></h2>
    <div id="query_form"></div>
    <?php $candy->html_title(__($queries->name($query), true)) ?>
<?php endif ?>
<!--<%= error_messages_for 'query' %>-->
<!--<% if @query.valid? %>-->
<?php if (!$issues): ?>
<p class="nodata"><?php echo h(__('No data to display')) ?></p>
<?php else: ?>
<?php echo $this->renderElement('issues/list', array('issue_list' => $issue_list, 'query' => $query)) ?>
<p class="pagination"><%= pagination_links_full @issue_pages, @issue_count %></p>

<p class="other-formats">
<%= l(:label_export_to) %>
<span><%= link_to 'Atom', {:query_id => @query, :format => 'atom', :key => User.current.rss_key}, :class => 'feed' %></span>
<span><%= link_to 'CSV', {:format => 'csv'}, :class => 'csv' %></span>
<span><%= link_to 'PDF', {:format => 'pdf'}, :class => 'pdf' %></span>
</p>
<?php endif ?>
<!--<% end %>-->

<?php $this->set('Sidebar', $this->renderElement('issues/sidebar')) ?>

<!--
<% content_for :header_tags do %>
    <%= auto_discovery_link_tag(:atom, {:query_id => @query, :format => 'atom', :page => nil, :key => User.current.rss_key}, :title => l(:label_issue_plural)) %>
    <%= auto_discovery_link_tag(:atom, {:action => 'changes', :query_id => @query, :format => 'atom', :page => nil, :key => User.current.rss_key}, :title => l(:label_changes_details)) %>
    <%= javascript_include_tag 'context_menu' %>
    <%= stylesheet_link_tag 'context_menu' %>
<% end %>
-->
<div id="context-menu" style="display: none;"></div>
<!--
<%= javascript_tag "new ContextMenu('#{url_for(:controller => 'issues', :action => 'context_menu')}')" %>
-->
