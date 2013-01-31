<?php $this->loadHelper('Candy'); ?>
<h1><?php echo $this->Html->link($news['News']['title'], $news_url); ?></h1>
<em><?php echo $this->Candy->format_username($news['Author']); ?></em>

<p><?php echo nl2br(h($news['News']['description'])); ?></p>