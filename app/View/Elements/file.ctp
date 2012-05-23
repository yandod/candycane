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
<?php echo $this->Html->scriptBlock("prettyPrint()"); ?>
<?php $this->Html->script("prettify/prettify", false); ?>
<?php $this->Html->css("prettify/prettify", null, array(), false); ?>
</div>
