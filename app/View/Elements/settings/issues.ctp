<?php echo $this->Form->create(
	'Setting',
	array(
		'action' => 'edit',
		'url' => array(
			'?' => 'tab=issues'
		)
	)
); ?>

<div class="box tabular settings">
<p><label><?php echo __('Allow cross-project issue relations') ?></label>
<?php echo $this->Form->checkbox(
	'cross_project_issue_relations',
	array(
		'checked' => ($Settings->cross_project_issue_relations == '1')
	)
);?></p>


<p><label><?php echo __('Display subprojects issues on main projects by default') ?></label>
<?php echo $this->Form->checkbox(
	'display_subprojects_issues',
	array(
		'checked' => ($Settings->display_subprojects_issues == '1')
	)
); ?></p>

<p><label><?php echo __('Issues export limit') ?></label>
<?php echo $this->Form->input(
	'issues_export_limit',
	array(
		'value' => $Settings->issues_export_limit,
		'size' => 6,
		'label' => false,
		'div' => false
	)
);?></p>
</div>

<fieldset class="box"><legend><?php echo __('Default columns displayed on the issue list') ?></legend>
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
    //echo $this->Form->checkbox('issue_list_default_columns[]',array(
	//	'value' => $v['name'],
	//	'label' => $v['caption'],
	//	'checked' => in_array($v['name'],$Settings->issue_list_default_columns)
	//));
    //echo h($v['caption']);
    //echo "  </label>";
  }
  echo $this->Form->input(
	'issue_list_default_columns',
	array(
		'type' => 'select',
		'multiple' => 'checkbox',
		'options' => $available_columns_values,
		'div' => false,
		'label' => false,
		'separator' => '',
		'selected' => $Settings->issue_list_default_columns
	)
); ?>
</p>
</fieldset>

<?php echo $this->Form->submit(__('Save')) ?>
<?php echo $this->Form->end(); ?>
