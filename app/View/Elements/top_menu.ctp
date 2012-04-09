<ul>
<?php
$menu_container = ClassRegistry::getObject('MenuContainer');
?>
<?php foreach($menu_container->getTopMenu($currentuser) as $item): ?>
	<li><?php echo $this->Html->link(__($item['caption']),$item['url'],array('class' => $item['class'])) ?></li>
<?php endforeach; ?>
</ul>