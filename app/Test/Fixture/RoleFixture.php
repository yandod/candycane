<?php 
class RoleFixture extends CakeTestFixture {
  var $name = 'Role';
  var $fields = array(
      'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
      'name' => array('type' => 'string', 'null' => false, 'length' => 30),
      'position' => array('type' => 'integer', 'null' => true, 'default' => '1'),
      'assignable' => array('type' => 'boolean', 'null' => true, 'default' => '1'),
      'builtin' => array('type' => 'integer', 'null' => false, 'default' => '0'),
      'permissions' => array('type' => 'text', 'null' => true, 'default' => NULL),
      'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
  );
  var $records = array(
    array('name'=>"Manager", 'id'=>1, 'builtin'=>0, 'position'=>1, 'permissions'=>
"--- 
- :edit_project
- :manage_members
- :manage_versions
- :manage_categories
- :add_issues
- :edit_issues
- :edit_issue_notes
- :edit_own_issue_notes
- :manage_issue_relations
- :add_issue_notes
- :move_issues
- :delete_issues
- :view_issue_watchers
- :add_issue_watchers
- :manage_public_queries
- :save_queries
- :view_gantt
- :view_calendar
- :log_time
- :view_time_entries
- :edit_time_entries
- :delete_time_entries
- :manage_news
- :comment_news
- :view_documents
- :manage_documents
- :view_wiki_pages
- :view_wiki_edits
- :edit_wiki_pages
- :delete_wiki_pages_attachments
- :protect_wiki_pages
- :delete_wiki_pages
- :rename_wiki_pages
- :add_messages
- :edit_messages
- :delete_messages
- :manage_boards
- :view_files
- :manage_files
- :browse_repository
- :manage_repository
- :view_changesets"),
    array('name'=>"Developer", 'id'=>2, 'builtin'=>0, 'position'=>2, 'permissions'=>
"--- 
- :edit_project
- :manage_members
- :manage_versions
- :manage_categories
- :add_issues
- :edit_issues
- :manage_issue_relations
- :add_issue_notes
- :move_issues
- :delete_issues
- :view_issue_watchers
- :save_queries
- :view_gantt
- :view_calendar
- :log_time
- :view_time_entries
- :edit_own_time_entries
- :manage_news
- :comment_news
- :view_documents
- :manage_documents
- :view_wiki_pages
- :view_wiki_edits
- :edit_wiki_pages
- :protect_wiki_pages
- :delete_wiki_pages
- :add_messages
- :edit_own_messages
- :delete_own_messages
- :manage_boards
- :view_files
- :manage_files
- :browse_repository
- :view_changesets"),
    array('name'=>"Reporter", 'id'=>3, 'builtin'=>0, 'position'=>3, 'permissions'=>
"--- 
- :edit_project
- :manage_members
- :manage_versions
- :manage_categories
- :add_issues
- :edit_issues
- :manage_issue_relations
- :add_issue_notes
- :move_issues
- :view_issue_watchers
- :save_queries
- :view_gantt
- :view_calendar
- :log_time
- :view_time_entries
- :manage_news
- :comment_news
- :view_documents
- :manage_documents
- :view_wiki_pages
- :view_wiki_edits
- :edit_wiki_pages
- :delete_wiki_pages
- :add_messages
- :manage_boards
- :view_files
- :manage_files
- :browse_repository
- :view_changesets"),
    array('name'=>"Non member", 'id'=>4, 'builtin'=>1, 'position'=>4, 'permissions'=>
"--- 
- :add_issues
- :edit_issues
- :manage_issue_relations
- :add_issue_notes
- :move_issues
- :save_queries
- :view_gantt
- :view_calendar
- :log_time
- :view_time_entries
- :comment_news
- :view_documents
- :manage_documents
- :view_wiki_pages
- :view_wiki_edits
- :edit_wiki_pages
- :add_messages
- :view_files
- :manage_files
- :browse_repository
- :view_changesets"),
    array('name'=>"Anonymous", 'id'=>5, 'builtin'=>2, 'position'=>5, 'permissions'=>
"--- 
- :add_issue_notes
- :view_gantt
- :view_calendar
- :view_time_entries
- :view_documents
- :view_wiki_pages
- :view_wiki_edits
- :view_files
- :browse_repository
- :view_changesets")
  );
}
