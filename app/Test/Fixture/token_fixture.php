<?php 
class TokenFixture extends CakeTestFixture {
  var $name = 'Token';
  var $import = array('table'=>'tokens');
  var $records = array(
    array('created_on'=>'2007-01-21 00:39:12 +01:00', 'action'=>'register', 'id'=>1, 'value'=>'DwMJ2yIxBNeAk26znMYzYmz5dAiIina0GFrPnGTM', 'user_id'=>1),
    array('created_on'=>'2007-01-21 00:39:52 +01:00', 'action'=>'recovery', 'id'=>2, 'value'=>'sahYSIaoYrsZUef86sTHrLISdznW6ApF36h5WSnm', 'user_id'=>2),
  );
}