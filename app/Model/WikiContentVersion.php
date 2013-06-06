<?php

class WikiContentVersion extends AppModel
{
    var $name = 'WikiContentVersion';
    var $belongsTo = array(
        'Author' => array(
            'className'  => 'User',
            'foreignKey' => 'author_id'
        ),
    );
}