<?php echo $this->Form->create('Setting',array('action' => 'edit')) ?>
<div class="box tabular settings">
<p><label><?php echo __('Application title')?></label>
<?php echo $this->Form->input(
	'app_title',
	array(
		'value' => $Settings->app_title,
		'size' => 30,
		'label' => false,
		'div' => false
	)
);?></p>
<p><label><?php echo __('Welcome text') ?></label>
<?php echo $this->Form->textarea(
	'welcome_text',
	array(
		'value' => $Settings->welcome_text,
		'cols' => 60,
		'rows' => 5,
		'class' => 'wiki-edit'
	)
); ?></p>
<!-- <%= wikitoolbar_for 'settings[welcome_text]' %> -->

<p><label><?php echo __('Theme') ?></label>
<?php echo $this->Form->select(
	'ui_theme',
	$themes,
	array(
		'value' => $Settings->ui_theme,
		'empty' => 	__('Default')
	)
); ?></p>

<p><label><?php echo __('Default language') ?></label>
<?php echo $this->Form->select(
	'default_language',
	$this->Candy->lang_options_for_select(),
	array(
		'value' => 	$Settings->default_language
	)
); ?></p>

<p><label><?php echo __('Date format') ?></label>
<?php echo $this->Form->select(
	'date_format',
	array_combine($Settings->DATE_FORMATS, array_map('strftime', $Settings->DATE_FORMATS)),
	array(
		'value' => 	$Settings->date_format,
		'empty' => 	__("Based on user's language")
	)
); ?></p>

<p><label><?php echo __('Time format') ?></label>
<?php echo $this->Form->select(
	'time_format',
	array_combine($Settings->TIME_FORMATS, array_map('strftime', $Settings->TIME_FORMATS)),
	array(
		'value' => $Settings->time_format,
		'empty' => __("Based on user's language")
	)
); ?></p>

<p><label><?php echo __('Users display format') ?></label>
<?php
$example_user_format = array();
foreach ($Settings->USER_FORMATS as $k => $v) {
	$example_user_format[$k] = sprintf(
		$v,
		$currentuser['firstname'],
		$currentuser['lastname'],
		$currentuser['login']
	);
} ?>
<?php echo $this->Form->select(
	'user_format',
	$example_user_format,
	array(
		'value' => $Settings->user_format,
	)
); ?></p>

<p><label><?php echo __('Attachment max. size') ?></label>
<?php echo $this->Form->input(
	'attachment_max_size',
	array(
		'value' => $Settings->attachment_max_size,
		'size' => 6,
		'label' => false,
		'div' => false
	)
);?> KB</p>

<p><label><?php echo __('Objects per page options') ?></label>
<?php echo $this->Form->input(
	'per_page_options',
	array(
		'value' => implode(",",$Settings->per_page_options),
		'size' => 20,
		'label' => false,
		'div' => false
	)
); ?>
<em><?php echo __('Multiple values allowed (comma separated).') ?></em></p>

<p><label><?php echo __('Days displayed on project activity') ?></label>
<?php echo $this->Form->input(
	'activity_days_default',
	array(
		'value' => $Settings->activity_days_default,
		'size' => 6,
		'label' => false,
		'div' => false
	)
);?> <?php echo __('days') ?></p>

<p><label><?php echo __('Host name and path') ?></label>
<?php echo $this->Form->input(
	'host_name',
	array(
		'value' => $Settings->host_name,
		'size' => 60,
		'label' => false,
		'div' => false
	)
);?><br/>
<em><?php echo __('Example') ?>: <?php echo h(env('SERVER_NAME')); ?>:<?php echo h(env('SERVER_PORT')) ?></em></p>

<p><label><?php echo __('Protocol') ?></label>
<?php echo $this->Form->select(
	'protocol',
	array(
		'http' => 'http',
		'https' => 'https'
	),
	array(
		'value' => $Settings->protocol
	)
); ?></p>

<p><label><?php echo __('Text formatting') ?></label>
<?php echo $this->Form->select(
	'text_formatting',
	$text_formattings,
	
	array(
		'value' => $Settings->text_formatting,
		'empty' => __('none')
	)
); ?></p>

<p><label><?php echo __('Wiki history compression') ?></label>
<?php echo $this->Form->select(
	'wiki_compression',
	array(
		'gzip' => 'gzip'
	),
	array(
		'value' => $Settings->wiki_compression,
		'empty' => __('none')
	)
); ?></p>

<p><label><?php echo __('Feed content limit') ?></label>
<?php echo $this->Form->input(
	'feeds_limit',
	array(
		'value' => $Settings->feeds_limit,
		'size' => 6,
		'label' => false,
		'div' => false
	)
);?></p>

<p><label><?php echo __('Max number of diff lines displayed') ?></label>
<?php echo $this->Form->input(
	'diff_max_lines_displayed',
	array(
		'value' => $Settings->diff_max_lines_displayed,
		'size' => 6,
		'label' => false,
		'div' => false
	)
);?></p>

<p><label><?php echo __('Use Gravatar user icons') ?></label>
<?php echo $this->Form->checkbox(
	'gravatar_enabled',
	array(
		'checked' => ($Settings->gravatar_enabled == '1')
	)
); ?></p>
</div>

<?php echo $this->Form->submit(__('Save')) ?>
<?php echo $this->Form->end(); ?>
