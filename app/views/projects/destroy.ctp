<h2><?php echo __('Confirmation',true) ?></h2>
<div class="warning">
<p><strong><?php echo h($this->data['Project']['name']) ?></strong><br />
<?php __('Are you sure you want to delete this project and related data ?') ?>

<?php if (!empty($subprojects)): ?>
	<?php $str = implode(',',Set::extract('/Project/name',$subprojects));?>
	<br /><strong><?php echo $candy->lwr_r('Its subproject(%s)',$str)?></strong>
<?php endif; ?>
</p>
<p>
  <?php echo $form->create('Project', array('action'=>'destroy','url' => array('id' => $mainProject['Project']['identifier'] ))) ?>
    <label><?php echo $form->input('confirm', array('type'=>'checkbox', 'value'=>1, 'label'=>__("Yes", true))) ?></label>
    <?php echo $form->submit(__('Delete',true)) ?>
  <?php echo $form->end() ?>
</p>
</div>
