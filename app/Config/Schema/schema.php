<?php 
class AppSchema extends CakeSchema {

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $attachments = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'container_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'container_type' => array('type' => 'string', 'null' => false, 'length' => 30, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'filename' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'disk_filename' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'filesize' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'content_type' => array('type' => 'string', 'null' => true, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'digest' => array('type' => 'string', 'null' => false, 'length' => 40, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'downloads' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'author_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'created_on' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'description' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $auth_sources = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'type' => array('type' => 'string', 'null' => false, 'length' => 30, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'length' => 60, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'host' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 60, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'port' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'account' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'account_password' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 60, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'base_dn' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'attr_login' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 30, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'attr_firstname' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 30, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'attr_lastname' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 30, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'attr_mail' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 30, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'onthefly_register' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'tls' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $boards = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'project_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'name' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'position' => array('type' => 'integer', 'null' => true, 'default' => '1'),
		'topics_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'messages_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'last_message_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'boards_project_id' => array('column' => 'project_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $changes = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'changeset_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'action' => array('type' => 'string', 'null' => false, 'length' => 1, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'path' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'from_path' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'from_revision' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'revision' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'branch' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'changesets_changeset_id' => array('column' => 'changeset_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $changesets = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'repository_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'revision' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'committer' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'committed_on' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'comments' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'commit_date' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'scmid' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'changesets_repos_rev' => array('column' => array('repository_id', 'revision'), 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $changesets_issues = array(
		'changeset_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'issue_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'indexes' => array('changesets_issues_ids' => array('column' => array('changeset_id', 'issue_id'), 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $comments = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'commented_type' => array('type' => 'string', 'null' => false, 'length' => 30, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'commented_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'author_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'comments' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created_on' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'updated_on' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $custom_fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'type' => array('type' => 'string', 'null' => false, 'length' => 30, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'length' => 30, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'field_format' => array('type' => 'string', 'null' => false, 'length' => 30, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'possible_values' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'regexp' => array('type' => 'string', 'null' => true, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'min_length' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'max_length' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'is_required' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'is_for_all' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'is_filter' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'position' => array('type' => 'integer', 'null' => true, 'default' => '1'),
		'searchable' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'default_value' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $custom_fields_projects = array(
		'custom_field_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'project_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $custom_fields_trackers = array(
		'custom_field_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'tracker_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $custom_values = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'customized_type' => array('type' => 'string', 'null' => false, 'length' => 30, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'customized_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'custom_field_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'value' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'custom_values_customized' => array('column' => array('customized_type', 'customized_id'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $documents = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'project_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'category_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'title' => array('type' => 'string', 'null' => false, 'length' => 60, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created_on' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'documents_project_id' => array('column' => 'project_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $enabled_modules = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'project_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'enabled_modules_project_id' => array('column' => 'project_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $enumerations = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'opt' => array('type' => 'string', 'null' => false, 'length' => 4, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'length' => 30, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'position' => array('type' => 'integer', 'null' => true, 'default' => '1'),
		'is_default' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $issue_categories = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'project_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'name' => array('type' => 'string', 'null' => false, 'length' => 30, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'assigned_to_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'issue_categories_project_id' => array('column' => 'project_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $issue_relations = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'issue_from_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'issue_to_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'relation_type' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'delay' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $issue_statuses = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'length' => 30, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'is_closed' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'is_default' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'position' => array('type' => 'integer', 'null' => true, 'default' => '1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $issues = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'tracker_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'project_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'subject' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'due_date' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'category_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'status_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'assigned_to_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'priority_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'fixed_version_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'author_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'lock_version' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'created_on' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'updated_on' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'start_date' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'done_ratio' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'estimated_hours' => array('type' => 'float', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'issues_project_id' => array('column' => 'project_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $journal_details = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'journal_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'property' => array('type' => 'string', 'null' => false, 'length' => 30, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'prop_key' => array('type' => 'string', 'null' => false, 'length' => 30, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'old_value' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'value' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'journal_details_journal_id' => array('column' => 'journal_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $journals = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'journalized_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'journalized_type' => array('type' => 'string', 'null' => false, 'length' => 30, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'notes' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created_on' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'journals_journalized_id' => array('column' => array('journalized_id', 'journalized_type'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $members = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'project_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'role_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'created_on' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'mail_notification' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $news = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'project_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'title' => array('type' => 'string', 'null' => false, 'length' => 60, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'summary' => array('type' => 'string', 'null' => true, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'author_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'created_on' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'comments_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'news_project_id' => array('column' => 'project_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $projects = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'length' => 30, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'homepage' => array('type' => 'string', 'null' => true, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'is_public' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'projects_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'created_on' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'updated_on' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'identifier' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 20, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'status' => array('type' => 'integer', 'null' => false, 'default' => '1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $projects_trackers = array(
		'project_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'tracker_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array('projects_trackers_project_id' => array('column' => 'project_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $queries = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'project_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'name' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'filters' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'is_public' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'column_names' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $repositories = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'project_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'url' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'login' => array('type' => 'string', 'null' => true, 'length' => 60, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'password' => array('type' => 'string', 'null' => true, 'length' => 60, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'root_url' => array('type' => 'string', 'null' => true, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $roles = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'length' => 30, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'position' => array('type' => 'integer', 'null' => true, 'default' => '1'),
		'assignable' => array('type' => 'boolean', 'null' => true, 'default' => '1'),
		'builtin' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'permissions' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $settings = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'length' => 30, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'value' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'updated_on' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $time_entries = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'project_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'issue_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'hours' => array('type' => 'float', 'null' => false, 'default' => NULL),
		'comments' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'activity_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'spent_on' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'tyear' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'tmonth' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'tweek' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created_on' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'updated_on' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'time_entries_project_id' => array('column' => 'project_id', 'unique' => 0), 'time_entries_issue_id' => array('column' => 'issue_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $tokens = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'action' => array('type' => 'string', 'null' => false, 'length' => 30, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'value' => array('type' => 'string', 'null' => false, 'length' => 40, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created_on' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $trackers = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'length' => 30, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'is_in_chlog' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'position' => array('type' => 'integer', 'null' => true, 'default' => '1'),
		'is_in_roadmap' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $user_preferences = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'others' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'hide_mail' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'time_zone' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $users = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'login' => array('type' => 'string', 'null' => false, 'length' => 30, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'hashed_password' => array('type' => 'string', 'null' => false, 'length' => 40, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'firstname' => array('type' => 'string', 'null' => false, 'length' => 30, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'lastname' => array('type' => 'string', 'null' => false, 'length' => 30, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'mail' => array('type' => 'string', 'null' => false, 'length' => 60, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'mail_notification' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'admin' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'status' => array('type' => 'integer', 'null' => false, 'default' => '1'),
		'last_login_on' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'language' => array('type' => 'string', 'null' => true, 'length' => 5, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'auth_source_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'created_on' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'updated_on' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $versions = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'project_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'name' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'string', 'null' => true, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'effective_date' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'created_on' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'updated_on' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'wiki_page_title' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'versions_project_id' => array('column' => 'project_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $watchers = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'watchable_type' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'watchable_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $wiki_content_versions = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'wiki_content_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'page_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'author_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'data' => array('type' => 'binary', 'null' => true, 'default' => NULL),
		'compression' => array('type' => 'string', 'null' => true, 'length' => 6, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'comments' => array('type' => 'string', 'null' => true, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'updated_on' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'version' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'wiki_content_versions_wcid' => array('column' => 'wiki_content_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $wiki_contents = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'page_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'author_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'text' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'comments' => array('type' => 'string', 'null' => true, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'updated_on' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'version' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'wiki_contents_page_id' => array('column' => 'page_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $wiki_pages = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'wiki_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'title' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created_on' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'protected' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'wiki_pages_wiki_id_title' => array('column' => array('wiki_id', 'title'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $wiki_redirects = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'wiki_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'title' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'redirects_to' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created_on' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'wiki_redirects_wiki_id_title' => array('column' => array('wiki_id', 'title'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $wikis = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'project_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'start_page' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'status' => array('type' => 'integer', 'null' => false, 'default' => '1'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'wikis_project_id' => array('column' => 'project_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
	public $workflows = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'tracker_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'old_status_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'new_status_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'role_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'wkfs_role_tracker_old_status' => array('column' => array('role_id', 'tracker_id', 'old_status_id'), 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
}
