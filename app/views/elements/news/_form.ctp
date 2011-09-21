<?php echo $this->renderElement('error_explanation'); ?>
<div class="box tabular">
	<p><?php echo $form->input('title', array('div' => false, 'size' => 60)); ?></p>
	<p><?php echo $form->input('summary', array('div' => false, 'cols' => 60, 'rows' => 2)); ?></p>
	<p><?php echo $form->input('description', array('div' => false, 'cols' => 60, 'rows' => 15, 'class' => 'wiki-edit')); ?></p>
</div>
<!--
<%= wikitoolbar_for 'news_description' %>
-->