<% labelled_tabular_form_for :project, @project, :url => { :action => "edit", :id => @project } do |f| %>
<?php echo $this->renderElement('projects/form'); ?>
<%= render :partial => 'form', :locals => { :f => f } %>
<%= submit_tag l(:button_save) %>
<% end %>
