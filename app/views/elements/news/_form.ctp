<?php echo $this->renderElement('error_explanation'); ?>
<div class="box tabular">
<p><?php echo $form->input( 'title', aa('div', false, 'size', 60) ) ; ?></p>
<p><?php echo $form->input( 'summary', aa('div', false, 'cols', 60, 'rows', 2) ) ; ?></p>
<p><?php echo $form->input( 'description', aa('div', false, 'cols', 60, 'rows', 15, 'class', 'wiki-edit') ) ; ?></p>
</div>
<!--
<%= wikitoolbar_for 'news_description' %>
-->