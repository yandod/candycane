<?php 
class RoleTestCase extends CakeTestCase {
  var $Role = null;
  var $autoFixtures = false;
  var $fixtures = array('app.role');

  function startTest() {
    $this->loadFixtures('Role');
    $this->Role =& ClassRegistry::init('Role');
  }
  function testIsMember() {
    // Manager is member
    $this->assertTrue($this->Role->is_member($this->Role->read(null, 1)));
    // Non member is not member
    $this->assertFalse($this->Role->is_member($this->Role->read(null, 4)));
    // Anonymous is not member
    $this->assertFalse($this->Role->is_member($this->Role->read(null, 5)));
  }
  function testNonMemberAllowedTo() {
    // Non member deny "edit_project"
    $this->assertFalse($this->Role->non_member_allowed_to("edit_project"));
//    $this->assertFalse($this->Role->non_member_allowed_to(array('action'=>"edit", "controller"=>"project")));
    // Non member allow "save_queries"
    $this->assertTrue($this->Role->non_member_allowed_to("save_queries"));
//    $this->assertTrue($this->Role->non_member_allowed_to(array('action'=>"save", "controller"=>"querieses")));
  }
  function testAnonymousAllowedTo() {
    // Non member deny "edit_project"
    $this->assertFalse($this->Role->anonymous_allowed_to("edit_project"));
//    $this->assertFalse($this->Role->anonymous_allowed_to(array('action'=>"edit", 'controller'=>"project")));
    // Non member allow "browse_repository"
    $this->assertTrue($this->Role->anonymous_allowed_to("browse_repository"));
//    $this->assertTrue($this->Role->anonymous_allowed_to(array('action'=>"browse", 'controller'=>"repositories")));
  }
  function testRoleExistsSubquery() {
    $tableName = $this->Role->table;
    $results = $this->Role->find('all', array('conditions'=>array(
      "EXISTS (SELECT ROLE.id FROM {$tableName} ROLE WHERE ROLE.name='Manager' AND ROLE.position=1)" => true
    )));
    $this->assertEqual(5, count($results));
  }
  function testRoleNotExistsSubquery() {
    $tableName = $this->Role->table;
    $results = $this->Role->find('all', array('conditions'=>array(
      "EXISTS (SELECT ROLE.id FROM {$tableName} ROLE WHERE ROLE.name='Manager' AND ROLE.position=0)" => true
    )));
    $this->assertEqual(0, count($results));
  }

}
?>