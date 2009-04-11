<% form_tag({}) do -%>	
<table class="list issues">
    <thead><tr>
        <th><!--<%= link_to image_tag('toggle_check.png'), {}, :onclick => 'toggleIssuesSelection(Element.up(this, "form")); return false;',
                                                           :title => "#{l(:button_check_all)}/#{l(:button_uncheck_all)}" %>
        --></th>
        <?php echo $html->tag('th', $paginator->sort('id', '#')) ?>
        <?php foreach ($queries->columns($query) as $column): ?><?php echo $html->tag('th', strlen($queryColumn->sortable($column)) ? $paginator->sort($queryColumn->name($column), $queryColumn->sortable($column), array('direction' => $queryColumn->default_order($column))) : h($queryColumn->name($column))) ?><?php endforeach ?>
        <!--
		<%= sort_header_tag('id', :caption => '#', :default_order => 'desc') %>
        <% query.columns.each do |column| %>
          <%= column_header(column) %>
        <% end %>
        -->
	</tr></thead>
	<tbody>
	<% issues.each do |issue| -%>
  <?php foreach ($issue_list as $issue): ?>
	<tr id="issue-<?php echo h($issue['Issue']['id']) ?>" class="hascontextmenu <%= cycle('odd', 'even') %> <?php echo $issues->css_issue_classes($issue) ?>">
	    <td class="checkbox"><input type="checkbox" name="ids[]" value="<?php echo h($issue['Issue']['id']) ?>" /></td>
		<td><?php echo $html->link($issue['Issue']['id'], array('controller' => 'issues', 'action' => 'show', 'id' => $issue['Issue']['id'])) ?></td>
        <?php foreach ($queries->columns($query) as $column): ?><?php echo $html->tag('td', $queries->column_content($column, $issue), array('class' => $column)) ?><?php endforeach ?>
  </tr>
	<?php endforeach ?>
	<% end -%>
	</tbody>
</table>
<% end -%>
