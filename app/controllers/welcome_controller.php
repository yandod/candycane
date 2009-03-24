<?php
class WelcomeController extends AppController
{
  var $uses = array('News'/*,'Project'*/);
  function index(){
  	$user = array(
  		'id' => 3,
  		'name' => 'yando',
  		'logged' => true,
  	);
    $this->set('news',$this->News->latest($user));
    //$projects = $this->Project->latest($this->User->current());
  }
}
