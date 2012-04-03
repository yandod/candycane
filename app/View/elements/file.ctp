<div class="autoscroll">
<table class="filecontent CodeRay">
<tr><td class="line-code">
<pre class="prettyprint">
<?php
  $line_num = 1;
  foreach ($content as $line) {
    echo '<span class="nocode line-num">'.$line_num++.':</span>';
    echo h($line);
  }
?>
</pre></td></tr></table>
<?php echo $javascript->codeBlock("prettyPrint()"); ?>
<?php $javascript->link("prettify/prettify", false); ?>
<?php $this->Html->css("prettify/prettify", null, array(), false); ?>
</div>
