<div class="contextual">
<!--<%= link_to_if_authorized(l(:button_update), {:controller => 'issues', :action => 'edit', :id => @issue }, :onclick => 'showAndScrollTo("update", "notes"); return false;', :class => 'icon icon-edit', :accesskey => accesskey(:edit)) %>-->
<!--<%= link_to_if_authorized l(:button_log_time), {:controller => 'timelog', :action => 'edit', :issue_id => @issue}, :class => 'icon icon-time' %>-->
<!--<%= watcher_tag(@issue, User.current) %>-->
<!--<%= link_to_if_authorized l(:button_copy), {:controller => 'issues', :action => 'new', :project_id => @project, :copy_from => @issue }, :class => 'icon icon-copy' %>-->
<!--<%= link_to_if_authorized l(:button_move), {:controller => 'issues', :action => 'move', :id => @issue }, :class => 'icon icon-move' %>-->
<!--<%= link_to_if_authorized l(:button_delete), {:controller => 'issues', :action => 'destroy', :id => @issue}, :confirm => l(:text_are_you_sure), :method => :post, :class => 'icon icon-del' %>-->
</div>

<h2><?php echo h($issue['Tracker']['name']) ?> #<?php echo h($issue['Issue']['id']) ?></h2>

<div class="<?php echo h($issues->css_issue_classes($issue)) ?>">
        <?php echo $candy->avatar(array('User' => $issue['Author']), array('size' => 64)) ?>
        <h3><?php echo h($issue['Issue']['subject']) ?></h3>
        <p class="author">
        <?php echo $candy->authoring($issue['Issue']['created_on'], $issue) ?>.
        <?php if ($issue['Issue']['created_on'] !=  $issue['Issue']['updated_on']) echo $candy->lwr('Updated %s ago', $candy->distance_of_time_in_words(time(), $issue['Issue']['updated_on'])) ?>
        </p>

<table width="100%">
<tr>
    <td style="width:15%" class="status"><b><?php __('Status') ?>:</b></td><td style="width:35%" class="status"><?php echo h($issue['Status']['name']) ?></td>
    <td style="width:15%" class="start-date"><b><?php __('Start') ?>:</b></td><td style="width:35%"><?php echo h($candy->format_date($issue['Issue']['start_date'])) ?></td>
</tr>
<tr>
    <td class="priority"><b><?php __('Priority') ?>:</b></td><td class="priority"><?php echo h($issue['Priority']['name']) ?></td>
    <td class="due-date"><b><?php __('Due date') ?>:</b></td><td class="due-date"><?php echo h($candy->format_date($issue['Issue']['due_date'])) ?></td>
</tr>
<tr>
    <td class="assigned-to"><b><?php __('Assigned to') ?>:</b></td><td><?php echo $candy->avatar(array('User' => $issue['Author']), array('size' => 14)) ?><?php echo strlen($issue['Issue']['assigned_to_id']) ? 'link_to_user(@issue.assigned_to)' : "-" ?></td>
    <td class="progress"><b><?php __('done_ratio') ?>:</b></td><td class="progress"><!--<%= progress_bar @issue.done_ratio, :width => '80px', :legend => "#{@issue.done_ratio}%" %>--></td>
</tr>
<tr>
    <td class="category"><b><?php __('Category') ?>:</b></td><td><?php echo h(strlen($issue['Issue']['category_id']) ? $issue['Category']['name'] : "-") ?></td>
    <!--<% if User.current.allowed_to?(:view_time_entries, @project) %>-->
    <td class="spent-time"><b><?php __('Spent time') ?>:</b></td>
    <td class="spent-hours"><!--<%= @issue.spent_hours > 0 ? (link_to lwr(:label_f_hour, @issue.spent_hours), {:controller => 'timelog', :action => 'details', :project_id => @project, :issue_id => @issue}, :class => 'icon icon-time') : "-" %>--></td>
    <!--<% end %>-->
</tr>
<tr>
    <td class="fixed-version"><b><?php __('Target version') ?>:</b></td><td><?php echo h(strlen($issue['Issue']['fixed_version_id']) ? $issue['FixedVersion']['name'] : "-") ?></td>
    <!--<% if @issue.estimated_hours %>-->
    <td class="estimated-hours"><b><?php __('Estimated time') ?>:</b></td><td><?php echo $candy->lwr('%.2f hour', $issue['Issue']['estimated_hours']) ?></td>
    <!--<% end %>-->
</tr>
<tr>
<!--<% n = 0 -%>-->
<!--<% @issue.custom_values.each do |value| -%>-->
    <td valign="top"><b><!--<%=h value.custom_field.name %>-->:</b></td><td valign="top"><!--<%= simple_format(h(show_value(value))) %>--></td>
<!--<% n = n + 1
   if (n > 1) 
        n = 0 %>-->
        </tr><tr>
 <!--<%end
end %>-->
</tr>
<!--<%= call_hook(:view_issues_show_details_bottom, :issue => @issue) %>-->
</table>
<hr />

<div class="contextual">
<!--<%= link_to_remote_if_authorized(l(:button_quote), { :url => {:action => 'reply', :id => @issue} }, :class => 'icon icon-comment') unless @issue.description.blank? %>-->
</div>
                              
<p><strong><?php __('Description') ?></strong></p>
<div class="wiki">
<!--<%= textilizable @issue, :description, :attachments => @issue.attachments %>-->
</div>

<!--<%= link_to_attachments @issue %>-->

<!--<% if authorize_for('issue_relations', 'new') || @issue.relations.any? %>-->
<hr />
<div id="relations">
<!--<%= render :partial => 'relations' %>-->
</div>
<!--<% end %>-->

<!--<% if User.current.allowed_to?(:add_issue_watchers, @project) ||
        (@issue.watchers.any? && User.current.allowed_to?(:view_issue_watchers, @project)) %>-->
<hr />
<div id="watchers">
<!--<%= render :partial => 'watchers/watchers', :locals => {:watched => @issue} %>-->
</div>
<!--<% end %>-->

</div>

<!--<% if @issue.changesets.any? && User.current.allowed_to?(:view_changesets, @project) %>-->
<div id="issue-changesets">
<h3><?php __('Associated revisions') ?></h3>
<!--<%= render :partial => 'changesets', :locals => { :changesets => @issue.changesets} %>-->
</div>
<!--<% end %>-->

<!--<% if @journals.any? %>-->
<div id="history">
<h3><?php __('History') ?></h3>
<!--<%= render :partial => 'history', :locals => { :journals => @journals } %>-->
</div>
<!--<% end %>-->
<div style="clear: both;"></div>

<!--<% if authorize_for('issues', 'edit') %>-->
  <div id="update" style="display:none;">
  <h3><!--<%= l(:button_update) %>--></h3>
  <!--<%= render :partial => 'edit' %>-->
  </div>
<!--<% end %>-->

<p class="other-formats">
<?php __("'Also available in:'") ?>
<span><!--<%= link_to 'Atom', {:format => 'atom', :key => User.current.rss_key}, :class => 'feed' %>--></span>
<span><!--<%= link_to 'PDF', {:format => 'pdf'}, :class => 'pdf' %>--></span>
</p>

    <?php $candy->html_title($issue['Tracker']['name'] . ' #' . $issue['Issue']['id'], ' ' . $issue['Issue']['subject']) ?>

<?php $this->set('Sidebar', $this->renderElement('issues/sidebar')) ?>

<!--<% content_for :header_tags do %>-->
    <!--<%= auto_discovery_link_tag(:atom, {:format => 'atom', :key => User.current.rss_key}, :title => "#{@issue.project} - #{@issue.tracker} ##{@issue.id}: #{@issue.subject}") %>-->
    <!--<%= stylesheet_link_tag 'scm' %>-->
<!--<% end %>-->
