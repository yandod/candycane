<?php echo $this->Form->create('Setting',aa('action','edit')) ?>
<div class="box tabular settings">
<p><label><?php echo __('Application title')?></label>
<?php echo $this->Form->input('app_title',aa('value',$Settings->app_title,'size',30,'label',false,'div',false))?></p>
<p><label><?php echo __('Welcome text') ?></label>
<?php echo $this->Form->textarea('welcome_text',aa('value',$Settings->welcome_text,'cols',60,'rows',5,'class','wiki-edit')) ?></p>
<!-- <%= wikitoolbar_for 'settings[welcome_text]' %> -->

<p><label><?php echo __('Theme') ?></label>
<?php echo $this->Form->select('ui_theme',$themes,$Settings->ui_theme,array(),__('Default')) ?></p>

<p><label><?php echo __('Default language') ?></label>
<?php echo $this->Form->select('default_language',$this->Candy->lang_options_for_select(),$Settings->default_language,array(),false) ?></p>

<p><label><?php echo __('Date format') ?></label>
<?php echo $this->Form->select('date_format',array_map('date',$Settings->DATE_FORMATS),$Settings->date_format,array(),__("Based on user's language")) ?></p>

<p><label><?php echo __('Time format') ?></label>
<?php echo $this->Form->select('time_format',array_map('date',$Settings->TIME_FORMATS),$Settings->time_format,array(),__("Based on user's language")) ?></p>

<p><label><?php echo __('Users display format') ?></label>
<?php
$example_user_format = array();
foreach ($Settings->USER_FORMATS as $k => $v) {
  $example_user_format[$k] = sprintf($v,$currentuser['firstname'],$currentuser['lastname'],$currentuser['login']);
}
?>
<?php echo $this->Form->select('user_format',$example_user_format,$Settings->user_format,array(),null) ?></p>

<p><label><?php echo __('Attachment max. size') ?></label>
<?php echo $this->Form->input('attachment_max_size',aa('value',$Settings->attachment_max_size,'size',6,'label',false,'div',false))?> KB</p>

<p><label><?php echo __('Objects per page options') ?></label>
<?php echo $this->Form->input('per_page_options',aa('value',implode(",",$Settings->per_page_options),'size',20,'label',false,'div',false))?> <em><?php echo __('Multiple values allowed (comma separated).') ?></em></p>

<p><label><?php echo __('Days displayed on project activity') ?></label>
<?php echo $this->Form->input('activity_days_default',aa('value',$Settings->activity_days_default,'size',6,'label',false,'div',false))?> <?php echo __('days') ?></p>

<p><label><?php echo __('Host name and path') ?></label>
<?php echo $this->Form->input('host_name',aa('value',$Settings->host_name,'size',60,'label',false,'div',false))?><br/>
<em><?php echo __('Example') ?>: <?php echo h(env('SERVER_NAME'))) ?>:<?php e(h(env('SERVER_PORT')) ?></em></p>

<p><label><?php echo __('Protocol') ?></label>
<?php echo $this->Form->select('protocol',aa('http','http','https','https'),$Settings->protocol,array(),null) ?></p>

<p><label><?php echo __('Text formatting') ?></label>
<?php echo $this->Form->select('text_formatting',$text_formattings,$Settings->text_formatting,array(),__('none')) ?></p>

<p><label><?php echo __('Wiki history compression') ?></label>
<?php echo $this->Form->select('wiki_compression',aa('gzip','gzip'),$Settings->wiki_compression,array(),aa(0,__('none'))) ?></p>

<p><label><?php echo __('Feed content limit') ?></label>
<?php echo $this->Form->input('feeds_limit',aa('value',$Settings->feeds_limit,'size',6,'label',false,'div',false))?></p>

<p><label><?php echo __('Max number of diff lines displayed') ?></label>
<?php echo $this->Form->input('diff_max_lines_displayed',aa('value',$Settings->diff_max_lines_displayed,'size',6,'label',false,'div',false))?></p>

<p><label><?php echo __('Use Gravatar user icons') ?></label>
<?php echo $this->Form->checkbox('gravatar_enabled',aa('checked', ($Settings->gravatar_enabled == '1'))); ?></p>
</div>

<?php echo $this->Form->submit(__('Save')) ?>
<?php echo $this->Form->end(); ?>