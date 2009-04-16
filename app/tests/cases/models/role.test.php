<?php 
class RoleTestCase extends CakeTestCase {
  var $Role = null;
  var $fixtures = array('app.role');

  function startTest() {
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


}
?>