<p><?php echo sprintf(
	__('A new user (%s) has registered. The account is pending your approval:'),
	$user['User']['login']
); ?></p>
<p><?php echo $this->Html->link($url); ?></p>