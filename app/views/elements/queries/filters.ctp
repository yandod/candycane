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
<?php foreach ($queries->available_filters_sort_order($query['Query']['available_filters']) as $field => $filter): ?>
<!--<% query.available_filters.sort{|a,b| a[1][:order]<=>b[1][:order]}.each do |filter| %>-->
<!--    <% field = filter[0]
       options = filter[1] %>-->
    <tr style="display:none;" id="tr_<?php echo h($field) ?>" class="filter">
<!--    <tr <%= 'style="display:none;"' unless query.has_filter?(field) %> id="tr_<?php echo h($field) ?>" class="filter">-->
    <td style="width:200px;">
        <input type="checkbox" name="fields[]" value="" onclick="toggle_filter('<?php echo $javascript->escapeString($field) ?>')" id="cb_<?php echo h($field) ?>" />
        <!--<%= check_box_tag 'fields[]', field, query.has_filter?(field), :onclick => "toggle_filter('#{field}');", :id => "cb_#{field}" %>-->
        <label for="cb_<?php echo h($field) ?>"><?php __($field) ?><!--<%= filter[1][:name] || l(("field_"+field.to_s.gsub(/\_id$/, "")).to_sym) %>--></label>
    </td>
    <td style="width:150px;">
        <?php echo $form->select('Filter.' . $field, $filter['operators_by_filter_type'], null, array('name' => 'operators[' . $field . ']', 'id' => 'operators_' . $field, 'onchange' => "toggle_operator('" . $javascript->escapeString($field) . "');", 'class' => 'select-small', 'style' => 'vertical-align: top;')) ?>
        <!--<%= select_tag "operators[#{field}]", options_for_select(operators_for_select(options[:type]), query.operator_for(field)), :id => "operators_#{field}", :onchange => "toggle_operator('#{field}');", :class => "select-small", :style => "vertical-align: top;" %>-->
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
      <?php echo $form->select('Filter.' . $field, $filter['values'], null, am(count($filter['values']) > 1 ? array('mutiple' => 'true'): a(), array('name' => 'values[' . $field . ']', 'class' => 'select-small', 'style' => 'vertical-align: top;', 'id' => 'values_' . $field))) ?>
      <!--
        <select <%= "multiple=true" if query.values_for(field) and query.values_for(field).length > 1 %> name="values[<?php echo h($field) ?>][]" id="values_<?php echo h($field) ?>" class="select-small" style="vertical-align: top;">
        <%= options_for_select options[:values], query.values_for(field) %>        
        </select>
        -->
        <?php echo $html->link($html->image('bullet_toggle_plus.png'), '#', array('onclick' => "toggle_multi_select('" . $javascript->escapeString($field) . "')", 'style' => 'vertical-align: bottom'), null, false) ?>
        <!--
        <%= link_to_function image_tag('bullet_toggle_plus.png'), "toggle_multi_select('#{field}');", :style => "vertical-align: bottom;" %>
    <% when :date, :date_past %>
        -->
        <!--
        <%= text_field_tag "values[#{field}][]", query.values_for(field), :id => "values_#{field}", :size => 3, :class => "select-small" %> <%= l(:label_day_plural) %>
    <% when :string, :text %>
        <%= text_field_tag "values[#{field}][]", query.values_for(field), :id => "values_#{field}", :size => 30, :class => "select-small" %>
    <% when :integer %>
        <%= text_field_tag "values[#{field}][]", query.values_for(field), :id => "values_#{field}", :size => 3, :class => "select-small" %>
        -->
        <?php break; ?>
    <?php endswitch ?>
    </div>
    <script type="text/javascript">toggle_filter('<?php echo h($field) ?>');</script>
    </td>
    </tr>
<?php endforeach ?>
</table>
</td>
<td class="add-filter">
<?php __('Add filter') ?>
<?php echo $form->select('Filter.add_filter_select', $queries->add_filter_select_options($queries->available_filters_sort_order($query['Query']['available_filters'])), null, array('name' => 'add_filter_select', 'onchange' => 'add_filter()', 'class' => 'select-small', 'id' => 'add_filter_select')) ?>
<!--
<%= select_tag 'add_filter_select', options_for_select(
  [["",""]] + query.available_filters.sort{
    |a,b| a[1][:order]<=>b[1][:order]
  }.collect{
    |field| [ field[1][:name] || l(("field_"+field[0].to_s.gsub(/\_id$/, "")).to_sym), field[0]] unless query.has_filter?(field[0])}.compact),
  :onchange => "add_filter();",
  :class => "select-small" %>-->
</td>
</tr>
</table>
