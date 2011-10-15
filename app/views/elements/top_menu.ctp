<ul>
<?php
$menu_container = ClassRegistry::getObject('MenuContainer');
?>
<?php foreach($menu_container->getTopMenu($currentuser) as $item): ?>
	<li><?php echo $html->link(__($item['caption'],true),$item['url'],aa('class',$item['class'])) ?></li>
<?php endforeach; ?>
</ul>