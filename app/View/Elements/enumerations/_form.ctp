<?php echo $this->element('error_explanation'); ?>
<div class="box">
<!--[form:optvalue]-->
<?php echo $this->Form->hidden('opt',array('value'=>$opt)); ?>
<p><?php echo $this->Form->label('name', __('Name')); ?>
<?php echo $this->Form->input('name',array('type'=>'text', 'div'=>false,'size'=>30, 'label'=>false)) ?></p>

<p>
<?php echo $this->Form->label('is_default', __('Default value')); ?>
<?php echo $this->Form->input('is_default', array('type'=>'checkbox', 'div'=>false, 'label'=>false)); ?></p>

<!--[eoform:optvalue]-->
</div>