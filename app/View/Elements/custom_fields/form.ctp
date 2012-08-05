<?php echo $this->element('error_explanation'); ?>

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
  <?php echo $this->Form->label('name', __('Name').'<span class="required"> *</span>'); ?>
  <?php echo $this->Form->input('name', array('type'=>'text', 'div'=>false, 'label'=>false)); ?>
</p>
<p>
  <?php echo $this->Form->label('field_format', __('Format')); ?>
  <?php echo $this->Form->input('field_format', array('options'=>$this->CustomField->custom_field_formats_for_select(), 'div'=>false, 'label'=>false, 'onchange' => "toggle_custom_field_format();")); ?>
</p>
<p>
  <label for="CustomFieldMinLength"><?php echo __('Min - Max length')?></label>
  <?php echo $this->Form->input('min_length', array('type'=>'text', 'size' => 5, 'div'=>false, 'label' => false)); ?> - 
  <?php echo $this->Form->input('max_length', array('type'=>'text', 'size' => 5, 'div'=>false, 'label' => false)); ?><br>(<?php echo __('0 means no restriction')?>)
</p>
<p>
  <?php echo $this->Form->label('regexp', __('Regular expression')); ?>
  <?php echo $this->Form->input('regexp', array('type'=>'text', 'size'=>50, 'div'=>false, 'label'=>false)); ?><br>(<?php echo __('eg. ^[A-Z0-9]+$')?>)
</p>
<p id="CustomFieldPossibleValues">
  <label><?php echo __('Possible values') ?> <?php echo $this->Form->submit("add.png", array('type'=>'image', 'onclick'=>"addValueField();return false", 'div'=>false)); ?></label>
  <?php foreach($this->CustomField->custom_field_possible_values_for_select($custom_field) as $i=>$value): ?>
  <span><?php echo $this->Form->input("possible_values[]", array('name'=>'data[CustomField][possible_values][]', 'type'=>'text', 'value'=>$value, 'size' => 30, 'div'=>false, 'label'=>false)); ?> <?php echo $this->Form->submit("delete.png", array('type'=>'image', 'onclick'=>"deleteValueField(this);return false", 'div'=>false)); ?><br /></span>
  <?php endforeach; ?>
</p>
<p>
  <?php echo $this->Form->label('default_value', __('Default value')); ?>
  <?php echo $this->CustomField->default_value_tag($custom_field); ?>
</p>
</div>

<div class="box">
<?php switch($custom_field['CustomField']['type']) : ?>
<?php case "IssueCustomField" : ?>
    
    <fieldset><legend><?php echo __('Trackers')?></legend>
      <?php 
        $_tags = $this->Form->Html->tags;
        $this->Form->Html->tags['div'] = '';
        $this->Form->Html->tags['label'] = '%3$s';
        echo $this->Form->input("tracker_id", array('type'=>'select', 'multiple'=>'checkbox', 'options'=>$trackers, 'selected'=>$this->CustomField->custom_fields_tracker_selected($custom_field), 'div'=>false, 'label'=>false));
        $this->Form->Html->tags = $_tags;
      ?>
    </fieldset>
    &nbsp;
    <p>
      <?php echo $this->Form->label('is_required', __('Required')); ?>
      <?php echo $this->Form->input('is_required', array('type'=>'checkbox', 'div'=>false, 'label'=>false)); ?>
    </p>
    <p>
      <?php echo $this->Form->label('is_for_all', __('For all projects')); ?>
      <?php echo $this->Form->input('is_for_all', array('type'=>'checkbox', 'div'=>false, 'label'=>false)); ?>
    </p>
    <p>
      <?php echo $this->Form->label('is_filter', __('Used as a filter')); ?>
      <?php echo $this->Form->input('is_filter', array('type'=>'checkbox', 'div'=>false, 'label'=>false)); ?>
    </p>
    <p>
      <?php echo $this->Form->label('searchable', __('Searchable')); ?>
      <?php echo $this->Form->input('searchable', array('type'=>'checkbox', 'div'=>false, 'label'=>false)); ?>
    </p>
<?php break; ?>
<?php case "UserCustomField" ?>
    <p>
      <?php echo $this->Form->label('is_required', __('Required')); ?>
      <?php echo $this->Form->input('is_required', array('type'=>'checkbox', 'div'=>false, 'label'=>false)); ?>
    </p>
<?php break; ?>
<?php case "ProjectCustomField" ?>
    <p>
      <?php echo $this->Form->label('is_required', __('Required')); ?>
      <?php echo $this->Form->input('is_required', array('type'=>'checkbox', 'div'=>false, 'label'=>false)); ?>
      <?php echo $this->Form->hidden('is_for_all', array('value'=>'1')); ?>
    </p>
<?php break; ?>
<?php case "TimeEntryCustomField" ?>
    <p>
      <?php echo $this->Form->label('is_required', __('Required')); ?>
      <?php echo $this->Form->input('is_required', array('type'=>'checkbox', 'div'=>false, 'label'=>false)); ?>
    </p>
<?php endswitch; ?>
</div>
<?php echo $this->Html->scriptBlock("toggle_custom_field_format();"); ?>
