<h2><?php echo $candy->html_title(__('Settings',true)) ?></h2>

<div class="tabs">
<ul>
<?php foreach($tabs as $tab): ?>
<?php $selected = ($selected_tab == $tab['name']) ? "selected" : ""; ?>
    <li><?php echo $html->link($tab['label'],aa('tab',$tab['name']),
                                     aa('id', "tab-".$tab['name'],
                                     'class',$selected,
                                     'onclick', "showTab('{$tab['name']}'); this.blur(); return false;",
                                     'escape', false
                                     )) ?></li>
<?php endforeach; ?>
</ul>
</div>

<?php foreach($tabs as $tab): ?>
<?php $disp = ($selected_tab !== $tab['name']) ? 'display:none' : ''; ?>
<?php echo $html->tag('div',$this->renderElement($tab['partial']),
						aa('id','tab-content-'.$tab['name'],
						   'style', $disp,
						   'class', 'tab-content'
						)
) ?>
<?php endforeach; ?>
