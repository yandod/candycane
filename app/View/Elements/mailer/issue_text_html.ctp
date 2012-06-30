<h1><?php echo $this->Html->link(
	"{$issue['Tracker']['name']} #{$issue['Issue']['id']} {$issue['Issue']['subject']}",
	$issue_url
); ?></h1>

<ul>
	<li><?php echo __('Author'); ?>: <?php echo $this->Candy->format_username($issue['Author']); ?></li>
	<li><?php echo __('Status'); ?>: <?php echo $issue['Status']['name']; ?></li>
	<li><?php echo __('Priority'); ?>: <?php echo $issue['Priority']['name']; ?></li>
	<li><?php echo __('Assigned To')?>: <?php echo $this->Candy->format_username($issue['AssignedTo']); ?></li>
	<li><?php echo __('Category')?>: <?php echo $issue['Category']['name']; ?></li>
	<li><?php echo __('Fixed Version')?>: <?php echo $issue['FixedVersion']['name']; ?></li>
<?php if(isset($issue['CustomValue']) && is_array($issue['CustomValue'])): ?>
	<?php foreach ($issue['CustomValue'] as $c): ?>
		<li><?php $c['CustomField']['name']; ?>: <?php echo $c['value']; ?></li>
	<?php endforeach; ?>　
<?php endif; ?>
</ul>

<?php echo nl2br(h($issue['Issue']['description'])); ?>