<h2><?php echo __('New query') ?></h2>

<form action="<?php echo $this->Html->url(array('controller' => 'queries', 'action' => 'add', '?'=>array('project_id' => $main_project['Project']['identifier']))) ?>" onsubmit="selectAllOptions('selected_columns');" method="POST">
<!--<% form_tag({:action => 'new', :project_id => @query.project}, :onsubmit => 'selectAllOptions("selected_columns");') do %>-->
<?php echo $this->element('error_explanation'); ?>
<?php echo $this->element('queries/form', array('query' => array('Query' => $this->request->data['Query']))) ?>
  <!--<%= render :partial => 'form', :locals => {:query => @query} %>-->
<?php echo $this->Form->submit(__('Save')) ?>
  <!--<%= submit_tag l(:button_save) %>-->
<!--<% end %>-->
</form>
