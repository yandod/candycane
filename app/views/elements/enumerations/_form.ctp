<?php echo $this->renderElement('error_explanation'); ?>
<div class="box">
<!--[form:optvalue]-->
<?php echo $form->hidden('opt',array('value'=>$opt)); ?>
<p><?php echo $form->label('name', __('Name', true)); ?>
<?php echo $form->input('name',array('type'=>'text', 'div'=>false,'size'=>30, 'label'=>false)) ?></p>

<p>
<?php echo $form->label('is_default', __('Default value', true)); ?>
<?php echo $form->input('is_default', array('type'=>'checkbox', 'div'=>false, 'label'=>false)); ?></p>

<!--[eoform:optvalue]-->
</div>