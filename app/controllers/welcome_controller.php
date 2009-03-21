<?php
class WelcomeController extends AppController
{
  var $uses = array('News'/*,'Project'*/);
  function index(){
    //$news = $this->News->latest($this->User->current());
    //$projects = $this->Project->latest($this->User->current());
  }
}
