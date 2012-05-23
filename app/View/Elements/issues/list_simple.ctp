<?php if ( !empty($issues)): ?>
<!-- <% if issues && issues.any? %> -->
<form method="post">
	<table class="list issues">		
		<thead><tr>
		<th>#</th>
		<th><?php echo __('Tracker') ?></th>
		<th><?php echo __('Subject') ?></th>
		</tr></thead>
		<tbody>	
		<?php foreach($issues as $issue): ?>
		<tr id="issue-<?php echo $issue['Issue']['id'] ?>" class="hascontextmenu <?php echo $this->Candy->cycle('odd','even')?> <%= css_issue_classes(issue) %>">
			<td class="id">
<!-- 			    <%= check_box_tag("ids[]", issue.id, false, :style => 'display:none;') %>-->
					<input type="checkbox" name="ids[]" value="<?php echo h($issue['Issue']['id']) ?>" style="display:none">
<!-- 				<%= link_to issue.id, :controller => 'issues', :action => 'show', :id => issue %> -->
				<?php echo $this->Html->link($issue['Issue']['id'],array(
					'controller' => 'issues',
					'action' => 'show',
					$issue['Issue']['id']
				)); ?>
			</td>
			<td><?php echo h($issue['Project']['name']) ?> - <?php echo $issue['Tracker']['name'] ?><br />
                <?php echo $issue['Status']['name'] ?> - <?php echo $this->Candy->format_time($issue['Issue']['updated_on']) ?></td>
			<td class="subject">
                <?php echo $this->Html->link($issue['Issue']['subject'],array(
					'controller' => 'issues',
					'action' => 'show',
					$issue['Issue']['id']
				)) ?>
            </td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</form>
<?php else: ?>
	<p class="nodata"><?php echo __('No data to display') ?></p>
<?php endif; ?>
