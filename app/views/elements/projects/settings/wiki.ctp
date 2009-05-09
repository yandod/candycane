<!-- <% remote_form_for :wiki, @wiki,
                   :url => { :controller => 'wikis', :action => 'edit', :id => @project },
                   :builder => TabularFormBuilder,
                   :lang => current_language do |f| %>

<%= error_messages_for 'wiki' %> -->
<?php echo $this->renderElement('error_explanation'); ?>
  <?php echo $ajax->form(
    array('options' =>array(
      'model' => 'Wiki',
      'update' => 'tab-content-wiki',
      'url' => array(
        'controller' => 'wikis',
        'action' => 'edit',
        'project_id' => $main_project['Project']['identifier'],
        'id' => null
      )
    ))
  ) ?>
<div class="box tabular">
<p><?php echo $form->text('Wiki.start_page',aa('size',60,'div',false,'label',false)); ?><br />
<em><?php __('Unallowed characters') ?>: , . / ? ; : |</em></p>
</div>

<div class="contextual">
<?php
if ( !empty($main_project['Wiki'])) { 
  echo $html->link(__('Delete',true),aa('controller','wikis','action','destroy','project_id',$main_project['Project']['identifier']),aa('class','icon icon-del'));
} ?>
</div>

<?php
 if ( !isset($main_project['Wiki']['id'])) {
   echo $form->submit(__('Create',true),aa('div',false));
 } else {
   echo $form->submit(__('Save',true),aa('div',false));
 }
?>
<?php echo $form->end(); ?>
