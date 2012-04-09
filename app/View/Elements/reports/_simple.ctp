<!--
<% if @statuses.empty? or rows.empty? %>
    <p><i><%=l(:label_no_data)%></i></p>
<% else %>
-->
<?php if (empty($statuses) || empty($rows)): ?>
<!--    <p><i><%=l(:label_no_data)%></i></p>-->
    <p><i><?php echo h(__(('No data to display'))); ?></i></p>
<?php else: ?>
<table class="list">
<thead><tr>
<th style="width:25%"></th>
<!--
<th align="center" style="width:25%"><%=l(:label_open_issues_plural)%></th>
<th align="center" style="width:25%"><%=l(:label_closed_issues_plural)%></th>
<th align="center" style="width:25%"><%=l(:label_total)%></th>
-->
<th align="center" style="width:25%"><?php echo h(__('open')); ?></th>
<th align="center" style="width:25%"><?php echo h(__('Closed')); ?></th>
<th align="center" style="width:25%"><?php echo h(__('Total')); ?></th>
</tr></thead>
<tbody>
<!--
<% for row in rows %>
<tr class="<%= cycle("odd", "even") %>">
  <td><%= link_to row.name, :controller => 'issues', :action => 'index', :project_id => ((row.is_a?(Project) ? row : @project)), 
                                                :set_filter => 1, 
                                                "#{field_name}" => row.id %></td>
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
<tr class="<?php echo ($k % 2 ? "even" : "odd"); ?>">
  <td><?php echo $this->Html->link($row['name'], array('controller' => 'issues', 'action' => 'index', 'project_id' => $project['identifier'],
                                                 '?' => array('set_filter' => 1
                                                              , $field_name => $row['id']))); ?></td>
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

