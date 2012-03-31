<?php echo sprintf(
	__('A new user (%s) has registered. The account is pending your approval:',true),
	$user['User']['login']
)?>
<?php echo $url ?>
