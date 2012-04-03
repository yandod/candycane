<?php
## redMine - project management software
## Copyright (C) 2006-2007  Jean-Philippe Lang
##
## This program is free software; you can redistribute it and/or
## modify it under the terms of the GNU General Public License
## as published by the Free Software Foundation; either version 2
## of the License, or (at your option) any later version.
## 
## This program is distributed in the hope that it will be useful,
## but WITHOUT ANY WARRANTY; without even the implied warranty of
## MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
## GNU General Public License for more details.
## 
## You should have received a copy of the GNU General Public License
## along with this program; if not, write to the Free Software
## Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
#
class WatchersController extends AppController
{
  var $name = 'Watchers';
  var $helpers = array(
    'Watchers'
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
  function watch() {
    $this->_set_watcher($this->current_user, true);
  }
  
  function unwatch() {
    $this->_set_watcher($this->current_user, false);
  }
  
  function add() {
    if($this->RequestHandler->isPost() && !empty($this->request->data['Watcher']['user_id'])) {
      $Model = & ClassRegistry::init($this->request->params['named']['object_type']);
      if($Model->read(null, $this->request->params['named']['object_id']) && $this->Watcher->User->read(null, $this->request->data['Watcher']['user_id'])) {
        $Model->add_watcher($this->Watcher->User->data);
      }
    }
    Configure::write('debug', 0);
    extract($this->request->params['named']);
    $Model = & ClassRegistry::init(Inflector::camelize($object_type));
    $data = $Model->read(null, $object_id);
    $project_id = $Model->get_watched_project_id();
    $project = $this->Project->read('identifier', $project_id);
    $this->request->params['project_id'] = $project['Project']['identifier'];
    parent::_findProject();
    $members = $this->Project->members($project_id);
    if(!empty($data['Watcher'])) {
      foreach($data['Watcher'] as $value) {
        if(array_key_exists($value['user_id'], $members)) {
          unset($members[$value['user_id']]);
        }
      }
    }
    $this->set(array_merge(compact('members', 'object_type', 'object_id', 'data')));
    if($this->RequestHandler->isAjax()) {
      $this->render('_watchers');
      $this->layout = 'ajax';
    } else {
      $this->redirect(env('HTTP_REFERER'));
    }
  }
  function _set_watcher($user, $watching) {
    Configure::write('debug', 0);
    $Model = & ClassRegistry::init($this->request->params['named']['object_type']);
    $Model->read(null, $this->request->params['named']['object_id']);
    $this->set('data', $Model->data);
    $result = $Model->set_watcher(array('User'=>$user), $watching);
    if($this->RequestHandler->isAjax()) {
      $this->layout = 'ajax';
      $this->render('update');
    } else {
      $this->redirect(env('HTTP_REFERER'));
    }
  }
}
