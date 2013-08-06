<?php

class News extends AppModel
{
    public $belongsTo = array(
        'Project' => array(),
        'Author' => array(
            'className' => 'User',
            'foreginKey' => 'author_id',
        ),
    );
    public $hasMany = array(
        'Comments' => array(
            'className' => 'Comment',
            'foreignKey' => 'commented_id',
            'conditions' => array('Comments.commented_type' => 'News'),
            'dependent' => true
        )
    );

    public $validate = array(
        'title' => array(
            'Required' => array('rule' => array('notEmpty'), 'required' => true),
            'maxLength' => array('rule' => array('maxLength', 60)),
        ),
        'summary' => array(
            'maxLength' => array('rule' => array('maxLength', 255)),
        ),
        'description' => array(
            'Required' => array('rule' => array('notEmpty'), 'required' => true),
        ),
    );

    public $actsAs = array(
        'ActivityProvider' => array(
            'find_options' => array('include' => array('Project', 'Author')),
            'author_key' => 'author_id'
        ),
        'Event' => array('url' => array('Proc' => '_event_url')),
        'Searchable' => array()
    );
    public $filterArgs = array(
        array('name' => 'description', 'type' => 'like'),
        array('name' => 'title', 'type' => 'like'),
    );

    public function _event_url($data)
    {
        return array(
            'controller' => 'news',
            'action' => 'show',
            'id' => $data['News']['id'],
            'project_id' => $data['Project']['id']
        );
    }

    public function latest($user, $count = 5)
    {
        $param = array(
            'order' => 'News.created_on DESC',
            'conditions' => $this->Project->allowed_to_condition($user, 'view_news'),
            'limit' => $count
        );
        return $this->find('all', $param);
    }
}