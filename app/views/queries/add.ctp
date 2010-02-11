<h2><?php __('New query') ?></h2>

<form action="<?php echo $html->url(array('controller' => 'queries', 'action' => 'add', '?'=>array('project_id' => $main_project['Project']['identifier']))) ?>" onsubmit="selectAllOptions('selected_columns');" method="POST">
<!--<% form_tag({:action => 'new', :project_id => @query.project}, :onsubmit => 'selectAllOptions("selected_columns");') do %>-->
<?php echo $this->renderElement('error_explanation'); ?>
<?php echo $this->renderElement('queries/form', array('query' => array('Query' => $this->data['Query']))) ?>
  <!--<%= render :partial => 'form', :locals => {:query => @query} %>-->
<?php echo $form->submit(__('Save', true)) ?>
  <!--<%= submit_tag l(:button_save) %>-->
<!--<% end %>-->
</form>
