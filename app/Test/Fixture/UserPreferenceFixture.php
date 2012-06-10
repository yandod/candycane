<?php 
class UserPreferenceFixture extends CakeTestFixture {
  var $name = 'UserPreference';
  var $import = array('table'=>'user_preferences');
  var $records = array(

array(
'others'=>'|
    --- 
    :my_page_layout: 
      left: 
      - latest_news
      - documents
      right: 
      - issues_assigned_to_me
      - issues_reported_by_me
      top: 
      - calendar
',
'id'=>1,
'user_id'=>1,
'hide_mail'=>true),

array(
'others'=>'|+
    --- {}
',
'id'=>2,
'user_id'=>3,
'hide_mail'=>false),
);

}