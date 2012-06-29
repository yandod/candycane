<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
	<title><?php echo $title_for_layout;?></title>
	<style>
		body {
			font-family: Verdana, sans-serif;
			font-size: 0.8em;
			color:#484848;
		}
		h1, h2, h3 { font-family: "Trebuchet MS", Verdana, sans-serif; margin: 0px; }
		h1 { font-size: 1.2em; }
		h2, h3 { font-size: 1.1em; }
		a, a:link, a:visited { color: #2A5685;}
		a:hover, a:active { color: #c61a1a; }
		a.wiki-anchor { display: none; }
		hr {
			width: 100%;
			height: 1px;
			background: #ccc;
			border: 0;
		}
		.footer {
			font-size: 0.8em;
			font-style: italic;
		}
	</style>
</head>

<body>
	<?php echo $content_for_layout;?>

	<hr>
	<span class="footer"><?php echo $footer ?></span>
</body>
</html>