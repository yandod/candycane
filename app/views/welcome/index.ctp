<h2><?php __('label_home') ?></h2>

<div class="splitcontentleft">
  <!-- TODO: <%= textilizable Setting.welcome_text %> -->
  <?php if (!empty($news)): ?>
  <div class="box">
	<h3><?php __('label_news_latest')?></h3>
		<?php echo $this->renderElement('news',aa('news',$news)) ?>
		<?php echo $html->link(__('label_news_view_all',true),'/news/') ?>
  </div>
  <?php endif; ?>
</div>

<div class="splitcontentright">
    <% if @projects.any? %>
	<div class="box">
	<h3 class="icon22 icon22-projects"><%=l(:label_project_latest)%></h3>
		<ul>
		<% for project in @projects %>
			<li>
			<%= link_to h(project.name), :controller => 'projects', :action => 'show', :id => project %> (<%= format_time(project.created_on) %>)
			<%= textilizable project.short_description, :project => project %>
			</li>
		<% end %>
		</ul>
	</div>
	<% end %>
</div>	

<% content_for :header_tags do %>
<%= auto_discovery_link_tag(:atom, {:controller => 'news', :action => 'index', :key => User.current.rss_key, :format => 'atom'},
                                   :title => "#{Setting.app_title}: #{l(:label_news_latest)}") %>
<%= auto_discovery_link_tag(:atom, {:controller => 'projects', :action => 'activity', :key => User.current.rss_key, :format => 'atom'},
                                   :title => "#{Setting.app_title}: #{l(:label_activity)}") %>
<% end %>
