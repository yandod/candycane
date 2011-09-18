<ul>
	<li><?php echo $html->link(__('Home',true),'/',aa('class','home')) ?></li>
	<?php if ($currentuser['logged']): ?>
		<li><?php echo $html->link(__('My page',true),'/my/page',aa('class','my-page')) ?></li>
	<?php endif; ?>
	<li><?php echo $html->link(__('Projects',true),'/projects',aa('class','projects')) ?></li>
	<?php if (isset($currentuser['admin']) && $currentuser['admin']): ?>
		<li><?php echo $html->link(__('Administration',true), '/admin', aa('class','administration')) ?></li>
	<?php endif; ?>
	<li><a href="https://groups.google.com/group/candycane-users" class="help"><?php __('Help') ?></a></li>
</ul>