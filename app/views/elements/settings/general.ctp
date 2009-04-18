<?php echo $form->create(null,aa('action','edit')) ?>
<div class="box tabular settings">
<p><label><?php __('Application title')?></label>
<?php echo $form->input('app_title',aa('value',$Settings->app_title,'size',30,'label',false,'div',false))?></p>
<p><label><?php __('Welcome text') ?></label>
<?php echo $form->textarea('welcome_text',aa('value',$Settings->welcome_text,'cols',60,'rows',5,'class','wiki-edit')) ?></p>
<!-- <%= wikitoolbar_for 'settings[welcome_text]' %> -->

<p><label><?php __('Theme') ?></label>
<?php echo $form->select('ui_themes',$themes,$Settings->ui_theme,array(),__('Default',true)) ?></p>

<p><label><?php __('Default language') ?></label>
<?php echo $form->select('default_language',$candy->lang_options_for_select(),$Settings->default_language) ?></p>

<p><label><?php __('Date format') ?></label>
<?php echo $form->select('date_format',array_map('date',$Settings->DATE_FORMATS),$Settings->date_format,array(),__("Based on user's language",true)) ?></p>

<p><label><?php __('Time format') ?></label>
<?php echo $form->select('time_format',array_map('date',$Settings->TIME_FORMATS),$Settings->time_format,array(),__("Based on user's language",true)) ?></p>

<p><label><?php __('Users display format') ?></label>
<?php
$example_user_format = array();
foreach ($Settings->USER_FORMATS as $k => $v) {
  $example_user_format[$k] = sprintf($v,$currentuser['firstname'],$currentuser['lastname'],$currentuser['login']);
}
?>
<?php echo $form->select('user_format',$example_user_format,$Settings->user_format,array(),null) ?></p>

<p><label><?php __('Attachment max. size') ?></label>
<?php echo $form->input('attachment_max_size',aa('value',$Settings->attachment_max_size,'size',6,'label',false,'div',false))?> KB</p>

<p><label><?php __('Objects per page options') ?></label>
<?php echo $form->input('per_page_options',aa('value',$Settings->per_page_options,'size',20,'label',false,'div',false))?> <em><?php __('Multiple values allowed (comma separated).') ?></em></p>

<p><label><?php __('Days displayed on project activity') ?></label>
<?php echo $form->input('activity_days_default',aa('value',$Settings->activity_days_default,'size',6,'label',false,'div',false))?> <?php __('days') ?></p>

<p><label><?php __('Host name and path') ?></label>
<?php echo $form->input('host_name',aa('value',$Settings->host_name,'size',60,'label',false,'div',false))?><br/>
<em><?php __('Example') ?>: <?php e(h(env('SERVER_NAME'))) ?>:<?php e(h(env('SERVER_PORT'))) ?></em></p>

<p><label><?php __('Protocol') ?></label>
<?php echo $form->select('protocol',aa('http','http','https','https'),$Settings->protocol,array(),null) ?></p>

<p><label><?php __('Text formatting') ?></label>
<?php echo $form->select('text_formatting',$text_formattings,$Settings->text_formatting,array(),__('none',true)) ?></p>

<p><label><?php __('Wiki history compression') ?></label>
<?php echo $form->select('wiki_compression',aa('gzip','gzip'),$Settings->wiki_compression,array(),aa(0,__('none',true))) ?></p>

<p><label><?php __('Feed content limit') ?></label>
<?php echo $form->input('feeds_limit',aa('value',$Settings->feeds_limit,'size',6,'label',false,'div',false))?></p>

<p><label><?php __('Max number of diff lines displayed') ?></label>
<?php echo $form->input('diff_max_lines_displayed',aa('value',$Settings->diff_max_lines_displayed,'size',6,'label',false,'div',false))?></p>

<p><label><?php __('Use Gravatar user icons') ?></label>
<?php echo $form->input('gravatar_enabled', array('type' => 'checkbox', 'options' => array(1), 'div' => false,'label' => false)); ?></p>
</div>

<?php echo $form->submit(__('Save',true)) ?>
<?php echo $form->end(); ?>