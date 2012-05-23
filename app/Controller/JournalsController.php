<?php
## redMine - project management software
## Copyright (C) 2006-2008  Jean-Philippe Lang
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
class JournalsController extends AppController
{
  var $name = 'Journals';
  var $components = array(
    'RequestHandler',
  );
  var $helpers = array(
    'Journals'
  );
  
  function edit($id) {
    if($this->RequestHandler->isAjax()) {
      $this->layout = 'ajax';
      Configure::write('debug', 0);
    }
    $journal = $this->_find_journal($id);
    $this->set(compact('journal'));
    $delete = false;
    if(!empty($journal) && !empty($this->request->data)) {
      if(empty($journal['JournalDetails']) && ($this->request->data['Journal']['notes'] == '')) {
        $delete = $this->Journal->delete($id);
      } else {
        $this->Journal->saveField('notes', $this->request->data['Journal']['notes']);
      }
      $this->set(compact('delete'));
      // TODO call_hook for Plugins.
      // call_hook(:controller_journals_edit_post, { :journal => @journal, :params => params})
      
      if($this->RequestHandler->isAjax()) {
        $this->render('update');
      } else {
        $this->redirect(array('controller'=>'issues', 'action'=>'show', 'id'=>$journal['Journal']['journalized_id']));
      }
    } else {
      $this->request->data = $journal;
    }
  }
  
  function _find_journal($id) {
    $this->Journal->recursive = 1;
    $journal = $this->Journal->read(null, $id);
    if(empty($journal)) {
      throw new NotFoundException();
    }
    if(!$this->Journal->is_editable_by($this->current_user)) {
      $this->cakeError('error403');
    }
    return $journal;
  }
}
