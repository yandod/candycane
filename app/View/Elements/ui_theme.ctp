<?php
$Setting = ClassRegistry::getObject('Setting');
if ($Setting) {
  $ui = $Setting->ui_theme;
}

if ( !empty( $ui ) )
{
	$css = '/themed/' . $ui . '/css/application.css';
	echo $this->Html->css( $css );
}