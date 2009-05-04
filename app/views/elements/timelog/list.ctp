<table class="list time-entries">
<thead>
<tr>
  <th><?php echo $paginator->sort(__('Date',true), 'spent_on'); ?></th>
  <th><?php echo $paginator->sort(__('Member',true), 'user_id'); ?></th>
  <th><?php echo $paginator->sort(__('Activity',true), 'activity_id'); ?></th>
  <th><?php echo $paginator->sort(__('Project',true), 'project_id'); ?></th>
  <th><?php echo $paginator->sort(__('Issue',true), 'issue_id'); ?></th>
  <th><?php __('Comment') ?></th>
  <th><?php echo $paginator->sort(__('Hours',true), 'hours'); ?></th>
  <?php /* sort_header_tag('hours', :caption => l(:field_hours))  */ ?>
  <th></th>
</tr>
</thead>
<tbody>
<?php foreach($entries as $entry) : ?>
  <tr class="time-entry <?php echo $candy->cycle(); ?>">
    <td class="spent_on"><?php echo $candy->format_date($entry['TimeEntry']['spent_on']); ?></td>
    <td class="user"><?php echo h($candy->format_username($entry['User'])); ?></td>
    <td class="activity"><?php echo h($entry['Activity']['name']); ?></td>
    <td class="project"><?php echo h($entry['Project']['name']); ?></td>
    <td class="subject">
    <?php 
      if(!empty($entry['Issue']['tracker_id'])) {
        $entry['Tracker'] = array('name'=>$trackers[$entry['Issue']['tracker_id']]);
        echo $candy->link_to_issue($entry).': '.h($candy->truncate($entry['Issue']['subject'], 50));
      }
    ?>
    </td>
    <td class="comments"><?php echo h($entry['TimeEntry']['comments']); ?></td>
    <td class="hours"><?php echo $candy->html_hours(sprintf("%.2f",$entry['TimeEntry']['hours'])); ?></td>
    <td align="center">
    <?php if($candy->authorize_for('edit_own_time_entries', $entry)): ?>
        <?php echo $html->link($html->image('edit.png'),   array('controller' => 'timelog', 'action' => 'edit',    'id' => $entry['TimeEntry']['id']), array('title' => __('Edit',true)), false, false); ?>
        <?php echo $html->link($html->image('delete.png'), array('controller' => 'timelog', 'action' => 'destroy', 'id' => $entry['TimeEntry']['id']), array('title' => __('Delete',true), 'method' => 'post'), __('Are you sure ?',true), false); ?>
    <?php endif; ?>
    </td>
  </tr>
<?php endforeach; ?>
</tbody>
</table>
