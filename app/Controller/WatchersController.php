<?php

class WatchersController extends AppController
{
    var $name = 'Watchers';
    var $helpers = array(
        'Watchers',
        'Js'
    );
    var $components = array(
        'RequestHandler',
    );

#class WatchersController < ApplicationController
#  before_filter :find_project
#  before_filter :require_login, :check_project_privacy, :only => [:watch, :unwatch]
#  before_filter :authorize, :only => :new
#  
#  verify :method => :post,
#         :only => [ :watch, :unwatch ],
#         :render => { :nothing => true, :status => :method_not_allowed }
#  
    function watch()
    {
        $this->_set_watcher($this->current_user, true);
    }

    function unwatch()
    {
        $this->_set_watcher($this->current_user, false);
    }

    public function add()
    {
        if (
            $this->RequestHandler->isPost() &&
            !empty($this->request->data['Watcher']['user_id'])
        ) {
            $Model = & ClassRegistry::init(ucfirst($this->request->params['named']['object_type']));
            if (
                $Model->read(null, $this->request->params['named']['object_id']) &&
                $this->Watcher->User->read(null, $this->request->data['Watcher']['user_id'])
            ) {
                $Model->add_watcher($this->Watcher->User->data);
                $this->redirect(env('HTTP_REFERER'));
            }
        }
        //Configure::write('debug', 0);
        extract($this->request->params['named']);
        $Model = & ClassRegistry::init(Inflector::camelize($object_type));
        $data = $Model->read(null, $object_id);
        $project_id = $Model->get_watched_project_id();
        $project = $this->Project->read('identifier', $project_id);
        $this->request->params['project_id'] = $project['Project']['identifier'];
        parent::_findProject();
        $members = $this->Project->members($project_id);
        if (!empty($data['Watcher'])) {
            foreach ($data['Watcher'] as $value) {
                if (array_key_exists($value['user_id'], $members)) {
                    unset($members[$value['user_id']]);
                }
            }
        }
        $this->set(array_merge(compact('members', 'object_type', 'object_id', 'data')));
        if ($this->RequestHandler->isAjax()) {
            $this->render('_watchers');
            $this->layout = 'ajax';
        } else {
            //$this->redirect(env('HTTP_REFERER'));
        }
        $this->render('_watchers');
    }

    function _set_watcher($user, $watching)
    {
        Configure::write('debug', 0);
        $Model = & ClassRegistry::init($this->request->params['named']['object_type']);
        $Model->read(null, $this->request->params['named']['object_id']);
        $this->set('data', $Model->data);
        $result = $Model->set_watcher(array('User' => $user), $watching);
        if ($this->RequestHandler->isAjax()) {
            $this->layout = 'ajax';
            $this->render('update');
        } else {
            $this->redirect(env('HTTP_REFERER'));
        }
    }
}