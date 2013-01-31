<h3><?php echo __('Wiki') ?></h3>

<?php echo $this->Html->link(
	__('Start page'),
	array(
		'action' => 'index',
		'project_id' => $main_project['Project']['identifier'],
		'wikipage' => null
	)
) ?><br />
<?php echo $this->Html->link(
	__('Index by title'),
	array(
		'action' => 'special',
		'project_id' => $main_project['Project']['identifier'],
		'wikipage' => 'Page_index'
	)
) ?><br />
<?php echo $this->Html->link(
	__('Index by date'),
	array(
		'action' => 'special',
		'project_id' => $main_project['Project']['identifier'],
		'wikipage' => 'Date_index'
	)
) ?><br />
