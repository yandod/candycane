<?php foreach ($news as $item): ?>
<p><?php echo $html->link($item['Project']['name'],'/projects/show/'.$item['Project']['id']) ?>:
<?php echo $html->link($item['News']['title'],'/news/show/'.$item['News']['id']) ?>
<!--
<%= "(#{news.comments_count} #{lwr(:label_comment, news.comments_count).downcase})" if news.comments_count > 0 %>  
-->
<br />
<?php if (!empty($item['News']['summary'])): ?>
<span class="summary"><?php echo h($item['News']['summary']) ?></span><br /><?php endif; ?>
<span class="author"><%= authoring news.created_on, news.author %></span></p>
<?php endforeach; ?>