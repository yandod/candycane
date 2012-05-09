<h2><?php echo __("Index by date") ?></h2>

<?php if (sizeof($pages) === 0) : ?>
<p class="nodata"><?php echo __("No data to display") ?></p>
<?php endif ?>

<?php if (sizeof($pages) !== 0) : ?>
<?php foreach($pages_by_date as $day=>$pages) : ?>
<h3><?php echo $this->Candy->format_date($day) ?></h3>
<ul>
<?php foreach($pages as $page) : ?>
    <li><?php echo $this->Html->link($this->Wiki->pretty_title($page['WikiPage']['title']), array('action' => 'index', 'project_id' => $main_project['Project']['identifier'], 'wikipage' => $page['WikiPage']['title'])) ?></li>
<?php endforeach ?>
</ul>
<?php endforeach ?>
<?php endif ?>

<?php $this->set('Sidebar', $this->element('wiki/sidebar')) ?>

<?php if (sizeof($pages) !== 0) : ?>
<p class="other-formats">
<?php echo __("'Also available in:'") ?>
<span><?php echo $this->Html->link('Atom', array('controller' => 'projects', 'action' => 'activity', 'project_id' => $main_project['Project']['identifier'], '?show_wiki_pages=1&format=atom'  /*key User.current.rss_key*/), array('class' => 'feed')) ?></span>
<span><?php echo $this->Html->link('HTML', array('action' => 'special', 'project_id' => $main_project['Project']['identifier'] ,'wikipage' => 'export'), array('class' => 'html')); ?></span>
</p>
<?php endif ?>

<!--% content_for :header_tags do %>
<%= auto_discovery_link_tag(:atom, :controller => 'projects', :action => 'activity', :id => @project, :show_wiki_pages => 1, :format => 'atom', :key => User.current.rss_key) %>
<% end %-->
