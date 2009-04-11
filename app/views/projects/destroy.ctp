<h2><?php __('Confimation') ?></h2>
<div class="warning">
<p><strong><?php echo h($this->data['Project']['name']) ?></strong><br />
<?php __('Are you sure you want to delete this project and related data ?') ?>

<% if @project_to_destroy.children.any? %>
<br /><%= l(:text_subprojects_destroy_warning, content_tag('strong', h(@project_to_destroy.children.sort.collect{|p| p.to_s}.join(', ')))) %>
<% end %>
</p>
<p>
  <?php echo $form->create('Project', array('action'=>'destroy')) ?>
    <?php echo $form->input('id', array('type'=>'hidden')) ?>
    <label><?php echo $form->input('confirm', array('type'=>'checkbox', 'value'=>1, 'label'=>__("'Yes'", true))) ?></label>
    <?php echo $form->submit('Delete') ?>
  <?php echo $form->end() ?>
</p>
</div>
