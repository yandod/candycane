<h2><?php $this->Candy->html_title(__('Home'))  ?></h2>

<div class="splitcontentleft">
  <!-- TODO: <%= textilizable Setting.welcome_text %> -->
  <?php echo $this->Candy->textilizable($Settings->welcome_text) ?>
  <?php if (!empty($news)): ?>
  <div class="box">
	<h3><?php echo __('Latest news')?></h3>
		<?php echo $this->element('news',array('news' => $news)) ?>
		<?php echo $this->Html->link(__('View all news'), array('controller' => 'news')) ?>
  </div>
  <?php endif; ?>
</div>

<div class="splitcontentright">
	<?php if (!empty($projects)): ?>
	<div class="box">
	<h3 class="icon22 icon22-projects"><?php echo __('Latest projects') ?></h3>
		<ul>
		<?php foreach ($projects as $project): ?>
			<li>
			<?php echo $this->Html->link($project['Project']['name'], array('controller' => 'projects', 'action' => 'show', 'project_id' => $project['Project']['identifier'])) ?> (<?php echo $this->Candy->format_time($project['Project']['created_on'])?>)
<!--  			<%= textilizable project.short_description, :project => project %> -->
				<?php echo $this->Candy->textilizable($project['Project']['description']) ?>
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
