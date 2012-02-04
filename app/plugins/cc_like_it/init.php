<?php
$pluginContainer = ClassRegistry::getObject('PluginContainer');
$pluginContainer->installed('cc_like_it','0.1');

$hookContainer = ClassRegistry::getObject('HookContainer');
$hookContainer->registerElementHook(
	'issues/show_details_bottom', // target element name.
	'../../plugins/cc_like_it/views/elements/hook', // additional template you want to inject.
	false // it should be true when you want to inject before the target template.
);


