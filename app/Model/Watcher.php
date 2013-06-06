<?php

class Watcher extends AppModel
{
    var $name = 'Watcher';
    var $belongsTo = array('User');

    var $validate = array(
        'user_id' => array(
            'validates_presence_of'   => array('rule' => array('activeUser')),
            'validates_uniqueness_of' => array('rule' => array('isUnique')),
        ),
    );

    function activeUser($data)
    {
        if (empty($this->data[$this->name]['user_id'])) return false;
        return $this->User->is_active($this->data[$this->name]['user_id']);
    }

    function isUnique($field, $data)
    {
        return parent::isUnique(array('user_id', 'watchable_type', 'watchable_id'), false);
    }
}