<table class="list time-entries">
<thead>
<tr>
  <?php echo $this->Sort->sort_header_tag('TimeEntry.spent_on', array('caption'=>__('Date'), 'url'=>$this->Timelog->url_options($main_project, $issue))); ?>
  <?php echo $this->Sort->sort_header_tag('TimeEntry.user_id', array('caption'=>__('Member'), 'url'=>$this->Timelog->url_options($main_project, $issue))); ?>
  <?php echo $this->Sort->sort_header_tag('TimeEntry.activity_id', array('caption'=>__('Activity'), 'url'=>$this->Timelog->url_options($main_project, $issue))); ?>
  <?php echo $this->Sort->sort_header_tag('Project.name', array('caption'=>__('Project'), 'url'=>$this->Timelog->url_options($main_project, $issue))); ?>
  <?php echo $this->Sort->sort_header_tag('TimeEntry.issue_id', array('caption'=>__('Issue'), 'url'=>$this->Timelog->url_options($main_project, $issue))); ?>
  <th><?php echo __('Comment') ?></th>
  <?php echo $this->Sort->sort_header_tag('TimeEntry.hours', array('caption'=>__('Hours'), 'url'=>$this->Timelog->url_options($main_project, $issue))); ?>
  <th></th>
</tr>
</thead>
<tbody>
<?php foreach($entries as $entry) : ?>
  <tr class="time-entry <?php echo $this->Candy->cycle(); ?>">
    <td class="spent_on"><?php echo $this->Candy->format_date($entry['TimeEntry']['spent_on']); ?></td>
    <td class="user"><?php echo h($this->Candy->format_username($entry['User'])); ?></td>
    <td class="activity"><?php echo h($entry['Activity']['name']); ?></td>
    <td class="project"><?php echo h($entry['Project']['name']); ?></td>
    <td class="subject">
    <?php 
      if(!empty($entry['Issue']['tracker_id'])) {
        $entry['Tracker'] = array('name'=>$trackers[$entry['Issue']['tracker_id']]);
        echo $this->Candy->link_to_issue($entry).': '.h($this->Candy->truncate($entry['Issue']['subject'], 50));
      }
    ?>
    </td>
    <td class="comments"><?php echo h($entry['TimeEntry']['comments']); ?></td>
    <td class="hours"><?php echo $this->Candy->html_hours(sprintf("%.2f",$entry['TimeEntry']['hours'])); ?></td>
    <td align="center">
    <?php if($this->Candy->authorize_for('edit_own_time_entries', $entry)): ?>
        <?php echo $this->Html->link($this->Html->image('edit.png'),   array('controller' => 'timelog', 'action' => 'edit',    'id' => $entry['TimeEntry']['id']), array('title' => __('Edit'), 'escape' => false)); ?>
        <?php echo $this->Html->link($this->Html->image('delete.png'), array('controller' => 'timelog', 'action' => 'destroy', 'id' => $entry['TimeEntry']['id']), array('title' => __('Delete'), 'method' => 'post', 'escape' => false), __('Are you sure ?')); ?>
    <?php endif; ?>
    </td>
  </tr>
<?php endforeach; ?>
</tbody>
</table>
