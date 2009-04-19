<h2><?php echo __("Index by title") ?></h2>


<?php if (sizeof($pages) === 0) : ?>
<p class="nodata"><%= l(:label_no_data) %></p>
<?php endif ?>

<!--%= render_page_hierarchy(@pages_by_parent_id) %-->

<?php $this->set('Sidebar', $this->renderElement('wiki/sidebar')) ?>

<?php if (sizeof($pages) !== 0) : ?>
<p class="other-formats">
<?php echo __("'Also available in:'") ?>
<span><?php echo $html->link('Atom', array('controller' => 'projects', 'action' => 'activity', 'project_id' => $project_id, 'show_wiki_pages' => 1, 'format' => 'atom', 'key' => '' /*User.current.rss_key*/), array('class' => 'feed')) ?></span>
<span><?php echo $html->link('HTML', array('action' => 'special', 'project_id' => $project_id ,'wikipage' => 'export'), array('class' => 'html')); ?></span>
</p>
<?php endif ?>

<!--% content_for :header_tags do %>
<%= auto_discovery_link_tag(:atom, :controller => 'projects', :action => 'activity', :id => @project, :show_wiki_pages => 1, :format => 'atom', :key => User.current.rss_key) %>
<% end %-->
