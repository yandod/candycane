<?php if(empty($query['Query']['id'])): ?>
    <h2><?php  $this->Candy->html_title(__('Issues')) ?></h2>
    
    <form action="<?php echo h($this->Html->url(array('controller' => 'queries', 'action' => 'add'))) ?>" method="get" id="query_form">
    <input type="hidden" name="set_filter" value="1" />
    <?php if (isset($main_project)): ?><input type="hidden" name="project_id" value="<?php echo h($main_project['Project']['identifier']) ?>" /><?php endif ?>
    <fieldset id="filters"><legend><?php echo __('Filters') ?></legend>
    <?php echo $this->element('queries/filters', array('show_filters' => $show_filters, 'available_filters' => $available_filters)) ?>
    <p class="buttons">
      <?php echo $this->Js->link(__('Apply'), 
      		array('controller' => 'issues', 'action' => 'index', 'project_id' => isset($main_project) ? $main_project['Project']['identifier_or_id'] : null),
		array('update' => 'content', 'buffer' => false, 'evalScripts' => true, 'data' => "Form.serialize('query_form')", 'dataExpression' => true, 'class' => 'icon icon-checked', 'method' => 'GET')) ?>
      <?php echo $this->Js->link(__('Clear'), 
		array('controller' => 'issues', 'action' => 'index', 'project_id' => isset($main_project) ? $main_project['Project']['identifier_or_id'] : null),
		array('update' => 'content', 'buffer' => false, 'evalScripts' => true, 'class' => 'icon icon-reload', 'method' => 'GET')) ?>
    <!--
    <% if User.current.allowed_to?(:save_queries, @project, :global => true) %>
    -->
    <?php echo $this->Html->link(__('Save'), '', array('onclick' => "$('query_form').submit(); return false;", 'class' => 'icon icon-save')) ?>
    <!--<%= link_to l(:button_save), {}, :onclick => "$('query_form').submit(); return false;", :class => 'icon icon-save' %>-->
    <!--
    <% end %>
    -->
    </p>
    </fieldset>
    <!--<% end %>-->
<?php else: ?>
    <div class="contextual">
    <?php if ($this->Queries->editable($query, $currentuser)): ?>
    <!--<% if @query.editable_by?(User.current) %>-->
    <?php echo $this->Html->link(__('Edit'), array('controller' => 'queries', 'action' => 'edit', $query['Query']['id']), array('class' => 'icon icon-edit')) ?>
    <?php echo $this->Html->link(__('Delete'), array('controller' => 'queries', 'action' => 'destroy', $query['Query']['id']), array('class' => 'icon icon-del'), __('Are you sure ?')) ?>
    <!--
    <%= link_to l(:button_edit), {:controller => 'queries', :action => 'edit', :id => @query}, :class => 'icon icon-edit' %>
    <%= link_to l(:button_delete), {:controller => 'queries', :action => 'destroy', :id => @query}, :confirm => l(:text_are_you_sure), :method => :post, :class => 'icon icon-del' %>
    -->
    <!--<% end %>-->
    <?php endif ?>
    </div>
    <h2><?php echo h(__($this->Queries->name($query))) ?></h2>
    <div id="query_form"></div>
    <?php $this->Candy->html_title(__($this->Queries->name($query))) ?>
<?php endif ?>
<!--<%= error_messages_for 'query' %>-->
<!--<% if @query.valid? %>-->
<?php if ( empty($issue_list) ): ?>
<p class="nodata"><?php echo h(__('No data to display')) ?></p>
<?php else: ?>
<?php echo $this->element('issues/list', array('issue_list' => $issue_list, 'query' => $query)) ?>
<p class="pagination"><?php echo $this->Candy->pagination_links_full() ?>
<!--<%= pagination_links_full @issue_pages, @issue_count %>--></p>

<p class="other-formats">
<?php /*
<%= l(:label_export_to) %>
<span><%= link_to 'Atom', {:query_id => @query, :format => 'atom', :key => User.current.rss_key}, :class => 'feed' %></span>
<span><%= link_to 'CSV', {:format => 'csv'}, :class => 'csv' %></span>
<span><%= link_to 'PDF', {:format => 'pdf'}, :class => 'pdf' %></span>
*/ ?>
</p>
<?php endif ?>
<!-- <% end %> -->

<?php $this->set('Sidebar', $this->element('issues/sidebar')) ?>

<!--
<% content_for :header_tags do %>
    <%= auto_discovery_link_tag(:atom, {:query_id => @query, :format => 'atom', :page => nil, :key => User.current.rss_key}, :title => l(:label_issue_plural)) %>
    <%= auto_discovery_link_tag(:atom, {:action => 'changes', :query_id => @query, :format => 'atom', :page => nil, :key => User.current.rss_key}, :title => l(:label_changes_details)) %>
-->

<?php
  $for_header_tags = $this->Html->script('context_menu') . $this->Html->css('context_menu');
  $this->viewVars['header_tags'] = isset($this->viewVars['header_tags']) ? $this->viewVars['header_tags'] . $for_header_tags : $for_header_tags;
?>
<div id="context-menu" style="display: none;"></div>
<script type="text/javascript">
//<![CDATA[
new ContextMenu('<?php echo $this->Html->url(array('controller' => 'issues', 'action' => 'context_menu')) ?>');
//-->
</script>
