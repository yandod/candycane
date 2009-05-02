<?php echo $form->create('Setting',aa('action','edit','url',aa('?','tab=issues'))) ?>

<div class="box tabular settings">
<p><label><?php __('Allow cross-project issue relations') ?></label>
<?php echo $form->checkbox('cross_project_issue_relations', aa('checked', ($Settings->cross_project_issue_relations == '1'))); ?></p>


<p><label><?php __('Display subprojects issues on main projects by default') ?></label>
<?php echo $form->checkbox('display_subprojects_issues', aa('checked', ($Settings->display_subprojects_issues == '1'))); ?></p>

<p><label><?php __('Issues export limit') ?></label>
<?php echo $form->input('issues_export_limit',aa('value',$Settings->issues_export_limit,'size',6,'label',false,'div',false))?></p>
</div>

<fieldset class="box"><legend><?php __('Default columns displayed on the issue list') ?></legend>
<!-- <%= hidden_field_tag 'settings[issue_list_default_columns][]', '' %> -->
<!-- <p><% Query.new.available_columns.each do |column| %>
  <label><%= check_box_tag 'settings[issue_list_default_columns][]', column.name, Setting.issue_list_default_columns.include?(column.name.to_s) %>
  <%= column.caption %></label>
<% end %></p>-->
<p>
<?php
  $available_columns_values = array();
  foreach ($available_columns as $v) {
    $available_columns_values[$v['name']] = $v['caption'];
    //$available_columns_values[$v['name']]['title'] = $v['caption'];
    //echo "<label>";
    //echo $form->checkbox('issue_list_default_columns[]',aa('value',$v['name'],'label',$v['caption'],'checked',in_array($v['name'],$Settings->issue_list_default_columns)));
    //echo h($v['caption']);
    //echo "  </label>";
  }
  echo $form->input('issue_list_default_columns',aa('type','select','multiple','checkbox','options',$available_columns_values,'div',false,'label',false,'separator','','selected',$Settings->issue_list_default_columns));
?>
</p>
</fieldset>

<?php echo $form->submit(__('Save',true)) ?>
<?php echo $form->end(); ?>
