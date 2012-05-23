<script type="text/javascript">
//<![CDATA[
function add_filter() {
    select = $('add_filter_select');
    field = select.value
    Element.show('tr_' +  field);
    check_box = $('cb_' + field);
    check_box.checked = true;
    toggle_filter(field);
    select.selectedIndex = 0;
    
    for (i=0; i<select.options.length; i++) {
        if (select.options[i].value == field) {
            select.options[i].disabled = true;
        }    
    }
}

function toggle_filter(field) {
    check_box = $('cb_' + field);
    
    if (check_box.checked) {
        Element.show("operators_" + field);
        toggle_operator(field);
    } else {
        Element.hide("operators_" + field);
        Element.hide("div_values_" + field);
  }
}

function toggle_operator(field) {
  operator = $("operators_" + field);
  switch (operator.value) {
    case "!*":
    case "*":
    case "t":
    case "w":
    case "o":
    case "c":
      Element.hide("div_values_" + field);
      break;
    default:
      Element.show("div_values_" + field);
      break;
  }
}

function toggle_multi_select(field) {
    select = $('values_' + field);
    if (select.multiple == true) {
        select.multiple = false;
    } else {
        select.multiple = true;
    }
}

//]]>
</script>

<table width="100%">
<tr>
<td>
<table>
<?php foreach ($this->Queries->available_filters_sort_order($available_filters) as $field => $filter): ?>
    <tr <?php if (!isset($show_filters[$field])): ?> style="display:none;"<?php endif ?> id="tr_<?php echo h($field) ?>" class="filter">
    <td style="width:200px;">
        <?php echo preg_replace('/^<input[^>]+>/s', '', $this->Form->checkbox('Filter.fields_' . $field, array('value' => $field, 'name' => 'fields[' . $field . ']', 'onclick' => "toggle_filter(" . $this->Js->value($field) . ")", 'id' => 'cb_' . $field, 'label' => false, 'hidden' => false))) ?>
        <label for="cb_<?php echo h($field) ?>"><?php echo __($field) ?></label>
    </td>
    <td style="width:150px;">
    	<?php 
			echo $this->Form->select(
				'Filter.operators_' . $field,
				$filter['operators'],
				array(
					'name' => 'operators[' . $field . ']',
					'id' => 'operators_' . $field,
					'onchange' => "toggle_operator(" . $this->Js->value($field) . ");",
					'class' => 'select-small',
					'style' => 'vertical-align: top;'
				)
			);?>
    </td>
    <td>    
    <div id="div_values_<?php echo h($field) ?>" style="display:none;">
    <?php
    switch ($filter['type']):
    case 'list':
    case 'list_optional':
    case 'list_status':
    case 'list_subprojects':
    ?>
      <?php 
				$default_values = "1";
				if(!empty($this->request->data['Filter']['values_' . $field])) {
					$default_values = $this->request->data['Filter']['values_' . $field];
				}
		echo $this->Form->select(
			'Filter.values_' . $field,
			$filter['values'],
			//$default_values,
			array_merge(count($filter['values']) > 1 ? array('multiple' => 'true'): array(),
			array(
				'name' => 'values[' . $field . ']',
				'class' => 'select-small',
				'style' => 'vertical-align: top;',
				'id' => 'values_' . $field,
				'value' => $default_values
			))); 
			?>
        <?php echo $this->Html->link($this->Html->image('bullet_toggle_plus.png'), '#', array('onclick' => "toggle_multi_select('" . $this->Js->value($field) . "')", 'style' => 'vertical-align: bottom', 'escape' => false)) ?>
    <?php
      break;
    case 'date':
    case 'date_past':
    ?>
        <?php echo $this->Form->input('Filter.values_' . $field, array('name' => 'values[' . $field . ']', 'id' => 'values_' . $field, 'size' => '3', 'class' => 'select-small', 'label' => false, 'div' => false)) ?> <?php echo __('days') ?>
    <?php
      break;
    case 'string':
    case 'text':
    ?>
        <?php echo $this->Form->input('Filter.values_' . $field, array('name' => 'values[' . $field . ']', 'id' => 'values_' . $field, 'size' => '30', 'class' => 'select-small', 'label' => false, 'div' => false)) ?>
    <?php
      break;
    case 'integer':
    ?>
        <?php echo $this->Form->input('Filter.values_' . $field, array('name' => 'values[' . $field . ']', 'id' => 'values_' . $field, 'size' => '3', 'class' => 'select-small', 'label' => false, 'div' => false)) ?>
    <?php
      break;
    endswitch;
    ?>
    </div>
    <script type="text/javascript">toggle_filter('<?php echo h($field) ?>');</script>
    </td>
    </tr>
<?php endforeach ?>
</table>
</td>
<td class="add-filter">
<?php echo __('Add filter') ?>:
<?php echo $this->Form->select(
	'Filter.add_filter_select',
	$this->Queries->add_filter_select_options(
		$this->Queries->available_filters_sort_order($available_filters)),
		array(
			'name' => 'add_filter_select',
			'onchange' => 'add_filter()',
			'class' => 'select-small',
			'id' => 'add_filter_select'
		)
	) ?>
</td>
</tr>
</table>
