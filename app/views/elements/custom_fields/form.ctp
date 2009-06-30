<?php echo $this->renderElement('error_explanation'); ?>

<script type="text/javascript">
//<![CDATA[
function toggle_custom_field_format() {
  format = $("CustomFieldFieldFormat");
  p_length = $("CustomFieldMinLength");
  p_regexp = $("CustomFieldRegexp");
  p_values = $("CustomFieldPossibleValues");
  p_searchable = $("CustomFieldSearchable");
  p_default = $("CustomFieldDefaultValue");
  
  p_default.setAttribute('type','text');
  Element.show(p_default.parentNode);
  
  switch (format.value) {
    case "list":
      Element.hide(p_length.parentNode);
      Element.hide(p_regexp.parentNode);
      if (p_searchable) Element.show(p_searchable.parentNode);
      Element.show(p_values);
      break;
    case "bool":
      p_default.setAttribute('type','checkbox');
      Element.hide(p_length.parentNode);
      Element.hide(p_regexp.parentNode);
      if (p_searchable) Element.hide(p_searchable.parentNode);
      Element.hide(p_values);
      break;
    case "date":
      Element.hide(p_length.parentNode);
      Element.hide(p_regexp.parentNode);
      if (p_searchable) Element.hide(p_searchable.parentNode);
      Element.hide(p_values);
      break;
    case "float":
    case "int":
      Element.show(p_length.parentNode);
      Element.show(p_regexp.parentNode);
      if (p_searchable) Element.hide(p_searchable.parentNode);
      Element.hide(p_values);
      break;
    default:
      Element.show(p_length.parentNode);
      Element.show(p_regexp.parentNode);
      if (p_searchable) Element.show(p_searchable.parentNode);
      Element.hide(p_values);
      break;
  }
}

function addValueField() {
    var f = $$('p#CustomFieldPossibleValues span');
    p = document.getElementById("CustomFieldPossibleValues");
    var v = f[0].cloneNode(true);
    v.childNodes[0].value = "";
    p.appendChild(v);
}

function deleteValueField(e) {
    var f = $$('p#CustomFieldPossibleValues span');
    if (f.length == 1) {
        e.parentNode.childNodes[0].value = "";    
    } else {
        Element.remove(e.parentNode);
    }
}

//]]>
</script>
<style type="text/css">
  div.checkbox {
    display:inline;
  }
</style>

<div class="box">
<p>
  <?php echo $form->label('name', __('Name', true).'<span class="required"> *</span>'); ?>
  <?php echo $form->input('name', array('type'=>'text', 'div'=>false, 'label'=>false)); ?>
</p>
<p>
  <?php echo $form->label('field_format', __('Format', true)); ?>
  <?php echo $form->input('field_format', array('options'=>$customField->custom_field_formats_for_select(), 'div'=>false, 'label'=>false, 'onchange' => "toggle_custom_field_format();")); ?>
</p>
<p>
  <label for="CustomFieldMinLength"><?php __('Min - Max length')?></label>
  <?php echo $form->input('min_length', array('type'=>'text', 'size' => 5, 'div'=>false, 'label' => false)); ?> - 
  <?php echo $form->input('max_length', array('type'=>'text', 'size' => 5, 'div'=>false, 'label' => false)); ?><br>(<?php __('0 means no restriction')?>)
</p>
<p>
  <?php echo $form->label('regexp', __('Regular expression', true)); ?>
  <?php echo $form->input('regexp', array('type'=>'text', 'size'=>50, 'div'=>false, 'label'=>false)); ?><br>(<?php __('eg. ^[A-Z0-9]+$')?>)
</p>
<p id="CustomFieldPossibleValues">
  <label><?php __('Possible values') ?> <?php echo $form->submit("add.png", array('type'=>'image', 'onclick'=>"addValueField();return false", 'div'=>false)); ?></label>
  <?php foreach($customField->custom_field_possible_values_for_select($custom_field) as $i=>$value): ?>
  <span><?php echo $form->input("possible_values[]", array('name'=>'data[CustomField][possible_values][]', 'type'=>'text', 'value'=>$value, 'size' => 30, 'div'=>false, 'label'=>false)); ?> <?php echo $form->submit("delete.png", array('type'=>'image', 'onclick'=>"deleteValueField(this);return false", 'div'=>false)); ?><br /></span>
  <?php endforeach; ?>
</p>
<p>
  <?php echo $form->label('default_value', __('Default value', true)); ?>
  <?php echo $customField->default_value_tag($custom_field, $form); ?>
</p>
</div>

<div class="box">
<?php switch($custom_field['CustomField']['type']) : ?>
<?php case "IssueCustomField" : ?>
    
    <fieldset><legend><?php __('Trackers')?></legend>
      <?php 
        $_tags = $form->Html->tags;
        $form->Html->tags['div'] = '';
        $form->Html->tags['label'] = '%3$s';
        echo $form->input("tracker_id", array('type'=>'select', 'multiple'=>'checkbox', 'options'=>$trackers, 'selected'=>$customField->custom_fields_tracker_selected($custom_field), 'div'=>false, 'label'=>false));
        $form->Html->tags = $_tags;
      ?>
    </fieldset>
    &nbsp;
    <p>
      <?php echo $form->label('is_required', __('Required', true)); ?>
      <?php echo $form->input('is_required', array('type'=>'checkbox', 'div'=>false, 'label'=>false)); ?>
    </p>
    <p>
      <?php echo $form->label('is_for_all', __('For all projects', true)); ?>
      <?php echo $form->input('is_for_all', array('type'=>'checkbox', 'div'=>false, 'label'=>false)); ?>
    </p>
    <p>
      <?php echo $form->label('is_filter', __('Used as a filter', true)); ?>
      <?php echo $form->input('is_filter', array('type'=>'checkbox', 'div'=>false, 'label'=>false)); ?>
    </p>
    <p>
      <?php echo $form->label('searchable', __('Searchable', true)); ?>
      <?php echo $form->input('searchable', array('type'=>'checkbox', 'div'=>false, 'label'=>false)); ?>
    </p>
<?php break; ?>
<?php case "UserCustomField" ?>
    <p>
      <?php echo $form->label('is_required', __('Required', true)); ?>
      <?php echo $form->input('is_required', array('type'=>'checkbox', 'div'=>false, 'label'=>false)); ?>
    </p>
<?php break; ?>
<?php case "ProjectCustomField" ?>
    <p>
      <?php echo $form->label('is_required', __('Required', true)); ?>
      <?php echo $form->input('is_required', array('type'=>'checkbox', 'div'=>false, 'label'=>false)); ?>
    </p>
<?php break; ?>
<?php case "TimeEntryCustomField" ?>
    <p>
      <?php echo $form->label('is_required', __('Required', true)); ?>
      <?php echo $form->input('is_required', array('type'=>'checkbox', 'div'=>false, 'label'=>false)); ?>
    </p>
<?php endswitch; ?>
</div>
<?php echo $javascript->codeBlock("toggle_custom_field_format();"); ?>
