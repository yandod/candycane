<?php
App::uses('ClassRegistry','Utility');
$ui = ClassRegistry::getObject('Setting')->ui_theme;
if ( !empty( $ui ) )
{
	$css = '/themed/' . $ui . '/css/application.css';
	echo $this->Html->css( $css );
}