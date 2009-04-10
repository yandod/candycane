<div class="contextual">
<?php echo $html->link(__('Edit', true), '/versions/edit/'.$this->data['Version']['id'], array('class'=>'icon icon-edit')) ?>
<?php /*
<%= link_to_if_authorized l(:button_edit), {:controller => 'versions', :action => 'edit', :id => @version}, :class => 'icon icon-edit' %>
 */ ?>
</div>

<h2><?php echo h($this->data['Version']['name']) ?></h2>

<div id="version-summary">
<?php if (($this->data['Version']['estimated_hours'] > 0) || (true)): ?>
<?php /*
<% if @version.estimated_hours > 0 || User.current.allowed_to?(:view_time_entries, @project) %>
*/ ?>
<fieldset><legend><?php echo __('Time tracking') ?></legend>
<table>
<tr>
    <td width="130px" align="right"><?php echo __('Estimated time') ?></td>
    <td width="240px" class="total-hours"width="130px" align="right">
      <?php echo $candy->lwr('%.2f hour', $this->data['Version']['estimated_hours']) ?>
<?php /*
    <%= html_hours(lwr(:label_f_hour, @version.estimated_hours)) %>
 */ ?>
    </td>
</tr>
<?php if ($current_user): ?>
<?php /*
<% if User.current.allowed_to?(:view_time_entries, @project) %>
 */ ?>
<tr>
    <td width="130px" align="right"><?php echo __('Spent time') ?></td>
    <td width="240px" class="total-hours">
      <?php echo 'FIXME' ?>
<?php /*
      <%= html_hours(lwr(:label_f_hour, @version.spent_hours)) %>
 */ ?>
    </td>
</tr>
<?php endif ?>
</table>
</fieldset>
<?php endif ?>

<div id="status_by">
<%= render_issue_status_by(@version, params[:status_by]) if @version.fixed_issues.count > 0 %>
</div>
</div>

<div id="roadmap">
<?php echo $this->element('versions/overview') ?>
<?php echo $this->element('wiki/content') ?>
<?php /*
<%= render(:partial => "wiki/content", :locals => {:content => @version.wiki_page.content}) if @version.wiki_page %>
 */ ?>

<?php if (count($issues) > 0): ?>
<fieldset class="related-issues"><legend><%= l(:label_related_issues) %></legend>
<ul>
<?php foreach ($issues as $issue): ?>
    <li><%= link_to_issue(issue) %>: <?php echo h($issue['Issue']['subject']) ?></li>
<?php endforeach ?>
</ul>
</fieldset>
<?php endif ?>
</div>

<%= call_hook :view_versions_show_bottom, :version => @version %>

<?php $candy->html_title($this->data['Version']['name']) ?>

