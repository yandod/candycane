<!--
<% if @statuses.empty? or rows.empty? %>
    <p><i><%=l(:label_no_data)%></i></p>
<% else %>
-->
<?php if (empty($statuses) || empty($rows)): ?>
    <p><i><?php echo __('No data to display'); ?></i></p>
<?php else: ?>
<!--<% col_width = 70 / (@statuses.length+3) %>-->
<?php $col_width = 70 / (count($statuses) + 3); ?>
<table class="list">
<thead><tr>
<th style="width:25%"></th>
<!--
<% for status in @statuses %>
<th style="width:<%= col_width %>%"><%= status.name %></th>
<% end %>
-->
<?php foreach ($statuses as $status): ?>
<th style="width:<?php echo h($col_width); ?>%"><?php echo h($status['IssueStatus']['name']); ?></th>
<?php endforeach; ?>
<!--
<th align="center" style="width:<%= col_width %>%"><strong><%=l(:label_open_issues_plural)%></strong></th>
<th align="center" style="width:<%= col_width %>%"><strong><%=l(:label_closed_issues_plural)%></strong></th>
<th align="center" style="width:<%= col_width %>%"><strong><%=l(:label_total)%></strong></th>
-->
<th align="center" style="width:<?php echo h($col_width); ?>%"><strong><?php echo __('open'); ?></strong></th>
<th align="center" style="width:<?php echo h($col_width); ?>%"><strong><?php echo __('Closed'); ?></strong></th>
<th align="center" style="width:<?php echo h($col_width); ?>%"><strong><?php echo __('Total'); ?></strong></th>
</tr></thead>
<tbody>
<!--
<% for row in rows %>
<tr class="<%= cycle("odd", "even") %>">
  <td><%= link_to row.name, :controller => 'issues', :action => 'index', :project_id => ((row.is_a?(Project) ? row : @project)), 
                                                :set_filter => 1, 
                                                "#{field_name}" => row.id %></td>
  <% for status in @statuses %>
    <td align="center"><%= aggregate_link data, { field_name => row.id, "status_id" => status.id }, 
                                                :controller => 'issues', :action => 'index', :project_id => ((row.is_a?(Project) ? row : @project)), 
                                                :set_filter => 1, 
                                                "status_id" => status.id, 
                                                "#{field_name}" => row.id %></td>
  <% end %>
  <td align="center"><%= aggregate_link data, { field_name => row.id, "closed" => 0 },
                                                :controller => 'issues', :action => 'index', :project_id => ((row.is_a?(Project) ? row : @project)), 
                                                :set_filter => 1, 
                                                "#{field_name}" => row.id,
                                                "status_id" => "o" %></td>
  <td align="center"><%= aggregate_link data, { field_name => row.id, "closed" => 1 },
                                                :controller => 'issues', :action => 'index', :project_id => ((row.is_a?(Project) ? row : @project)), 
                                                :set_filter => 1, 
                                                "#{field_name}" => row.id,
                                                "status_id" => "c" %></td>
  <td align="center"><%= aggregate_link data, { field_name => row.id },
                                                :controller => 'issues', :action => 'index', :project_id => ((row.is_a?(Project) ? row : @project)), 
                                                :set_filter => 1, 
                                                "#{field_name}" => row.id,
                                                "status_id" => "*" %></td>  
</tr>
<% end %>
-->
<?php foreach ($rows as $k => $row): ?>
<tr class="<?php echo ($k % 2 ? "odd" : "even"); ?>">
  <td><?php echo $this->Html->link($row['name'], array('controller' => 'issues', 'action' => 'index', 'project_id' => $project['identifier'],
                                                 '?' => array('set_filter' => 1
                                                            , $field_name => $row['id']))); ?></td>
  <?php foreach ($statuses as $status): ?>
    <td align="center"><?php echo $this->Reports->aggregate_link($data, array($field_name => $row['id'], 'status_id' => $status['IssueStatus']['id'])
                                                         , array('controller' => 'issues', 'action' => 'index', 'project_id' => $project['identifier']
                                                                , '?' => array('set_filter' => 1
                                                                             , 'status_id' => $status['IssueStatus']['id']
                                                                             , $field_name => $row['id']))); ?></td>
  <?php endforeach; ?>
  <td align="center"><?php echo $this->Reports->aggregate_link($data, array($field_name => $row['id'], 'closed' => 0)
                                                       , array('controller' => 'issues', 'action' => 'index', 'project_id' => $project['identifier']
                                                              , '?' => array('set_filter' => 1
                                                                             , $field_name => $row['id']
                                                                             , 'status_id' => 'o'))); ?></td>
  <td align="center"><?php echo $this->Reports->aggregate_link($data, array($field_name => $row['id'], 'closed' => 1)
                                                       , array('controller' => 'issues', 'action' => 'index', 'project_id' => $project['identifier']
                                                              , '?' => array('set_filter' => 1
                                                                             , $field_name => $row['id']
                                                                             , 'status_id' => 'c'))); ?></td>
  <td align="center"><?php echo $this->Reports->aggregate_link($data, array($field_name => $row['id'])
                                                       , array('controller' => 'issues', 'action' => 'index', 'project_id' => $project['identifier']
                                                              , '?' => array('set_filter' => 1
                                                                             , $field_name => $row['id']
                                                                             , 'status_id' => '*'))); ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<!--
<% end
  reset_cycle %>
-->
<?php endif; ?>
