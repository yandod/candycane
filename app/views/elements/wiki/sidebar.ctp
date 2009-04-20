<h3><?php __('Wiki') ?></h3>

<?php echo $html->link(__('Start page', true), aa('action', 'index', 'project_id', $main_project['Project']['identifier'], 'wikipage', null)) ?><br />
<?php echo $html->link(__('Index by title',true), aa('action', 'special', 'project_id', $main_project['Project']['identifier'], 'wikipage', 'Page_index')) ?><br />
<?php echo $html->link(__('Index by date',true), aa('action', 'special', 'project_id', $main_project['Project']['identifier'], 'wikipage', 'Date_index')) ?><br />
