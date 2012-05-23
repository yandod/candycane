<?php

class CustomFieldsTracker extends AppModel
{
  var $name = 'CustomFieldsTracker';
  var $belongsTo = array('CustomField', 'Tracker');
  var $primaryKey = false;

}

