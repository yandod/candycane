<?php

$vars = array(
'app_title' =>
  'CandyCane',
'app_subtitle' =>
  'Project management',
'welcome_text' =>
  '',
'login_required' =>
  '0',
'self_registration' =>
  '2',
'lost_password' =>
  '1',
'attachment_max_size' =>
  '5120',
'issues_export_limit' =>
  '500',
'activity_days_default' =>
  '30',
'per_page_options' =>
  '25,50,100',
'mail_from' =>
  'redmine@example.net',
'bcc_recipients' =>
  '1',
'plain_text_mail' =>
  '0',
'text_formatting' =>
  'textile',
'wiki_compression' =>
  '""',
'default_language' =>
  'en',
'host_name' =>
  'localhost:3000',
'protocol' =>
  'http',
'feeds_limit' =>
  '15',
'diff_max_lines_displayed' =>
  '1500',
'enabled_scm' =>
  array(
    'Subversion',
    'Darcs',
    'Mercurial',
    'Cvs',
    'Bazaar',
    'Git',
  ),
'autofetch_changesets' =>
  '1',
'sys_api_enabled' =>
  '0',
'commit_ref_keywords' =>
  'refs,references,IssueID',
'commit_fix_keywords' =>
  'fixes,closes',
'commit_fix_status_id' =>

  '0',
'commit_fix_done_ratio' =>
  '100',
'autologin' =>
  '0',/*
# date format
date_format' =>
  '''
time_format' =>
  '''
user_format' =>
  ':firstname_lastname
  format: symbol
cross_project_issue_relations' =>
  '0
notified_events' =>
  serialized: true
  '
  - issue_added
  - issue_updated
mail_handler_api_enabled' =>
  '0
mail_handler_api_key' =>
  '
issue_list_default_columns' =>
  serialized: true
  '
  - tracker
  - status
  - priority
  - subject
  - assigned_to
  - updated_on
display_subprojects_issues' =>
  '1
default_projects_public' =>
  '1
sequential_project_identifiers' =>
  '0
# encodings used to convert repository files content to UTF-8
# multiple values accepted, comma separated
repositories_encodings' =>
  '''
# encoding used to convert commit logs to UTF-8
commit_logs_encoding' =>
  ''UTF-8'
ui_theme' =>
  '''
emails_footer' =>
  '|-
    You have received this notification because you have either subscribed to it, or are involved in it.
    To change your notification preferences, please click here: http://hostname/my/account
gravatar_enabled:  
  '0
*/
);
return $vars;
?>