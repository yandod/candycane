<h2><?php echo __('Members') ?></h2>

<?php if (count($members_by_role) == 0): ?>
<p><i><?php echo __('No data to display') ?></i></p>
<?php endif ?>

<?php foreach($members_by_role as $key=>$members): ?>
<h3><?php echo h($key) ?></h3>
<ul>
<?php foreach($members as $key2=>$member): ?>
  <li><?php echo $this->Candy->link_to_user($member['User']) ?></li>
<?php endforeach ?>
</ul>
<?php endforeach ?>

