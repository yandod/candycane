<?php
class AppHelper extends Helper
{
  var $Settings;
  
  function __construct()
  {
    $this->Settings =& ClassRegistry::getObject('Setting');
    parent::__construct();
  }
}