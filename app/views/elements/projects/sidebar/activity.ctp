<?php echo $form->create('Project', array('action'=>'activity', 'method'=>'get')) ?>
<h3><?php __('Activity') ?></h3>
<p><% @activity.event_types.each do |t| %>
<label><%= check_box_tag "show_#{t}", 1, @activity.scope.include?(t) %> <%= l("label_#{t.singularize}_plural")%></label><br />
<% end %></p>
<% if @project && @project.active_children.any? %>
    <p><label><%= check_box_tag 'with_subprojects', 1, @with_subprojects %> <%=l(:label_subproject_plural)%></label></p>
    <%= hidden_field_tag 'with_subprojects', 0 %>
<% end %>
<%= hidden_field_tag('user_id', params[:user_id]) unless params[:user_id].blank? %>
<p><?php echo $form->submit(__('Apply', true), array('class'=>'button-small')) ?></p>
<?php echo $form->end() ?>

