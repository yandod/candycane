<?php
if( $currentuser[ 'admin' ] )
{
	$this->set( 'Sidebar', $this->element( 'admin/sidebar' ) );
}
?>

<h2><?php echo $this->Candy->html_title(__('Settings')) ?></h2>

<?php $selected_tab = isset($this->request->params['named']['tab']) ? $this->request->params['named']['tab'] : 'general' ?>
<div class="tabs">
<ul>
<?php foreach($settings_tabs as $tab): ?>
<?php $selected = ($selected_tab == $tab['name']) ? "selected" : ""; ?>
    <li><?php echo $this->Html->link(
		$tab['label'],
		array(
			'tab' => $tab['name']
		),
		array(
			'id' => "tab-".$tab['name'],
			'class' => $selected,
			'onclick' => "showTab('{$tab['name']}'); this.blur(); return false;",
			'escape' => false
		)
	);?></li>
<?php endforeach; ?>
</ul>
</div>

<?php foreach($settings_tabs as $tab): ?>
<?php $disp = ($selected_tab !== $tab['name']) ? 'display:none' : ''; ?>
<?php echo $this->Html->tag('div',$this->element($tab['partial']),
	array(
		'id' => 'tab-content-'.$tab['name'],
		'style' => $disp,
		'class' => 'tab-content'
	)
); ?>
<?php endforeach; ?>

