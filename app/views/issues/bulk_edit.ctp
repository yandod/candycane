<h2><?php __('Bulk edit selected issues') ?></h2>

<ul><?php foreach($_issues as $i): ?>
	<li><?php echo $html->link(h("{$i['Tracker']['name']} #{$i['Issue']['id']}"), array('action' => 'show', 'id' => $i['Issue']['id'] )).h(": {$i['Issue']['subject']}"); ?></li>
<?php endforeach; ?>
</ul>

<?php echo $form->create('Issue', array('action'=>'bulk_edit'));?>
<?php foreach($_issues as $i) { echo $form->hidden('ids', array('name'=>'data[Issue][ids][]', 'value'=>$i['Issue']['id'])); } ?>
<div class="box">
<fieldset>
<legend><?php __('Change properties') ?></legend>
<p>
<?php if (count($available_statuses) > 0): ?>
<label><?php __('Status') ?>: 
<?php echo $form->select(
	'status_id', 
	$candy->options_from_collection_for_select($available_statuses, 'Status', 'id', 'name'), 
	null,
	array(),
	__('(No change)',true)); ?>
</label>
<?php endif; ?>
<label><?php __('Priority') ?>: 
<?php echo $form->select(
	'priority_id', 
	$candy->options_from_collection_for_select($priorities, 'Priority', 'id', 'name'), 
	null,
	array(),
	__('(No change)',true)); ?>
</label>
<label><?php __('Category') ?>: 
<?php echo $form->select(
	'category_id', 
	array('none' => __('none',true)) + $issueCategories, 
	null,
	array(),
	__('(No change)',true)); ?>
</label>
</p>
<p>
<label><?php __('Assigned to') ?>: 
<?php echo $form->select(
	'assigned_to_id', 
	array('none' => __('nobody',true)) + $assignableUsers, 
	null,
	array(),
	__('(No change)',true)); ?>
</label>
<label><?php __('Target version') ?>: 
<?php echo $form->select(
	'fixed_version_id', 
	array('none' => __('none',true)) + $fixedVersions, 
	null,
	array(),
	__('(No change)',true)); ?>
</label>
</p>

<p>
<label><?php __('Start') ?>: 
<?php echo $form->text('start_date', array('value'=>'', 'size' => 10)); echo $candy->calendar_for('IssueStartDate'); ?></label>
<label><?php __('Due date') ?>: 
<?php echo $form->text('due_date', array('value'=>'', 'size' => 10)); echo $candy->calendar_for('IssueDueDate');?></label>
<label><?php __('%% Done') ?>: 
<?php 
$done_ratios = array();
for($i = 0;$i<=10;$i++) {$done_ratios[$i] = sprintf('%d %%', $i*10);}
echo $form->select(
	'done_ratio', 
	$done_ratios, 
	null,
	array(),
	__('(No change)',true)); ?>
</label>
</p>
<?php /* call_hook(:view_issues_bulk_edit_details_bottom, { :issues => @issues }) */ ?>
</fieldset>

<fieldset><legend><?php __('Notes') ?></legend>
<?php echo $form->textarea('notes', array('cols' => 60, 'rows' => 10, 'class' => 'wiki-edit', 'id'=>'notes')); ?>
    <script src="/js/jstoolbar/jstoolbar.js?1236399204" type="text/javascript"></script><script src="/js/jstoolbar/textile.js?1236399204" type="text/javascript"></script><script src="/js/jstoolbar/lang/jstoolbar-ja.js?1236399204" type="text/javascript"></script><script type="text/javascript">
//<![CDATA[
var toolbar = new jsToolBar($('notes')); toolbar.setHelpLink('<?php __("Text formatting");?>: <a href="/help/wiki_syntax.html?1236399200" onclick="window.open(&quot;/help/wiki_syntax.html?1236399200&quot;, &quot;&quot;, &quot;resizable=yes, location=no, width=300, height=640, menubar=no, status=no, scrollbars=yes&quot;); return false;"><?php __("Help"); ?></a>'); toolbar.draw();
//]]>
</script>
</fieldset>
</div>

<p><?php echo $form->submit(__('Submit',true)); ?>
<?php echo $form->end(); ?>
