<?php if ( !empty($issues)): ?>
<!-- <% if issues && issues.any? %> -->
<!-- <% form_tag({}) do %> -->
	<table class="list issues">		
		<thead><tr>
		<th>#</th>
		<th><?php __('Tracker') ?></th>
		<th><?php __('Subject') ?></th>
		</tr></thead>
		<tbody>	
		<?php foreach($issues as $issue): ?>
		<tr id="issue-<%= issue.id %>" class="hascontextmenu <%= cycle('odd', 'even') %> <%= css_issue_classes(issue) %>">
			<td class="id">
<!-- 			    <%= check_box_tag("ids[]", issue.id, false, :style => 'display:none;') %>-->
<!-- 				<%= link_to issue.id, :controller => 'issues', :action => 'show', :id => issue %> -->
				<?php echo $html->link($issue['Issue']['id'],aa('controller','issues','action','show','id',$issue['Issue']['id'])); ?>
			</td>
			<td><?php echo h($issue['Project']['name']) ?> - <?php echo $issue['Tracker']['name'] ?><br />
                <?php echo $issue['Status']['name'] ?> - <?php echo $candy->format_time($issue['Issue']['updated_on']) ?></td>
			<td class="subject">
                <?php echo $html->link($issue['Issue']['subject'],aa('controller','issues','action','show','id',$issue['Issue']['id'])) ?>
            </td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
<!-- <% end %> -->
<?php else: ?>
	<p class="nodata"><%= l(:label_no_data) %></p>
<?php endif; ?>
