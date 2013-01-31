<?php foreach ($news as $item): ?>
<p><?php echo $this->Html->link($item['Project']['name'], array(
	'controller' => 'projects', 
	'action' => 'show', 
	'project_id' => $item['Project']['identifier']
	)) ?>:
<?php echo $this->Html->link($item['News']['title'], array(
	'controller' => 'news', 
	'action' => 'show',
	'project_id' => $item['Project']['identifier'], 
	'id' => $item['News']['id']
	)) ?>
<!--
<%= "(#{news.comments_count} #{lwr(:label_comment, news.comments_count).downcase})" if news.comments_count > 0 %>  
-->
<br />
<?php if (!empty($item['News']['summary'])): ?>
<span class="summary"><?php echo h($item['News']['summary']) ?></span><br /><?php endif; ?>
<span class="author"><?php echo $this->Candy->authoring($item['News']['created_on'],$item['Author']) ?></span></p>
<?php endforeach; ?>