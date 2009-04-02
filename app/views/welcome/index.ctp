<h2><?php __('Home') ?></h2>

<div class="splitcontentleft">
  <!-- TODO: <%= textilizable Setting.welcome_text %> -->
  <?php if (!empty($news)): ?>
  <div class="box">
	<h3><?php __('Latest news')?></h3>
		<?php echo $this->renderElement('news',aa('news',$news)) ?>
		<?php echo $html->link(__('View all news',true),'/news/') ?>
  </div>
  <?php endif; ?>
</div>

<div class="splitcontentright">
	<?php if (!empty($projects)): ?>
	<div class="box">
	<h3 class="icon22 icon22-projects"><?php __('Latest projects') ?></h3>
		<ul>
		<?php foreach ($projects as $project): ?>
			<li>
			<?php echo $html->link(h($project['Project']['name']),"/projects/show/{$project['Project']['id']}") ?>(<?php echo $candy->format_time($project['Project']['created_on'])?>)
<!--  			<%= textilizable project.short_description, :project => project %> -->
				<?php echo "<p>".$project['Project']['description']."</p>" ?>
			</li>
		<?php endforeach; ?>
		</ul>
	</div>
	<?php endif; ?>
</div>	

<!--  <% content_for :header_tags do %> -->
<!--  <%= auto_discovery_link_tag(:atom, {:controller => 'news', :action => 'index', :key => User.current.rss_key, :format => 'atom'},
                                   :title => "#{Setting.app_title}: #{l(:label_news_latest)}") %>
<%= auto_discovery_link_tag(:atom, {:controller => 'projects', :action => 'activity', :key => User.current.rss_key, :format => 'atom'},
                                   :title => "#{Setting.app_title}: #{l(:label_activity)}") %>
-->
<!-- <% end %> -->
