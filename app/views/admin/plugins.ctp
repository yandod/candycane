<h2><?php __('Plugins'); ?></h2>

<?php if (! empty($plugins)): ?>
<table class="list plugins">
    <% @plugins.each do |plugin| %>
        <tr class="<%= cycle('odd', 'even') %>">
        <td><span class="name"><%=h plugin.name %></span>
            <%= content_tag('span', h(plugin.description), :class => 'description') unless plugin.description.blank? %>
						<%= content_tag('span', link_to(h(plugin.url), plugin.url), :class => 'url') unless plugin.url.blank? %>
				</td>
        <td class="author"><%= plugin.author_url.blank? ? h(plugin.author) : link_to(h(plugin.author), plugin.author_url) %></td>
        <td class="version"><%=h plugin.version %></td>
        <td class="configure"><%= link_to(l(:button_configure), :controller => 'settings', :action => 'plugin', :id => plugin.id) if plugin.configurable? %></td>
        </tr>
    <% end %>
</table>
<?php else: ?>
<p class="nodata"><?php __('No data to display'); ?></p>
<?php endif; ?>


<!--
<% if @plugins.any? %>
<table class="list plugins">
    <% @plugins.each do |plugin| %>
        <tr class="<%= cycle('odd', 'even') %>">
        <td><span class="name"><%=h plugin.name %></span>
            <%= content_tag('span', h(plugin.description), :class => 'description') unless plugin.description.blank? %>
						<%= content_tag('span', link_to(h(plugin.url), plugin.url), :class => 'url') unless plugin.url.blank? %>
				</td>
        <td class="author"><%= plugin.author_url.blank? ? h(plugin.author) : link_to(h(plugin.author), plugin.author_url) %></td>
        <td class="version"><%=h plugin.version %></td>
        <td class="configure"><%= link_to(l(:button_configure), :controller => 'settings', :action => 'plugin', :id => plugin.id) if plugin.configurable? %></td>
        </tr>
    <% end %>
</table>
<% else %>
<p class="nodata"><%= l(:label_no_data) %></p>
<% end %>
-->