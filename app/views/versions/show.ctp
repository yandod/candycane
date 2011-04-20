<div class="contextual">
<?php echo $candy->link_to_if_authorized(aa('controller','versions','action','edit'), __('Edit',true), 'edit/'.$this->data['Version']['id'], aa('class', 'icon icon-edit', 'onclick', 'Element.show("edit-news"); return false;')) ?>
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
    </td>
</tr>
<?php if (true): ?>
<?php /*
<% if User.current.allowed_to?(:view_time_entries, @project) %>
 */ ?>
<tr>
    <td width="130px" align="right"><?php echo __('Spent time') ?></td>
    <td width="240px" class="total-hours">
      <?php echo $candy->html_hours($candy->lwr('%.2f hour', $this->data['Version']['spent_hours'])) ?>
    </td>
</tr>
<?php endif ?>
</table>
</fieldset>
<?php endif ?>

<div id="status_by">
<?php if ($fixed_issue_count > 0): ?>
<?php /*
<%= render_issue_status_by(@version, params[:status_by]) %>
 */ ?>
<?php endif ?>
</div>
</div>

<div id="roadmap">
<?php echo $this->element('versions/overview', array('version'=>$this->data['Version'])) ?>
<?php echo $this->renderElement('wiki/content', aa('content', $wiki_content)); ?>
<?php /*
<%= render(:partial => "wiki/content", :locals => {:content => @version.wiki_page.content}) if @version.wiki_page %>
 */ ?>

<?php if (count($issues) > 0): ?>
<fieldset class="related-issues"><legend><?php __('Related issues') ?></legend>
<ul>
<?php foreach ($issues as $issue): ?>
    <li><?php echo $candy->link_to_issue($issue) ?>: <?php echo h($issue['FixedIssue']['subject']) ?></li>
<?php endforeach ?>
</ul>
</fieldset>
<?php endif ?>
</div>

<?php /*
<%= call_hook :view_versions_show_bottom, :version => @version %>
 */ ?>

<?php $candy->html_title($this->data['Version']['name']) ?>

