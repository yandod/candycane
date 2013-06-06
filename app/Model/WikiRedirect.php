<?php

class WikiRedirect extends AppModel
{
    var $name = 'WikiRedirect';
    var $validate = array(
        'title'        => array(
            'validates_presence_of' => array('rule' => 'notEmpty'),
            'validates_length_of'   => array('rule' => array('maxLength', 255))
        ),
        'redirects_to' => array(
            'validates_presence_of' => array('rule' => 'notEmpty'),
            'validates_length_of'   => array('rule' => array('maxLength', 255))
        )
    );
}
