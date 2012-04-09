<?php
echo header("Content-Type: application/atom+xml");
echo $xml->header();
echo $content_for_layout;
?>