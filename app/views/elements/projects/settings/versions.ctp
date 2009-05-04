<?php if (!empty($main_project['Version'])) : ?>
<table class="list">
	<thead>
    <th><?php __('Version') ?></th>
    <th><?php __('Date') ?></th>
    <th><?php __('Description') ?></th>
    <th><?php __('Wiki page') ?></th>
    <th style="width:15%"></th>
    <th style="width:15%"></th>
    </thead>
	<tbody>
<!-- TODO: sort -->
<?php foreach ($main_project['Version'] as $version_row): ?>
    <tr class="<?php echo $candy->cycle() ?>">
    <td><?php echo $html->link($version_row['name'],aa('controller','versions','action','show','id',$version_row['id'])); ?></td>
    <td align="center"><%= format_date(version.effective_date) %></td>
    <td><%=h version.description %></td>
    <td><%= link_to(version.wiki_page_title, :controller => 'wiki', :page => Wiki.titleize(version.wiki_page_title)) unless version.wiki_page_title.blank? || @project.wiki.nil? %></td>
    <td align="center"><%= link_to_if_authorized l(:button_edit), { :controller => 'versions', :action => 'edit', :id => version }, :class => 'icon icon-edit' %></td>
    <td align="center"><%= link_to_if_authorized l(:button_delete), {:controller => 'versions', :action => 'destroy', :id => version}, :confirm => l(:text_are_you_sure), :method => :post, :class => 'icon icon-del' %></td>
    </tr>
<?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
<p class="nodata"><?php __('No data to display') ?></p>
<?php endif; ?>

<!-- TOOO: auth -->
<p><?php echo $html->link(__('New version',true),aa('controller','projects','action','add_version','id',$main_project['Project']['identifier'])); ?></p>

