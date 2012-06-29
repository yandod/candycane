<h1><?php echo $this->Html->link($news['News']['title'], $news_url); ?></h1>
<em><?php echo $news['Author']['name']; ?></em>

<p><?php echo nl2br(h($news['News']['description'])); ?></p>