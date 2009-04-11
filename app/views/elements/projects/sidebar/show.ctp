<?php /*
    <% planning_links = []
      planning_links << link_to_if_authorized(l(:label_calendar), :controller => 'issues', :action => 'calendar', :project_id => @project)
      planning_links << link_to_if_authorized(l(:label_gantt), :controller => 'issues', :action => 'gantt', :project_id => @project)
      planning_links.compact!
      unless planning_links.empty? %>
 */ ?>
    <h3><?php __('Planning') ?></h3>
<?php /*
    <p><%= planning_links.join(' | ') %></p>
    <% end %>
 */ ?>

<?php if ($total_hours && ( true /* User.current.allowed_to?(:view_time_entries, @project) */ )): ?>
    <h3><?php __('Spent time') ?></h3>
    <p><span class="icon icon-time"><?php echo $candy->lwr('%.2f hour', $total_hours) ?></span></p>
    <p><?php echo $html->link(__('Details', true), array('controller'=>'timelog', 'action'=>'details', 'project_id'=>$this->data['Project']['identifier_or_id'])) ?> |
       <?php echo $html->link(__('Report', true), array('controller'=>'timelog', 'action'=>'report', 'project_id'=>$this->data['Project']['identifier_or_id'])) ?></p>
<?php endif ?>

