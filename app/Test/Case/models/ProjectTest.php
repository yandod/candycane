<?php 
class ProjectTest extends CakeTestCase {
  var $Project = null;
  var $autoFixtures = false;
  var $fixtures = array(
      'app.issue', 'app.project', 'app.tracker', 'app.issue_status', 'app.user', 'app.version',
      'app.enumeration', 'app.issue_category', 'app.token', 'app.member', 'app.role', 'app.user_preference',
      'app.issue_category', 'app.time_entry', 'app.changeset', 'app.changesets_issue', 'app.attachment',
      'app.projects_tracker', 'app.custom_value', 'app.custom_field', 'app.watcher', 'app.issue_relation',
      'app.journal', 'app.journal_detail', 'app.workflow', 'app.enabled_module',
      'app.wiki', 'app.wiki_page', 'app.wiki_content', 'app.wiki_content_version', 'app.wiki_redirect','app.workflow',
	  'app.custom_fields_project'
      );

  function startTest() {
    $this->Project =& ClassRegistry::init('Project');
  }

  function test_findMainProject() {
    $this->loadFixtures('Issue', 'Project', 'Tracker', 'IssueStatus', 'User', 'Version', 'Enumeration', 'IssueCategory', 'TimeEntry', 'Changeset', 'EnabledModule','CustomFieldsProject');
    $project = $this->Project->findMainProject('ecookbook');
    
    $this->assertEqual('eCookbook', $project['Project']['name']);
    $this->assertEqual(8, count($project['EnabledModule']));

    $this->assertEqual(array('issue_tracking','time_tracking','news','documents','files','wiki','repository','boards'), Set::extract('{n}.name', $project['EnabledModule']));
    
  }

}
?>