<h2><?php echo __('Bulk edit selected issues') ?></h2>

<ul><?php foreach($_issues as $i): ?>
	<li><?php echo $this->Html->link(h("{$i['Tracker']['name']} #{$i['Issue']['id']}"), array('action' => 'show', 'id' => $i['Issue']['id'] )).h(": {$i['Issue']['subject']}"); ?></li>
<?php endforeach; ?>
</ul>

<?php echo $this->Form->create('Issue', array('action'=>'bulk_edit'));?>
<?php foreach($_issues as $i) { echo $this->Form->hidden('ids', array('name'=>'data[Issue][ids][]', 'value'=>$i['Issue']['id'])); } ?>
<div class="box">
<fieldset>
<legend><?php echo __('Change properties') ?></legend>
<p>
<?php if (count($available_statuses) > 0): ?>
<label><?php echo __('Status') ?>: 
<?php echo $this->Form->select(
	'status_id', 
	$this->Candy->options_from_collection_for_select($available_statuses, 'Status', 'id', 'name'), 
	isset($this->request->params['url']['status_id']) ? $this->request->params['url']['status_id'] : null,
	array(),
	__('(No change)')); ?>
</label>
<?php endif; ?>
<label><?php echo __('Priority') ?>: 
<?php echo $this->Form->select(
	'priority_id', 
	$this->Candy->options_from_collection_for_select($priorities, 'Priority', 'id', 'name'), 
	isset($this->request->params['url']['priority_id']) ? $this->request->params['url']['priority_id'] : null,
	array(),
	__('(No change)')); ?>
</label>
<label><?php echo __('Category') ?>: 
<?php echo $this->Form->select(
	'category_id', 
	array('none' => __('none')) + $issueCategories, 
	isset($this->request->params['url']['category_id']) ? $this->request->params['url']['category_id'] : null,
	array(),
	__('(No change)')); ?>
</label>
</p>
<p>
<label><?php echo __('Assigned to') ?>: 
<?php echo $this->Form->select(
	'assigned_to_id', 
	array('none' => __('nobody')) + $assignableUsers, 
	isset($this->request->params['url']['assigned_to_id']) ? $this->request->params['url']['assigned_to_id'] : null,
	array(),
	__('(No change)')); ?>
</label>
<label><?php echo __('Target version') ?>: 
<?php echo $this->Form->select(
	'fixed_version_id', 
	array('none' => __('none')) + $fixedVersions, 
	isset($this->request->params['url']['fixed_version_id']) ? $this->request->params['url']['fixed_version_id'] : null,
	array(),
	__('(No change)')); ?>
</label>
</p>

<p>
<label><?php echo __('Start') ?>: 
<?php echo $this->Form->text('start_date', array('value'=>'', 'size' => 10)); echo $this->Candy->calendar_for('IssueStartDate'); ?></label>
<label><?php echo __('Due date') ?>: 
<?php echo $this->Form->text('due_date', array('value'=>'', 'size' => 10)); echo $this->Candy->calendar_for('IssueDueDate');?></label>
<label><?php echo __('%% Done') ?>: 
<?php 
$done_ratios = array();
for($i = 0;$i<=10;$i++) {$done_ratios[$i] = sprintf('%d %%', $i*10);}
echo $this->Form->select(
	'done_ratio', 
	$done_ratios, 
	isset($this->request->params['url']['done_ratio']) ? $this->request->params['url']['done_ratio'] : null,
	array(),
	__('(No change)')); ?>
</label>
</p>
<?php /* call_hook(:view_issues_bulk_edit_details_bottom, { :issues => @issues }) */ ?>
</fieldset>

<fieldset><legend><?php echo __('Notes') ?></legend>
<?php echo $this->Form->textarea('notes', array('cols' => 60, 'rows' => 10, 'class' => 'wiki-edit', 'id'=>'notes')); ?>
    <script src="/js/jstoolbar/jstoolbar.js?1236399204" type="text/javascript"></script><script src="/js/jstoolbar/textile.js?1236399204" type="text/javascript"></script><script src="/js/jstoolbar/lang/jstoolbar-ja.js?1236399204" type="text/javascript"></script><script type="text/javascript">
//<![CDATA[
var toolbar = new jsToolBar($('notes')); toolbar.setHelpLink('<?php echo __("Text formatting");?>: <a href="/help/wiki_syntax.html?1236399200" onclick="window.open(&quot;/help/wiki_syntax.html?1236399200&quot;, &quot;&quot;, &quot;resizable=yes, location=no, width=300, height=640, menubar=no, status=no, scrollbars=yes&quot;); return false;"><?php echo __("Help"); ?></a>'); toolbar.draw();
//]]>
</script>
</fieldset>
</div>

<p><?php echo $this->Form->submit(__('Submit')); ?>
<?php echo $this->Form->end(); ?>
