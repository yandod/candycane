<?php

class CustomFieldsProject extends AppModel
{
    var $name = 'CustomFieldsProject';
    var $belongsTo = array('CustomField', 'Project');
}

