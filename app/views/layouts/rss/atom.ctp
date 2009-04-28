<?php
echo header("application/atom+xml");
echo $xml->header();
echo $content_for_layout;
?>