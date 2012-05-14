<?php echo $form->create() ?>
<table class="list issues">
    <thead><tr>
        <th><?php echo $html->link($html->image('toggle_check.png'), array(), array('onclick' => 'toggleIssuesSelection(Element.up(this, "form")); return false;', 'title' => __('Check all', true) . '/' . __('Uncheck all', true)), null, false) ?>
        
        <!--<%= link_to image_tag('toggle_check.png'), {}, :onclick => 'toggleIssuesSelection(Element.up(this, "form")); return false;',
                                                           :title => "#{l(:button_check_all)}/#{l(:button_uncheck_all)}" %>
        --></th>
        <?php
 					// var_dump($paginator->sortKey());
					$sort_mark = '';
					if ($paginator->sortKey() == 'id' || $paginator->sortKey() == 'Issue.id') {
						$sort_mark = '&nbsp;'.$html->image('sort_'.$paginator->sortDir().'.png', array('alt' => "Sort_desc"));
					}
					echo $html->tag('th', $paginator->sort('#', 'Issue.id', array('url' => $paginator->params['url_param'])).$sort_mark);
					foreach ($queries->columns($query) as $column):
						$sort_mark = '';
						if ($paginator->sortKey() == $queryColumn->sortable($column)) {
							$sort_mark = '&nbsp;'.$html->image('sort_'.$paginator->sortDir().'.png', array('alt' => "Sort_desc"));
						}
            echo $html->tag('th', strlen($queryColumn->sortable($column)) ?
              $paginator->sort(__($column, true), $queryColumn->sortable($column), array(
                'direction' => $queryColumn->default_order($column),
                'update' => 'content',
                'url' => $paginator->params['url_param']
              )).$sort_mark : h(__($column, true))
            );
        	endforeach;
				?>
        <!--
		<%= sort_header_tag('id', :caption => '#', :default_order => 'desc') %>
        <% query.columns.each do |column| %>
          <%= column_header(column) %>
        <% end %>
        -->
	</tr></thead>
	<tbody>
  <?php foreach ($issue_list as $issue): ?>
	<tr id="issue-<?php echo h($issue['Issue']['id']) ?>" class="hascontextmenu <?php echo $candy->cycle('odd', 'even') ?> <?php echo $issues->css_issue_classes($issue) ?>">
	    <td class="checkbox"><input type="checkbox" name="ids[]" value="<?php echo h($issue['Issue']['id']) ?>" /></td>
		<td><?php echo $html->link($issue['Issue']['id'], array('controller' => 'issues', 'action' => 'show', 'id' => $issue['Issue']['id'])) ?></td>
        <?php foreach ($queries->columns($query) as $column): ?><?php echo $html->tag('td', $queries->column_content($column, $issue), array('class' => $column)) ?><?php endforeach ?>
  </tr>
	<?php endforeach ?>
	</tbody>
</table>
<?php echo $form->end() ?>
