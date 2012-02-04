<html>
<head>
<style>
body {
	background: transparent;
	padding: 0;
	margin: 0;
}
td.likebtn {
	border-collapse: collapse;
	display: table-cell;
	border:1px solid #CAD4E7;
	background-color: #ECEEF5;
	padding: 4px 5px;
	-webkit-border-radius:3px;
	font-size: 12px;
}
td.likecnt {
	border-collapse: collapse;
	display: table-cell;
	border:1px solid #CAD4E7;
	background-color: #FFFFFF;
	padding: 4px 5px;
	-webkit-border-radius:3px;
	font-size: 12px;
}
span.liketxt {
	padding-left: 18px;
	background: url(<?php echo $html->url('/cc_like_it/img/thumb_up.png')?>);
	background-repeat: no-repeat;
}
</style>
</head>
<body>
<table>
	<tr>
		<td class="likebtn">
<?php
$url = Router::url(array(
	'plugin' => 'cc_like_it',
	'controller' => 'home',
	'action' => 'like',
	'issue_id' => $this->params['named']['issue_id']
));
?>
<?php if ($liked == 0): ?>
			<a href="<?php echo $url;?>">
<span class="liketxt"><?php echo __('Like')?></span>
			</a>
<?php else: ?>
	<span class="liketxt"><?php echo __('Liked');?></span>
<?php endif; ?>
		</td>
		<td class="likecnt">
<span><?php echo $count;?></span>
		</td>
	</tr>
</table>
	</body>
</html>