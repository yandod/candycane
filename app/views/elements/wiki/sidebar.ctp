<h3><?php __('Wiki') ?></h3>

<?php echo $html->link(__('Start page', true), aa('action', 'index', 'page', null)) ?><br />
<?php echo $html->link(__('Index by title',true), aa('action', 'special', 'page', 'Page_index')) ?><br />
<?php echo $html->link(__('Index by date',true), aa('action', 'special', 'page', 'Date_index')) ?><br />
