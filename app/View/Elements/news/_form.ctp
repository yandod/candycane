<?php echo $this->element('error_explanation'); ?>
<div class="box tabular">
	<p><?php echo $this->Form->input('title', array('div' => false, 'size' => 60)); ?></p>
	<p><?php echo $this->Form->input('summary', array('div' => false, 'cols' => 60, 'rows' => 2)); ?></p>
	<p><?php echo $this->Form->input('description', array('div' => false, 'cols' => 60, 'rows' => 15, 'class' => 'wiki-edit')); ?></p>
</div>
<!--
<%= wikitoolbar_for 'news_description' %>
-->