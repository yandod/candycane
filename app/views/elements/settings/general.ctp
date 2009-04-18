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
<?php echo $form->select('date_format',array_map('date',$Settings->DATE_FORMATS)) ?>
<%= select_tag 'settings[date_format]', options_for_select( [[l(:label_language_based), '']] + Setting::DATE_FORMATS.collect {|f| [Date.today.strftime(f), f]}, Setting.date_format) %></p>

<p><label><?php __('Time format') ?></label>
<%= select_tag 'settings[time_format]', options_for_select( [[l(:label_language_based), '']] + Setting::TIME_FORMATS.collect {|f| [Time.now.strftime(f), f]}, Setting.time_format) %></p>

<p><label><?php __('Users display format') ?></label>
<%= select_tag 'settings[user_format]', options_for_select( @options[:user_format], Setting.user_format.to_s ) %></p>

<p><label><?php __('Attachment max. size') ?></label>
<?php echo $form->input('attachment_max_size',aa('value',$Settings->attachment_max_size,'size',6,'label',false,'div',false))?> KB</p>

<p><label><?php __('Objects per page options') ?></label>
<?php echo $form->input('per_page_options',aa('value',implode(',',$Settings->per_page_options),'size',20,'label',false,'div',false))?> <em><?php __('Multiple values allowed (comma separated).') ?></em></p>

<p><label><?php __('Days displayed on project activity') ?></label>
<%= text_field_tag 'settings[activity_days_default]', Setting.activity_days_default, :size => 6 %> <%= l(:label_day_plural) %></p>

<p><label><?php __('Host name and path') ?></label>
<%= text_field_tag 'settings[host_name]', Setting.host_name, :size => 60 %><br />
<em><%= l(:label_example) %>: <%= @guessed_host_and_path %></em></p>

<p><label><?php __('Protocol') ?></label>
<%= select_tag 'settings[protocol]', options_for_select(['http', 'https'], Setting.protocol) %></p>

<p><label><?php __('Text formatting') ?></label>
<%= select_tag 'settings[text_formatting]', options_for_select([[l(:label_none), "0"], *Redmine::WikiFormatting.format_names.collect{|name| [name, name]} ], Setting.text_formatting.to_sym) %></p>

<p><label><?php __('Wiki history compression') ?></label>
<%= select_tag 'settings[wiki_compression]', options_for_select( [[l(:label_none), 0], ["gzip", "gzip"]], Setting.wiki_compression) %></p>

<p><label><?php __('Feed content limit') ?></label>
<%= text_field_tag 'settings[feeds_limit]', Setting.feeds_limit, :size => 6 %></p>

<p><label><?php __('Max number of diff lines displayed') ?></label>
<%= text_field_tag 'settings[diff_max_lines_displayed]', Setting.diff_max_lines_displayed, :size => 6 %></p>

<p><label><?php __('Use Gravatar user icons') ?></label>
<%= check_box_tag 'settings[gravatar_enabled]', 1, Setting.gravatar_enabled? %><%= hidden_field_tag 'settings[gravatar_enabled]', 0 %></p>
</div>

<?php echo $form->submit(__('Save',true)) ?>
<?php echo $form->end(); ?>