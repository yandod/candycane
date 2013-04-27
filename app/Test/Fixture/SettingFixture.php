<?php
class SettingFixture extends CakeTestFixture {
  var $name = 'Setting';
  var $import = array('table'=>'settings');
  var $records = array(
	array('id' => 1, 'name' => 'app_title', 'value' => 'TestCandyCane', 'updated_on' => '2013-04-27 12:14:46'),
  );
}
